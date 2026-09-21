<?php

namespace App\Http\Controllers;

use App\Models\Reservation; // <--- Add this line
use App\Models\Report;      // <--- Add this line for Report (line 31)
use App\Models\Facility;    // <--- Add this line for Facility (line 32)
use Illuminate\Http\Request;
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
        $pendingReservations = Reservation::where('status', 'pending')->count();
        $newReports          = Report::where('status', 'baru')->count();
        $underRepair         = Facility::where('status', 'repair')->count();

        $approvedBookings = Reservation::with('facility', 'user')
            ->where('status', 'approved')
            ->where('reservation_date', '>=', now()->toDateString())
            ->orderBy('reservation_date')
            ->limit(5)
            ->get();

        $facilitySnapshot = Facility::orderBy('name')->limit(6)->get();

        return view('dashboard.petugas', compact(
            'pendingReservations', 'newReports', 'underRepair',
            'approvedBookings', 'facilitySnapshot'
        ));
    }

    public function admin()
    {
        return view('dashboard.admin');
    }
}