<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Services\LibraryTransactionService;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function __construct(private LibraryTransactionService $library)
    {
    }

    // Menampilkan daftar permintaan pengembalian.
    public function index()
    {
        $this->library->refreshOverdueLoans();

        $peminjaman = Peminjaman::with(['buku', 'anggota'])
            ->dipinjam()
            ->pendingReturn()
            ->latest('return_requested_at')
            ->paginate(10);

        return view('admin.pengembalian.index', compact('peminjaman'));
    }

    // Memproses pengembalian buku yang disetujui.
    public function proses(Request $request, Peminjaman $peminjaman)
    {
        $denda = $this->library->approveReturn($peminjaman);

        $msg = 'Buku berhasil dikembalikan.';
        if ($denda > 0) {
            $msg .= ' Denda: Rp ' . number_format($denda, 0, ',', '.');
        }

        return redirect()->route('admin.pengembalian.index')->with('success', $msg);
    }

    // Menolak permintaan pengembalian buku.
    public function reject(Request $request, Peminjaman $peminjaman)
    {
        if (!$peminjaman->isApprovedLoan() || !$peminjaman->hasPendingReturn()) {
            return back()->with('error', 'Permintaan pengembalian ini tidak valid atau sudah diproses.');
        }

        $validated = $request->validate([
            'return_note' => ['nullable', 'string', 'max:255'],
        ]);

        $peminjaman->update([
            'return_status' => 'rejected',
            'return_note' => $validated['return_note'] ?? 'Permintaan pengembalian ditolak oleh admin.',
            'return_processed_at' => now(),
        ]);

        return back()->with('success', 'Permintaan pengembalian berhasil ditolak.');
    }
}
