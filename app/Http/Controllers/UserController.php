<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    // Menampilkan daftar user admin.
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::when(
            $search,
            fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
            )
            ->latest('id')
            ->get();

        return view('admin.users.index', compact('users', 'search'));
    }

    // Menampilkan detail user dan data peminjamannya.
    public function show(User $user)
    {
        $peminjaman = Peminjaman::with('buku')
            ->where('user_id', $user->id)
            ->latest('id')
            ->get();

        return view('admin.users.show', compact('user', 'peminjaman'));
    }

    // Menampilkan form tambah user.
    public function create()
    {
        return view('admin.users.create');
    }

    // Menyimpan user baru.
    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'mobile'   => ['required', 'string', 'max:20'],
            'role_id'  => ['required', 'in:1,2'],
            'password' => ['required', Password::min(8)],
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'mobile'   => $request->mobile,
            'role_id'  => $request->role_id,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Anggota berhasil ditambahkan.');
    }

    // Menampilkan form edit user.
    public function edit(User $user)
    {
        $peminjamanAktif = Peminjaman::where('user_id', $user->id)
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->count();

        return view('admin.users.edit', compact('user', 'peminjamanAktif'));
    }

    // Memperbarui data user.
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', "unique:users,email,{$user->id}"],
            'mobile'   => ['required', 'string', 'max:20'],
            'password' => ['nullable', Password::min(8)],
        ]);

        $data = [
            'name'   => $request->name,
            'email'  => $request->email,
            'mobile' => $request->mobile,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data anggota berhasil diperbarui.');
    }

    // Menghapus user jika tidak punya pinjaman aktif.
    public function destroy(User $user)
    {
        $aktif = Peminjaman::where('user_id', $user->id)
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->count();

        if ($aktif > 0) {
            return back()->with('error', 'Anggota masih memiliki pinjaman aktif dan tidak dapat dihapus.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Anggota berhasil dihapus.');
    }
}
