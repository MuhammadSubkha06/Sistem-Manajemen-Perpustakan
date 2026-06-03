<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class MemberController extends Controller
{
    // Menampilkan daftar anggota beserta statistik ringkas.
    public function index(Request $request)
    {
        $query = Anggota::query();

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                    ->orWhere('nis', 'like', "%{$keyword}%")
                    ->orWhere('kelas', 'like', "%{$keyword}%");
            });
        }

        $members = $query->withCount([
            'peminjaman as peminjaman_aktif_count' => fn ($q) => $q->dipinjam(),
        ])->latest()->paginate(10)->withQueryString();

        $stats = [
            'total_anggota' => Anggota::count(),
            'aktif_anggota' => Anggota::whereHas('peminjaman')->count(),
            'nonaktif_anggota' => Anggota::doesntHave('peminjaman')->count(),
            'total_buku' => Buku::count(),
            'dipinjam_hari_ini' => Peminjaman::dipinjam()->whereDate('created_at', today())->count(),
            'denda_bulan_ini' => Peminjaman::whereMonth('created_at', now()->month)->sum('denda'),
        ];

        $bukuTerpopuler = Buku::whereHas('peminjaman')
            ->withCount('peminjaman')
            ->orderByDesc('peminjaman_count')
            ->get();

        $terlambat = Peminjaman::with(['anggota', 'buku'])->terlambat()->get();

        return view('admin.members.index', compact('members', 'stats', 'bukuTerpopuler', 'terlambat'));
    }

    // Menampilkan form tambah anggota.
    public function create()
    {
        return view('admin.members.create');
    }

    // Menyimpan data anggota baru.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|string|max:20|unique:anggotas,nis',
            'nama' => 'required|string|max:100',
            'kelas' => 'required|string|max:20',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['nama'],
                'email' => $this->emailFromNis($validated['nis']),
                'mobile' => $validated['no_hp'] ?? null,
                'password' => Hash::make($validated['password']),
                'role_id' => 2,
                'role' => 'anggota',
            ]);

            Anggota::create([
                'user_id' => $user->id,
                'nis' => $validated['nis'],
                'nama' => $validated['nama'],
                'kelas' => $validated['kelas'],
                'no_hp' => $validated['no_hp'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
            ]);
        });

        return redirect()->route('admin.members.index')
            ->with('success', 'Anggota berhasil ditambahkan.');
    }

    // Menampilkan detail anggota.
    public function show(Anggota $member)
    {
        $member->load('peminjaman.buku');

        return view('admin.members.show', compact('member'));
    }

    // Menampilkan form edit anggota.
    public function edit(Anggota $member)
    {
        return view('admin.members.edit', compact('member'));
    }

    // Memperbarui data anggota.
    public function update(Request $request, Anggota $member)
    {
        $validated = $request->validate([
            'nis' => 'required|string|max:20|unique:anggotas,nis,' . $member->id,
            'nama' => 'required|string|max:100',
            'kelas' => 'required|string|max:20',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'password' => ['nullable', 'confirmed', Password::min(6)],
        ]);

        DB::transaction(function () use ($member, $validated) {
            $member->update([
                'nis' => $validated['nis'],
                'nama' => $validated['nama'],
                'kelas' => $validated['kelas'],
                'no_hp' => $validated['no_hp'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
            ]);

            $user = $member->user ?: User::create([
                'name' => $validated['nama'],
                'email' => $this->emailFromNis($validated['nis']),
                'mobile' => $validated['no_hp'] ?? null,
                'password' => Hash::make($validated['password'] ?? $validated['nis']),
                'role_id' => 2,
                'role' => 'anggota',
            ]);

            $userData = [
                'name' => $validated['nama'],
                'email' => $this->emailFromNis($validated['nis']),
                'mobile' => $validated['no_hp'] ?? null,
                'role_id' => 2,
                'role' => 'anggota',
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $user->update($userData);
            $member->update(['user_id' => $user->id]);
        });

        return redirect()->route('admin.members.index')
            ->with('success', 'Data anggota berhasil diperbarui.');
    }

    // Menghapus anggota jika tidak punya pinjaman aktif.
    public function destroy(Anggota $member)
    {
        if ($member->peminjamanAktif()->exists()) {
            return back()->with('error', 'Anggota tidak dapat dihapus karena masih memiliki pinjaman aktif.');
        }

        DB::transaction(function () use ($member) {
            $user = $member->user;
            $member->delete();
            $user?->delete();
        });

        return redirect()->route('admin.members.index')
            ->with('success', 'Anggota berhasil dihapus.');
    }

    // Menampilkan riwayat peminjaman anggota.
    public function history(Anggota $member)
    {
        $member->load('peminjaman.buku');

        return view('admin.members.history', compact('member'));
    }

    private function emailFromNis(string $nis): string
    {
        return $nis . '@anggota.local';
    }
}
