<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Report;
use App\Models\Reservation;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    /* ---------------- Reservations ---------------- */

    public function reservations(Request $request)
    {
        $status = $request->query('status', 'pending');

        $reservations = Reservation::with('facility', 'user')
            ->when($status === 'rejected',
                fn ($q) => $q->whereIn('status', ['rejected', 'cancelled']),
                fn ($q) => $q->where('status', $status)
            )
            ->when($request->filled('facility_id'), fn ($q) =>
                $q->where('facility_id', $request->facility_id))
            ->when($request->filled('date'), fn ($q) =>
                $q->whereDate('reservation_date', $request->date))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = $request->q;
                $q->where(function ($qq) use ($term) {
                    $qq->where('purpose', 'like', "%{$term}%")
                       ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$term}%"))
                       ->orWhereHas('facility', fn ($f) => $f->where('name', 'like', "%{$term}%"));
                });
            })
            ->orderBy('reservation_date')
            ->get();

        $facilities = Facility::orderBy('name')->get();

        return view('operator.reservation', compact('reservations', 'status', 'facilities'));
    }

    public function approveReservation(Reservation $reservation)
    {
        $reservation->update(['status' => 'approved']);

        return back()->with('success', 'Reservasi berhasil disetujui.');
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

        return redirect()->route('petugas.reservations', ['status' => 'rejected'])
            ->with('success', 'Reservasi berhasil ditolak.');
    }

    public function cancelReservation(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'cancel_reason' => 'required|string|max:1000',
        ]);

        $reservation->update([
            'status'        => 'cancelled',
            'cancel_reason' => $validated['cancel_reason'],
        ]);

        return redirect()->route('petugas.reservations', ['status' => 'rejected'])
            ->with('success', 'Reservasi berhasil dibatalkan.');
    }

    /* ---------------- Reports ---------------- */

    public function reports(Request $request)
    {
        $status = $request->query('status', 'baru');

        $reports = Report::with('facility', 'user')
            ->where('status', $status)
            ->when($request->filled('facility_id'), fn ($q) =>
                $q->where('facility_id', $request->facility_id))
            ->when($request->filled('date'), fn ($q) =>
                $q->whereDate('created_at', $request->date))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = $request->q;
                $q->where(function ($qq) use ($term) {
                    $qq->where('description', 'like', "%{$term}%")
                       ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$term}%"))
                       ->orWhereHas('facility', fn ($f) => $f->where('name', 'like', "%{$term}%"));
                });
            })
            ->latest()
            ->get();

        $facilities = Facility::orderBy('name')->get();

        return view('operator.reports', compact('reports', 'status', 'facilities'));
    }

    public function startReport(Report $report)
    {
        $report->update(['status' => 'diproses']);

        return back()->with('success', 'Laporan dipindah ke In Progress.');
    }

    public function resolveReport(Request $request, Report $report)
    {
        $validated = $request->validate([
            'resolution_notes' => 'required|string|max:1000',
        ]);

        $report->update([
            'status'           => 'selesai',
            'resolution_notes' => $validated['resolution_notes'],
            'handled_by'       => auth()->id(),
        ]);

        return redirect()->route('petugas.reports', ['status' => 'selesai'])
            ->with('success', 'Laporan berhasil ditandai selesai.');
    }

    /* ---------------- Facility Status ---------------- */

    public function facilityStatus(Request $request)
    {
        $facilities = Facility::withCount(['reports as open_reports_count' => function ($q) {
                $q->where('status', '!=', 'selesai');
            }])
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->type))
            ->orderBy('name')
            ->get();

        return view('operator.facility-status', compact('facilities'));
    }

    public function setFacilityStatus(Request $request, Facility $facility)
    {
        $validated = $request->validate([
            'status' => 'required|in:available,inuse,repair',
        ]);

        $facility->update(['status' => $validated['status']]);

        return back()->with('success', $facility->name.' diubah ke '.$validated['status'].'.');
    }
}