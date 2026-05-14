<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Anggota;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    // Menampilkan halaman awal pilihan login.
    public function start(): View
    {
        return view('welcome');
    }

    // Menampilkan halaman login admin.
    public function createAdmin(): View
    {
        return view('auth.login-admin');
    }

    // Menampilkan halaman login anggota.
    public function createUser(): View
    {
        return view('auth.login-user');
    }

    // Fallback login email untuk kompatibilitas route /login lama.
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    // Memproses login admin menggunakan email dan password.
    public function storeAdmin(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        if (auth()->user()->role !== 'admin') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Akun ini bukan akun admin.',
            ]);
        }

        return redirect()->route('admin.dashboard');
    }

    // Memproses login anggota menggunakan NIS dan password akun.
    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nis' => ['required', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $anggota = Anggota::with('user')
            ->where('nis', $validated['nis'])
            ->first();

        if (!$anggota || !$anggota->user || $anggota->user->role !== 'anggota' || !Hash::check($validated['password'], $anggota->user->password)) {
            throw ValidationException::withMessages([
                'nis' => trans('auth.failed'),
            ]);
        }

        Auth::login($anggota->user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route('user.dashboard');
    }

    // Mengakhiri sesi login user.
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
