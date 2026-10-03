<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Report;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Halaman profil (riwayat reservasi & laporan) — khusus pengguna.
     * Admin & petugas tidak punya halaman ini, jadi langsung diarahkan ke Edit Account.
     */
    public function show(): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user->role !== User::ROLE_PENGGUNA) {
            return redirect()->route('profile.edit');
        }

        $reservations = Reservation::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $reports = Report::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('profile.show', compact('user', 'reservations', 'reports'));
    }

    /**
     * Form Edit Account — tampilan (header & sidebar) disesuaikan dengan role.
     */
    public function edit(Request $request): View
    {
        $user = Auth::user();

        $view = match ($user->role) {
            User::ROLE_ADMIN   => 'admin.profile',
            User::ROLE_PETUGAS => 'operator.profile',
            default            => 'profile.edit',
        };

        // Selama halaman petugas belum dibuat, pakai halaman pengguna dulu
        if (! view()->exists($view)) {
            $view = 'profile.edit';
        }

        return view($view, ['user' => $user]);
    }

    /**
     * Simpan perubahan profil.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'name'    => 'required|string|max:255',
            'nim_nip' => 'nullable|string|max:50',
            'bio'     => 'nullable|string|max:500',
            'photo'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'email'   => 'required|email|unique:users,email,' . $user->id,
        ]);

        $data = [
            'name'    => $request->name,
            'nim_nip' => $request->nim_nip,
            'bio'     => $request->bio,
            'email'   => $request->email,
        ];

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        if ($request->filled('password')) {
            $request->validate([
                'password'              => 'min:8',
                'password_confirmation' => 'required|same:password',
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Pengguna kembali ke halaman profilnya; admin & petugas tetap di halaman Edit Account
        $route = $user->role === User::ROLE_PENGGUNA ? 'profile.show' : 'profile.edit';

        return redirect()->route($route)->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validate(['password' => 'required']);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password salah.']);
        }

        Auth::logout();

        // Hapus data terkait dulu sebelum hapus user
        $user->reservations()->delete();
        $user->reports()->delete();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Successfully deleted your account. All your reservations and reports have been removed.');
    }

    public function personalization()
    {
        return view('profile.personalization', ['user' => Auth::user()]);
    }

    public function textSettings()
    {
        return view('profile.text-settings', ['user' => Auth::user()]);
    }

    public function tts()
    {
        return view('profile.tts', ['user' => Auth::user()]);
    }

    public function permissions()
    {
        return view('profile.permissions', ['user' => Auth::user()]);
    }

    public function biometrics()
    {
        return view('profile.biometrics', ['user' => Auth::user()]);
    }

    public function deleteAccount()
    {
        return view('profile.delete', ['user' => Auth::user()]);
    }
}