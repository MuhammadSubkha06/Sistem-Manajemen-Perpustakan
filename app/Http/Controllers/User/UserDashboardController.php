<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Services\LibraryTransactionService;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function __construct(private LibraryTransactionService $library)
    {
    }

    // Menampilkan dashboard anggota beserta ringkasan pinjaman.
    public function index()
    {
        $this->library->refreshOverdueLoans();

        $anggota = auth()->user()->anggota;

        $stats = [
            'sedang_pinjam' => 0,
            'total_pinjam'  => 0,
            'denda_aktif'   => 0,
            'menunggu_persetujuan' => 0,
            'pengembalian_pending' => 0,
        ];

        $riwayat = collect();
        $strukTerbaru = collect();
        $katalogBuku = Buku::with('kategoris')
            ->latest()
            ->limit(8)
            ->get();
        $bukuTerpopuler = Buku::whereHas('peminjaman')
            ->withCount('peminjaman')
            ->orderByDesc('peminjaman_count')
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

            $strukTerbaru = $anggota->struks()
                ->approved()
                ->with('peminjaman.buku')
                ->latest('issued_at')
                ->limit(3)
                ->get();
        }

        return view('user.dashboard.index', compact('stats', 'riwayat', 'anggota', 'katalogBuku', 'strukTerbaru', 'bukuTerpopuler'));
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
