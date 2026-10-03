<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        // Cek email & password + status akun (pending / rejected / suspended ditolak di LoginRequest)
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        // Admin & petugas selalu ke panel masing-masing.
        // Halaman tujuan yang "diingat" sebelum login (mis. /reports dari tombol Report)
        // diabaikan, karena halaman pengguna tidak boleh dibuka oleh admin/petugas.
        if ($user->isAdmin()) {
            $request->session()->forget('url.intended');
            return redirect()->route('admin.accounts');
        }

        if ($user->isPetugas()) {
            $request->session()->forget('url.intended');
            return redirect()->route('dashboard.petugas');
        }

        // Pengguna: kembali ke halaman tujuan sebelum login (mis. Report / Booking), atau ke beranda
        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}