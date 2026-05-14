<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    // Menampilkan ringkasan data utama untuk dashboard admin.
    public function index()
    {
        $totalBuku = Buku::count();
        $totalAnggota = Anggota::count();
        $totalDipinjam = Peminjaman::dipinjam()->count();
        $totalDenda = Peminjaman::sum('denda');
        $pendingPeminjaman = Peminjaman::pendingApproval()->count();
        $pendingPengembalian = Peminjaman::pendingReturn()->count();

        $recentPinjam = Peminjaman::with(['buku', 'anggota'])
            ->latest('tgl_pinjam')
            ->limit(5)
            ->get();

        $bukuTerpopuler = Buku::withCount('peminjaman')
            ->orderByDesc('peminjaman_count')
            ->limit(5)
            ->get();

        $terlambat = Peminjaman::with(['buku', 'anggota'])
            ->terlambat()
            ->latest('tgl_kembali_rencana')
            ->limit(10)
            ->get();

        $laporanBulanan = Peminjaman::selectRaw(
            'MONTH(tgl_pinjam) as bulan,
            YEAR(tgl_pinjam) as tahun,
            COUNT(*) as total_pinjam,
            SUM(denda) as total_denda'
        )
            ->whereYear('tgl_pinjam', now()->year)
            ->groupByRaw('YEAR(tgl_pinjam), MONTH(tgl_pinjam)')
            ->orderByRaw('YEAR(tgl_pinjam), MONTH(tgl_pinjam)')
            ->get();

        return view('admin.dashboard.index', compact(
            'totalBuku',
            'totalAnggota',
            'totalDipinjam',
            'totalDenda',
            'pendingPeminjaman',
            'pendingPengembalian',
            'recentPinjam',
            'bukuTerpopuler',
            'terlambat',
            'laporanBulanan'
        ));
    }
}
