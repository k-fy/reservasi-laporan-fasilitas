<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // 1. Belum login → lempar ke halaman login
        if (! $user) {
            return redirect()->route('login');
        }

        // 2. Jika akun ditangguhkan → logout & kembalikan ke login
        if (! $user->isActive()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Akun kamu sedang ditangguhkan. Silakan hubungi Admin.',
            ]);
        }

        // 3. Login tapi role tidak sesuai → tolak (403)
        if (! $user->hasRole(...$roles)) {
            abort(403, 'Kamu tidak punya akses ke halaman ini.');
        }

        return $next($request);
    }
}