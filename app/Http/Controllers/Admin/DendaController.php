<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Struk;

class DendaController extends Controller
{
    // Menampilkan daftar denda yang pernah tercatat.
    public function index()
    {
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
        $nominal = (int) $peminjaman->denda;

        if ($nominal < 1) {
            return back()->with('error', 'Denda sudah lunas.');
        }

        Struk::buatUntukPeminjaman(
            $peminjaman->load(['anggota', 'buku']),
            'pembayaran_denda',
            'Bukti Pembayaran Denda',
            $nominal
        );

        $peminjaman->update(['denda' => 0]);

        return back()->with('success', 'Denda berhasil dikonfirmasi sebagai lunas.');
    }
}
