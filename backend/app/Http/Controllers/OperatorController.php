<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Reservation;
use App\Models\Facility;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    /**********RESERVATION***********/
    public function reservations(Request $request)
    {
        $status = $request->query('status', 'pending');

        $reservations = Reservation::with('facility', 'user')
            ->when($request->filled('facility_id'), fn ($q) =>
                $q->where('facility_id', $request->facility_id))
            ->when($request->filled('date'), fn ($q) =>
                $q->whereDate('reservation_date', $request->date))
            ->where('status', $status)
            ->latest('reservation_date')
            ->get();

        $facilities = Facility::orderBy('name')->get();

        return view('operator.reservation', compact('reservations', 'status', 'facilities'));
    }

    public function approveReservation(Reservation $reservation)
    {
        $reservation->update(['status' => 'approved']);

        return redirect()
            ->route('petugas.reservations')
            ->with('success', 'Reservasi berhasil disetujui.');
    }

    public function rejectReservation(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'cancel_reason' => 'required|string|max:1000',
        ]);

        $reservation->update([
            'status'        => 'rejected',
            'cancel_reason' => $validated['cancel_reason'],
        ]);

        return redirect()
            ->route('petugas.reservations')
            ->with('success', 'Reservasi berhasil ditolak.');
    }

    /**********REPORTS***********/
    public function reports(Request $request)
    {
        // 1. Ambil status dari query string URL (?status=...), default 'baru'
        $status = $request->query('status', 'baru');

        // 2. Ambil data laporan berdasarkan status
        $reports = Report::where('status', $status)
            ->latest()
            ->get();

        // 3. Kirim variabel $status dan $reports ke view
        return view('operator.reports', compact('reports', 'status'));
    }
}