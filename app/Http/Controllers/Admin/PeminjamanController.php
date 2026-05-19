<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Services\LibraryTransactionService;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function __construct(private LibraryTransactionService $library)
    {
    }

    // Mengarahkan filter ke daftar peminjaman.
    public function filter(Request $request)
    {
        return $this->index($request);
    }

    // Menampilkan daftar peminjaman sesuai filter.
    public function index(Request $request)
    {
        $this->library->refreshOverdueLoans();

        $query = Peminjaman::with(['buku', 'anggota']);

        if ($request->filled('status')) {
            if ($request->status === 'terlambat') {
                $query->terlambat();
            } elseif (in_array($request->status, ['pending', 'approved', 'rejected'], true)) {
                $query->where('approval_status', $request->status);
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('tgl_dari')) {
            $query->whereDate('tgl_pinjam', '>=', $request->tgl_dari);
        }

        if ($request->filled('tgl_sampai')) {
            $query->whereDate('tgl_pinjam', '<=', $request->tgl_sampai);
        }

        $peminjaman = $query->latest()->paginate(10)->withQueryString();

        return view('admin.peminjaman.index', compact('peminjaman'));
    }

    // Menampilkan form tambah peminjaman.
    public function create()
    {
        $anggota = Anggota::orderBy('nama')->get();
        $buku = Buku::tersedia()->orderBy('judul')->get();

        return view('admin.peminjaman.create', compact('anggota', 'buku'));
    }

    // Menyimpan peminjaman yang dibuat admin.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'anggota_id'          => 'required|exists:anggotas,id',
            'buku_id'             => 'required|exists:buku,id',
            'tgl_pinjam'          => 'required|date',
            'tgl_kembali_rencana' => 'required|date|after:tgl_pinjam',
        ]);

        $this->library->createManualLoan($validated);

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Peminjaman berhasil dicatat.');
    }

    // Menampilkan detail peminjaman.
    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['buku', 'anggota']);

        return view('admin.peminjaman.show', compact('peminjaman'));
    }

    // Menampilkan form edit peminjaman.
    public function edit(Peminjaman $peminjaman)
    {
        $peminjaman->load(['buku', 'anggota']);

        return view('admin.peminjaman.edit', compact('peminjaman'));
    }

    // Memperbarui tanggal peminjaman.
    public function update(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'tgl_pinjam' => 'required|date',
            'tgl_kembali_rencana' => 'required|date|after:tgl_pinjam',
        ]);

        $peminjaman->update($validated);

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Data peminjaman berhasil diperbarui.');
    }

    // Menghapus data peminjaman yang tidak aktif.
    public function destroy(Peminjaman $peminjaman)
    {
        if ($peminjaman->isApprovedLoan()) {
            return back()->with('error', 'Peminjaman aktif tidak dapat dihapus.');
        }

        $peminjaman->delete();

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Data peminjaman dihapus.');
    }

    // Menyetujui permohonan peminjaman.
    public function approve(Peminjaman $peminjaman)
    {
        $this->library->approveLoan($peminjaman);

        return back()->with('success', 'Permohonan peminjaman berhasil disetujui.');
    }

    // Menolak permohonan peminjaman.
    public function reject(Request $request, Peminjaman $peminjaman)
    {
        if (!$peminjaman->isPendingApproval()) {
            return back()->with('error', 'Permohonan ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'approval_note' => ['nullable', 'string', 'max:255'],
        ]);

        $peminjaman->update([
            'approval_status' => 'rejected',
            'approval_note' => $validated['approval_note'] ?? 'Permohonan ditolak oleh admin.',
        ]);

        return back()->with('success', 'Permohonan peminjaman berhasil ditolak.');
    }
}
