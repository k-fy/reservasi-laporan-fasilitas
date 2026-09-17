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
        // Belum login → lempar ke halaman login
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        // Login tapi role tidak sesuai → tolak (403)
        if (Auth::user()->role !== $role) {
            abort(403, 'Anda tidak punya akses ke halaman ini.');
        }

        return $next($request);
    }
}