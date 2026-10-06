<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Registrasi mandiri hanya untuk pengguna.
        // Akun berstatus "pending" dan baru bisa login setelah diverifikasi Admin.
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password,
            'role'     => User::ROLE_PENGGUNA,
            'status'   => AdminController::STATUS_PENDING,
        ]);

        event(new Registered($user));

        // Tidak login otomatis: arahkan ke halaman login dengan pemberitahuan
        return redirect()->route('login')->with('status', 'Pendaftaran berhasil! Akun Anda sedang menunggu verifikasi dari Admin.');
    }
}