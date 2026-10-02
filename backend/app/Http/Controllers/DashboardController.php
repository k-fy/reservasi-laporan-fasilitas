<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Report;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /** Jam operasional & panjang slot (sesuai ketentuan tugas) */
    private const OPEN_TIME    = '07:00';
    private const CLOSE_TIME   = '20:00';
    private const SLOT_MINUTES = 30;

    /** Booking hanya boleh dilakukan paling cepat H-2 (asumsi kelompok) */
    public const MIN_BOOKING_DAYS_AHEAD = 2;

    /** Status reservasi yang dianggap "mengisi" slot */
    private const BLOCKING_STATUSES = ['approved'];
    private const WAITING_STATUSES  = ['pending'];

    /**
     * /dashboard — Home publik (pengunjung & pengguna).
     * Petugas & admin dibelokkan ke panel mereka masing-masing.
     */
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.accounts');
            }
            if (Auth::user()->isPetugas()) {
                return redirect()->route('dashboard.petugas');
            }
        }

        // Daftar fasilitas untuk saran pada kolom "Search Facility"
        // (fasilitas nonaktif tidak ditampilkan ke publik)
        $facilitySuggestions = Facility::where('status', '!=', 'inactive')
            ->orderBy('name')
            ->get(['id', 'name', 'type', 'location'])
            ->map(fn (Facility $f) => [
                'id'       => $f->id,
                'name'     => $f->name,
                'type'     => AdminController::FACILITY_TYPES[$f->type] ?? $f->type,
                'location' => $f->location,
            ])
            ->values();

        return view('dashboard.index', compact('facilitySuggestions'));
    }

    /**
     * GET /availability — cek ketersediaan fasilitas (dipanggil dari kotak Availability Check).
     * Mengembalikan JSON berisi status tiap fasilitas TANPA detail pemohon / tujuan.
     */
    public function availability(Request $request): JsonResponse
    {
        $validated = $request->validate(
            [
                'q'           => 'nullable|string|max:100',
                'facility_id' => 'nullable|integer|exists:facilities,id',
                'date'       => 'required|date_format:Y-m-d|after_or_equal:today',
                'start_time' => 'required|date_format:H:i',
                'end_time'   => 'required|date_format:H:i|after:start_time',
            ],
            [
                'date.required'          => 'Please select a date.',
                'date.after_or_equal'    => 'The date cannot be earlier than today.',
                'start_time.required'    => 'Please select a start time.',
                'end_time.required'      => 'Please select an end time.',
                'end_time.after'         => 'The end time must be later than the start time.',
                'start_time.date_format' => 'Invalid start time format.',
                'end_time.date_format'   => 'Invalid end time format.',
            ]
        );

        // Validasi tambahan di sisi server: jam operasional & kelipatan slot 30 menit
        $errors = $this->validateSlot($validated['start_time'], $validated['end_time']);
        if ($errors) {
            return response()->json(['message' => $errors[0], 'errors' => ['time' => $errors]], 422);
        }

        $date  = $validated['date'];
        $start = $validated['start_time'];
        $end   = $validated['end_time'];

        // Cek ketersediaan boleh untuk hari ini, tetapi booking minimal H-2
        $earliestBookingDate = today()->addDays(self::MIN_BOOKING_DAYS_AHEAD);
        $bookingAllowed      = Carbon::parse($date)->gte($earliestBookingDate);

        // 1. Fasilitas yang cocok dengan kata kunci (nama, tipe, atau lokasi)
        $query = Facility::where('status', '!=', 'inactive');

        if (! empty($validated['facility_id'])) {
            // Fasilitas dipilih langsung dari daftar saran
            $query->whereKey($validated['facility_id']);
        } elseif (! empty($validated['q'])) {
            $keyword = $validated['q'];

            // Cocokkan juga dengan label tipe, mis. "laboratorium" -> 'lab'
            $matchingTypes = collect(AdminController::FACILITY_TYPES)
                ->filter(fn ($label, $value) => str_contains(strtolower($label . ' ' . $value), strtolower($keyword)))
                ->keys()
                ->all();

            $query->where(function ($q) use ($keyword, $matchingTypes) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('location', 'like', "%{$keyword}%")
                  ->orWhere('type', 'like', "%{$keyword}%");

                if ($matchingTypes) {
                    $q->orWhereIn('type', $matchingTypes);
                }
            });
        }

        $facilities = $query->orderBy('name')->limit(30)->get();

        // 2. Reservasi yang beririsan dengan rentang waktu yang dipilih
        //    (beririsan jika: mulai_reservasi < selesai_dipilih DAN selesai_reservasi > mulai_dipilih)
        $overlapping = Reservation::whereIn('facility_id', $facilities->pluck('id'))
            ->whereDate('reservation_date', $date)
            ->whereIn('status', array_merge(self::BLOCKING_STATUSES, self::WAITING_STATUSES))
            ->whereTime('start_time', '<', $end)
            ->whereTime('end_time', '>', $start)
            ->get(['facility_id', 'status']);

        $bookedIds  = $overlapping->whereIn('status', self::BLOCKING_STATUSES)->pluck('facility_id')->unique();
        $waitingIds = $overlapping->whereIn('status', self::WAITING_STATUSES)->pluck('facility_id')->unique();

        // 3. Susun hasil (tanpa nama pemohon / tujuan penggunaan)
        $results = $facilities->map(function (Facility $f) use ($bookedIds, $waitingIds, $date, $start, $end, $bookingAllowed) {
            if ($f->status === 'maintenance') {
                $status = 'maintenance';
                $label  = 'This facility is under maintenance';
            } elseif ($bookedIds->contains($f->id)) {
                $status = 'booked';
                $label  = 'Already booked at this time';
            } else {
                $status = 'available';
                $label  = 'Available';
            }

            return [
                'id'          => $f->id,
                'name'        => $f->name,
                'type'        => AdminController::FACILITY_TYPES[$f->type] ?? $f->type,
                'location'    => $f->location,
                'capacity'    => $f->capacity,
                'unit'        => $f->type === 'alat' ? 'pcs' : 'people',
                'image'       => $f->image ? asset('storage/' . $f->image) : null,
                'status'      => $status,
                'label'       => $label,
                'has_waiting' => $status === 'available' && $waitingIds->contains($f->id),
                'can_book'    => $status === 'available' && $bookingAllowed,
                'url'         => route('booking.show', [
                    'facility'   => $f->id,
                    'date'       => $date,
                    'start_time' => $start,
                    'end_time'   => $end,
                ]),
            ];
        })->values();

        return response()->json([
            'date_label' => Carbon::parse($date)->locale('en')->translatedFormat('l, d F Y'),
            'start_time' => $start,
            'end_time'   => $end,
            'summary'    => [
                'total'     => $results->count(),
                'available' => $results->where('status', 'available')->count(),
            ],
            // Info aturan H-2 untuk ditampilkan di panel hasil
            'booking_allowed' => $bookingAllowed,
            'earliest_booking_label' => $earliestBookingDate->locale('en')->translatedFormat('d F Y'),
            'booking_note'    => $bookingAllowed ? null : sprintf(
                'Bookings must be made at least %d days in advance (from %s). You can still check availability.',
                self::MIN_BOOKING_DAYS_AHEAD,
                $earliestBookingDate->locale('en')->translatedFormat('d F Y')
            ),
            'results'    => $results,
        ]);
    }

    /**
     * Cek jam berada dalam jam operasional dan merupakan kelipatan slot 30 menit.
     */
    private function validateSlot(string $start, string $end): array
    {
        $errors = [];

        foreach (['start' => $start, 'end' => $end] as $label => $time) {
            $minutes = (int) substr($time, 3, 2);
            if ($minutes % self::SLOT_MINUTES !== 0) {
                $errors[] = "The {$label} time must be on a 30-minute slot (e.g. 09:00 or 09:30).";
            }
        }

        if ($start < self::OPEN_TIME || $end > self::CLOSE_TIME) {
            $errors[] = 'The time must be within operating hours (07:00–20:00).';
        }

        return $errors;
    }

    public function petugas()
    {
        $pendingReservations = Reservation::where('status', 'pending')->count();
        $newReports          = Report::where('status', Report::STATUS_NEW)->count();
        $underRepair         = Facility::where('status', 'maintenance')->count();

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

    /**
     * Admin tidak punya dashboard sendiri — selalu diarahkan ke Accounts.
     * (Route /dashboard/admin juga sudah me-redirect, method ini hanya pengaman.)
     */
    public function admin()
    {
        return redirect()->route('admin.accounts');
    }
}