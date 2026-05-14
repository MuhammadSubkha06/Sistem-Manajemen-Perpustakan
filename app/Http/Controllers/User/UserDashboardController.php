<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    // Menampilkan dashboard anggota beserta ringkasan pinjaman.
    public function index()
    {
        $anggota = auth()->user()->anggota;

        $stats = [
            'sedang_pinjam' => 0,
            'total_pinjam'  => 0,
            'denda_aktif'   => 0,
            'menunggu_persetujuan' => 0,
            'pengembalian_pending' => 0,
        ];

        $riwayat = collect();
        $katalogBuku = Buku::with('kategoris')
            ->latest()
            ->limit(8)
            ->get();

        if ($anggota) {
            $stats['sedang_pinjam'] = $anggota->peminjaman()->dipinjam()->count();
            $stats['total_pinjam'] = $anggota->peminjaman()->count();
            $stats['denda_aktif'] = $anggota->peminjaman()->where('denda', '>', 0)->sum('denda');
            $stats['menunggu_persetujuan'] = $anggota->peminjaman()->where('approval_status', 'pending')->count();
            $stats['pengembalian_pending'] = $anggota->peminjaman()->where('return_status', 'pending')->count();

            $riwayat = $anggota->peminjaman()
                ->with('buku')
                ->latest()
                ->limit(5)
                ->get();
        }

        return view('user.dashboard.index', compact('stats', 'riwayat', 'anggota', 'katalogBuku'));
    }

    // Menampilkan profil anggota.
    public function profile()
    {
        $anggota = auth()->user()->anggota;

        return view('user.dashboard.profile', compact('anggota'));
    }

    // Memperbarui kontak dan alamat anggota.
    public function updateProfile(Request $request)
    {
        $request->validate([
            'no_hp'  => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        auth()->user()->anggota?->update($request->only('no_hp', 'alamat'));

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
