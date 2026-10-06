<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Report;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Cookie preferensi tampilan: nama cookie => [nilai yang diizinkan, nilai default]
     */
    public const PREFERENCE_COOKIES = [
        'chloe_text_size'       => [['small', 'normal', 'large'], 'normal'],
        'chloe_animations'      => [['on', 'off'], 'on'],
        'chloe_remember_search' => [['on', 'off'], 'on'],
    ];

    /** Cookie preferensi berlaku 1 tahun (dalam menit) */
    private const PREFERENCE_LIFETIME = 60 * 24 * 365;

    /**
     * Ambil preferensi dari cookie; nilai tidak valid diganti default.
     * Dipakai juga oleh layouts.app untuk menerapkan preferensi di setiap halaman.
     */
    public static function preferences(?Request $request = null): array
    {
        $request ??= request();
        $prefs = [];

        foreach (self::PREFERENCE_COOKIES as $name => [$allowed, $default]) {
            $value = $request->cookie($name);
            $prefs[$name] = in_array($value, $allowed, true) ? $value : $default;
        }

        return $prefs;
    }

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

    /**
     * Halaman Personalization — preferensi tampilan disimpan di cookie.
     */
    public function personalization(Request $request)
    {
        return view('profile.personalization', [
            'user'  => Auth::user(),
            'prefs' => self::preferences($request),
        ]);
    }

    /**
     * Simpan preferensi tampilan ke cookie (berlaku 1 tahun, terenkripsi otomatis oleh Laravel).
     */
    public function updatePersonalization(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'text_size'       => 'required|in:small,normal,large',
            'animations'      => 'required|in:on,off',
            'remember_search' => 'required|in:on,off',
        ]);

        Cookie::queue('chloe_text_size', $validated['text_size'], self::PREFERENCE_LIFETIME);
        Cookie::queue('chloe_animations', $validated['animations'], self::PREFERENCE_LIFETIME);
        Cookie::queue('chloe_remember_search', $validated['remember_search'], self::PREFERENCE_LIFETIME);

        // Jika fitur "ingat pencarian" dimatikan, hapus juga pencarian terakhir yang tersimpan
        if ($validated['remember_search'] === 'off') {
            Cookie::queue(Cookie::forget('chloe_last_search'));
        }

        return redirect()->route('profile.personalization')->with('success', 'Preferensi tampilan berhasil disimpan.');
    }

    /**
     * Kembalikan preferensi ke default dengan menghapus cookie-nya.
     */
    public function resetPersonalization(): RedirectResponse
    {
        foreach (array_keys(self::PREFERENCE_COOKIES) as $name) {
            Cookie::queue(Cookie::forget($name));
        }
        Cookie::queue(Cookie::forget('chloe_last_search'));

        return redirect()->route('profile.personalization')->with('success', 'Preferensi tampilan dikembalikan ke pengaturan awal.');
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