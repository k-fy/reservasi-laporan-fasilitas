<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Belum login → lempar ke halaman login
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Jika akun ditangguhkan / nonaktif → logout & kembalikan ke login
        if (strtolower(Auth::user()->status ?? 'active') !== 'active') {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda sedang ditangguhkan. Silakan hubungi Admin.',
            ]);
        }

        // 3. Login tapi role tidak sesuai → tolak (403)
        if (strtolower(Auth::user()->role ?? '') !== strtolower($role)) {
            abort(403, 'Anda tidak punya akses ke halaman ini.');
        }

        return $next($request);
    }
}