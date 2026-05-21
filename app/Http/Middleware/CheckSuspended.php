<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSuspended
{
    /**
     * Handle an incoming request.
     *
     * Logs out and redirects any authenticated user whose User account
     * OR linked Anggota record is suspended.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user) {
            return $next($request);
        }

        // 1. User-level suspension
        if ($user->isSuspended()) {
            return $this->forceLogout(
                $request,
                $user->suspension_reason
            );
        }

        // 2. Anggota-level suspension (library members only)
        if (
            method_exists($user, 'isAnggota') &&
            $user->isAnggota() &&
            optional($user->anggota)->isSuspended()
        ) {
            return $this->forceLogout(
                $request,
                $user->anggota->suspension_reason
            );
        }

        return $next($request);
    }

    /**
     * Invalidate the session and redirect to home with an error flash.
     */
    private function forceLogout(Request $request, ?string $reason): Response
    {
        auth()->guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $message = 'Akun Anda telah di-suspend.';

        if ($reason) {
            $message .= ' Alasan: ' . $reason;
        } else {
            $message .= ' Hubungi administrator untuk informasi lebih lanjut.';
        }

        return redirect('/')->with('error', $message);
    }
}