<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Services\LibraryTransactionService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class UserPeminjamanController extends Controller
{
    public function __construct(private LibraryTransactionService $library)
    {
    }

    // Menampilkan riwayat peminjaman milik anggota.
    public function index()
    {
        $this->library->refreshOverdueLoans();

        $anggota = auth()->user()->anggota;

        if (!$anggota) {
            return view('user.peminjaman.index', [
                'peminjaman' => new LengthAwarePaginator([], 0, 10),
            ]);
        }

        $peminjaman = $anggota->peminjaman()
            ->with(['buku', 'struks' => fn ($query) => $query->approved()])
            ->latest()
            ->paginate(10);

        return view('user.peminjaman.index', compact('peminjaman'));
    }

    // Menampilkan form pengajuan peminjaman.
    public function create()
    {
        $buku = Buku::tersedia()->orderBy('judul')->get();

        return view('user.peminjaman.create', compact('buku'));
    }

    // Menyimpan pengajuan peminjaman dari anggota.
    public function store(Request $request)
    {
        $anggota = auth()->user()->anggota;

        if (!$anggota) {
            return back()->with('error', 'Data anggota Anda belum terdaftar. Hubungi admin.');
        }

        $validated = $request->validate([
            'buku_id'             => 'required|exists:buku,id',
            'tgl_kembali_rencana' => 'required|date|after:today',
        ]);

        $this->library->submitLoanRequest($anggota, $validated);

        return redirect()->route('user.peminjaman.index')
            ->with('success', 'Permohonan peminjaman berhasil diajukan dan menunggu persetujuan admin.');
    }

    // Menampilkan detail peminjaman milik anggota.
    public function show(Peminjaman $peminjaman)
    {
        $this->library->refreshOverdueLoans();

        $anggota = auth()->user()->anggota;

        abort_if(!$anggota || $peminjaman->anggota_id !== $anggota->id, 403);

        $peminjaman->load(['buku', 'struks' => fn ($query) => $query->approved()]);

        return view('user.peminjaman.show', compact('peminjaman'));
    }

    // Mengajukan permintaan pengembalian buku.
    public function requestReturn(Peminjaman $peminjaman)
    {
        $anggota = auth()->user()->anggota;

        abort_if(!$anggota || $peminjaman->anggota_id !== $anggota->id, 403);

        if (!$peminjaman->isApprovedLoan()) {
            return back()->with('error', 'Peminjaman ini belum aktif untuk diajukan pengembalian.');
        }

        if ($peminjaman->return_status === 'pending') {
            return back()->with('error', 'Permintaan pengembalian sudah diajukan dan sedang menunggu persetujuan admin.');
        }

        $peminjaman->update([
            'return_status' => 'pending',
            'return_requested_at' => now(),
            'return_note' => null,
        ]);

        return back()->with('success', 'Permintaan pengembalian berhasil diajukan.');
    }
}
