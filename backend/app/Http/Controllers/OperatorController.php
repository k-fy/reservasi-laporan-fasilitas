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

        // Tandai konflik jadwal untuk tiap reservasi (dipakai di view sebagai warning)
        foreach ($reservations as $r) {
            // Bentrok dengan reservasi yang SUDAH approved => slot sudah penuh
            $r->slot_occupied = Reservation::where('facility_id', $r->facility_id)
                ->where('reservation_date', $r->reservation_date)
                ->where('status', 'approved')
                ->where('id', '!=', $r->id)
                ->where('start_time', '<', $r->end_time)
                ->where('end_time', '>', $r->start_time)
                ->exists();

            // Bentrok dengan sesama pending => petugas perlu memilih salah satu
            $r->peer_conflict = Reservation::where('facility_id', $r->facility_id)
                ->where('reservation_date', $r->reservation_date)
                ->where('status', 'pending')
                ->where('id', '!=', $r->id)
                ->where('start_time', '<', $r->end_time)
                ->where('end_time', '>', $r->start_time)
                ->exists();
        }

        $facilities = Facility::orderBy('name')->get();

        return view('operator.reservation', compact('reservations', 'status', 'facilities'));
    }

    public function approveReservation(Reservation $reservation)
    {
        // Cegah approve kalau slot sudah dipegang reservasi lain yang approved
        $bentrok = Reservation::where('facility_id', $reservation->facility_id)
            ->where('reservation_date', $reservation->reservation_date)
            ->where('status', 'approved')
            ->where('id', '!=', $reservation->id)
            ->where('start_time', '<', $reservation->end_time)
            ->where('end_time', '>', $reservation->start_time)
            ->exists();

        if ($bentrok) {
            return back()->with('error', 'Slot fasilitas ini sudah penuh (disetujui untuk reservasi lain). Silakan tolak pengajuan ini.');
        }

        $reservation->update([
            'status'       => 'approved',
            'processed_by' => auth()->id(),
        ]);

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
            'processed_by'  => auth()->id(),
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

    public function rejectReport(Request $request, Report $report)
    {
        $validated = $request->validate([
            'resolution_notes' => 'required|string|max:1000',
        ]);

        $report->update([
            'status'           => 'ditolak',
            'resolution_notes' => $validated['resolution_notes'],
            'handled_by'       => auth()->id(),
        ]);

        return redirect()->route('petugas.reports', ['status' => 'ditolak'])
            ->with('success', 'Laporan ditandai ditolak.');
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
            'status' => 'required|in:active,maintenance,inactive',
        ]);

        $facility->update(['status' => $validated['status']]);

        $labels = ['active' => 'Active', 'maintenance' => 'Under repair', 'inactive' => 'Inactive'];

        return back()->with('success', $facility->name.' set to '.$labels[$validated['status']].'.');
    }
}