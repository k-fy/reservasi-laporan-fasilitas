<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * /dashboard — Home publik (pengunjung & pengguna).
     * Petugas & admin dibelokkan ke panel mereka masing-masing.
     */
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('dashboard.admin');
            }
            if (Auth::user()->role === 'petugas') {
                return redirect()->route('dashboard.petugas');
            }
        }

        // Pengunjung (belum login) & pengguna → tampilkan Home
        return view('dashboard.index');
    }

    public function petugas()
    {
        return view('dashboard.petugas');
    }

    public function admin()
    {
        return view('dashboard.admin');
    }
}