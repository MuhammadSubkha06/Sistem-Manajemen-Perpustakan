<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ReportController extends Controller
{
    // ─── Shared ────────────────────────────────────────────────

    /**
     * Query dasar peminjaman dengan filter tahun/bulan.
     */
    private function baseQuery(int $tahun, ?int $bulan)
    {
        return Peminjaman::query()
            ->whereYear('tgl_pinjam', $tahun)
            ->when($bulan, fn ($q) => $q->whereMonth('tgl_pinjam', $bulan));
    }

    /**
     * Ringkasan per-bulan untuk tahun yang dipilih.
     */
    private function getRingkasan(int $tahun, ?int $bulan)
    {
        return Peminjaman::selectRaw("
            MONTH(tgl_pinjam)                                              AS bulan,
            YEAR(tgl_pinjam)                                               AS tahun,
            COUNT(*)                                                        AS total_pinjam,
            SUM(tgl_kembali_aktual IS NOT NULL)                            AS total_kembali,
            COALESCE(SUM(denda), 0)                                        AS total_denda
        ")
            ->whereYear('tgl_pinjam', $tahun)
            ->when($bulan, fn ($q) => $q->whereMonth('tgl_pinjam', $bulan))
            ->groupByRaw('YEAR(tgl_pinjam), MONTH(tgl_pinjam)')
            ->orderByRaw('YEAR(tgl_pinjam), MONTH(tgl_pinjam)')
            ->get();
    }

    /**
     * Statistik pelanggaran anggota (yang punya denda atau terlambat).
     */
    private function getStatistikPelanggaran(int $tahun, ?int $bulan)
    {
        return Anggota::from('anggotas as a')
            ->selectRaw("
                a.id, a.nama,
                COUNT(CASE WHEN p.denda > 0 THEN 1 END)                                    AS total_denda_count,
                COALESCE(SUM(p.denda), 0)                                                   AS total_denda_amount,
                COUNT(CASE WHEN p.tgl_kembali_aktual > p.tgl_kembali_rencana THEN 1 END)   AS late_return_count
            ")
            ->leftJoin('peminjamans as p', function ($join) use ($tahun, $bulan) {
                $join->on('a.id', '=', 'p.anggota_id')
                    ->whereYear('p.tgl_pinjam', $tahun);

                if ($bulan) {
                    $join->whereMonth('p.tgl_pinjam', $bulan);
                }
            })
            ->groupBy('a.id', 'a.nama')
            ->having('total_denda_count', '>', 0)
            ->orHaving('late_return_count', '>', 0)
            ->orderByDesc('total_denda_amount')
            ->get();
    }

    /**
     * Daftar tahun yang tersedia di tabel peminjaman.
     */
    private function getTahunList()
    {
        $list = Peminjaman::selectRaw('YEAR(tgl_pinjam) as tahun')
            ->distinct()
            ->orderByRaw('YEAR(tgl_pinjam) DESC')
            ->pluck('tahun');

        // Pastikan tahun berjalan selalu ada
        return $list->contains(now()->year)
            ? $list
            : $list->prepend(now()->year);
    }

    // ─── Index ──────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $tahun = (int) $request->get('tahun', now()->year);
        $bulan = $request->filled('bulan') ? (int) $request->get('bulan') : null;

        $laporanPeminjaman   = $this->baseQuery($tahun, $bulan)
            ->with(['buku', 'anggota'])
            ->latest('tgl_pinjam')
            ->paginate(20)
            ->withQueryString();

        $ringkasanBulanan    = $this->getRingkasan($tahun, $bulan);
        $statistikPelanggaran = $this->getStatistikPelanggaran($tahun, $bulan);
        $tahunList           = $this->getTahunList();

        return view('admin.report.index', compact(
            'laporanPeminjaman',
            'ringkasanBulanan',
            'statistikPelanggaran',
            'tahun',
            'bulan',
            'tahunList',
        ));
    }

    // ─── Export PDF ─────────────────────────────────────────────

    public function exportPDF(Request $request)
    {
        $tahun = (int) $request->get('tahun', now()->year);
        $bulan = $request->filled('bulan') ? (int) $request->get('bulan') : null;

        $laporanPeminjaman    = $this->baseQuery($tahun, $bulan)
            ->with(['buku', 'anggota'])
            ->latest('tgl_pinjam')
            ->get();

        $ringkasanBulanan     = $this->getRingkasan($tahun, $bulan);
        $statistikPelanggaran = $this->getStatistikPelanggaran($tahun, $bulan);
        $filename             = 'Laporan-Peminjaman-' . ($bulan ? "{$bulan}-{$tahun}" : $tahun) . '.pdf';

        $pdf = Pdf::loadView('admin.report.pdf', compact(
            'laporanPeminjaman',
            'ringkasanBulanan',
            'statistikPelanggaran',
            'tahun',
            'bulan',
            'filename',
        ))
            ->setPaper('a4', 'landscape')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isPhpEnabled', true)
            ->setOption('defaultFont', 'Arial');

        return $pdf->download($filename);
    }

    // ─── Export Excel (CSV) ─────────────────────────────────────

    public function exportExcel(Request $request): Response
    {
        $tahun = (int) $request->get('tahun', now()->year);
        $bulan = $request->filled('bulan') ? (int) $request->get('bulan') : null;

        $rows = $this->baseQuery($tahun, $bulan)
            ->with(['buku', 'anggota'])
            ->latest('tgl_pinjam')
            ->get();

        $filename = 'Laporan-Peminjaman-' . ($bulan ? "{$bulan}-{$tahun}" : $tahun) . '.csv';

        // UTF-8 BOM agar Excel membaca encoding dengan benar
        $lines = ["\xEF\xBB\xBF"];
        $lines[] = implode(',', [
            'No', 'Nama Anggota', 'NIS', 'Judul Buku',
            'Tgl Pinjam', 'Tgl Kembali Rencana', 'Tgl Kembali Aktual',
            'Status', 'Denda',
        ]);

        foreach ($rows as $i => $item) {
            $lines[] = implode(',', [
                $i + 1,
                '"' . str_replace('"', '""', $item->anggota->nama ?? '-') . '"',
                '"' . ($item->anggota->nis ?? '-') . '"',
                '"' . str_replace('"', '""', $item->buku->judul ?? '-') . '"',
                $item->tgl_pinjam->format('d-m-Y'),
                $item->tgl_kembali_rencana->format('d-m-Y'),
                $item->tgl_kembali_aktual?->format('d-m-Y') ?? '-',
                ucfirst($item->status),
                $item->denda > 0 ? number_format($item->denda, 0, ',', '.') : '0',
            ]);
        }

        return response(implode("\n", $lines))
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}