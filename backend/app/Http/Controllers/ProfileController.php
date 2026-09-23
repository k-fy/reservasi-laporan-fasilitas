<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Reservation;
use Illuminate\Support\Facades\Storage;
use App\Models\Report; 

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

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
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    /**
     * Update the user's profile information.
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

            return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
        }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

}