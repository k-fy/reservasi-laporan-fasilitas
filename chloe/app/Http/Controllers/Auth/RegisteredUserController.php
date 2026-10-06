<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\FormRules;
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
        // Rapikan input sebelum divalidasi (email huruf kecil, hapus spasi di NIM/NIP)
        $request->merge([
            'email'   => strtolower(trim((string) $request->email)),
            'nim_nip' => preg_replace('/\s+/', '', (string) $request->nim_nip),
        ]);

        $request->validate([
            'name'     => FormRules::name(),
            'nim_nip'  => array_merge(FormRules::nimNip(), ['unique:users,nim_nip']),
            'email'    => FormRules::campusEmail(),
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], FormRules::messages() + [
            'nim_nip.unique' => 'NIM/NIP ini sudah terdaftar.',
        ]);

        // Registrasi mandiri hanya untuk pengguna.
        // Akun berstatus "pending" dan baru bisa login setelah diverifikasi Admin.
        $user = User::create([
            'name'     => trim($request->name),
            'nim_nip'  => $request->nim_nip,
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