<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || $request->user()->role !== $role) {
            if ($request->user() && $request->user()->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
            if ($request->user() && $request->user()->role === 'anggota') {
                return redirect()->route('user.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}