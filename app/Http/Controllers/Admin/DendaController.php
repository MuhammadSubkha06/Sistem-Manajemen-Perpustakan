<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Services\LibraryTransactionService;

class DendaController extends Controller
{
    public function __construct(private LibraryTransactionService $library)
    {
    }

    // Menampilkan daftar denda yang pernah tercatat.
    public function index()
    {
        $this->library->refreshOverdueLoans();

        $denda = Peminjaman::with(['buku', 'anggota'])
            ->where('status', 'dikembalikan')
            ->where('denda', '>', 0)
            ->latest('updated_at')
            ->paginate(10);

        $totalDenda = Peminjaman::where('denda', '>', 0)->sum('denda');

        return view('admin.denda.index', compact('denda', 'totalDenda'));
    }

    // Menghapus nominal denda setelah dibayar.
    public function bayar(Peminjaman $peminjaman)
    {
        $this->library->payFine($peminjaman);

        return back()->with('success', 'Denda berhasil dikonfirmasi sebagai lunas.');
    }
}
