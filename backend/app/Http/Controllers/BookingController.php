<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Facility;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    /** Status reservasi yang mengunci slot (tidak boleh ada reservasi lain di jam yang sama) */
    private const BLOCKING_STATUSES = ['approved'];

    /** Reservasi yang masih bisa dibatalkan pengguna, dan batas waktunya (jam sebelum mulai) */
    private const CANCELLABLE_STATUSES = ['pending', 'approved'];
    private const CANCEL_DEADLINE_HOURS = 24;

    public function index(Request $request)
    {
        // Validasi input pencarian jika user mengisi form Availability Check di Dashboard
        if ($request->filled('start_time') || $request->filled('end_time')) {
            $request->validate([
                'start_time' => [
                    'nullable',
                    'date_format:H:i',
                    'after_or_equal:07:00',
                    'before:20:00',
                    'regex:/^([0-1][0-9]|20):(00|30)$/' // Wajib kelipatan 30 menit (00 atau 30)
                ],
                'end_time' => [
                    'nullable',
                    'date_format:H:i',
                    'after:start_time',
                    'before_or_equal:20:00',
                    'regex:/^([0-1][0-9]|20):(00|30)$/' // Wajib kelipatan 30 menit (00 atau 30)
                ],
            ], [
                'end_time.after' => 'Waktu selesai harus lebih besar dari waktu mulai.',
                'start_time.regex' => 'Waktu pencarian mulai harus kelipatan 30 menit (contoh: 07:00).',
                'end_time.regex' => 'Waktu pencarian selesai harus kelipatan 30 menit.',
            ]);
        }

        $query = Facility::where('status', 'active');

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('description', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $facilities = $query->paginate(9)->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'html'         => view('booking.partials.facility-cards', compact('facilities'))->render(),
                'pagination'   => $facilities->hasPages() ? (string) $facilities->links() : '',
                'nothing_else' => $facilities->isNotEmpty() && $facilities->onLastPage(),
            ]);
        }

        return view('booking.index', compact('facilities'));
    }

    public function show(Facility $facility, Request $request)
    {
        $date = $request->get('date', now()->toDateString());

        $booked = Reservation::where('facility_id', $facility->id)
            ->whereDate('reservation_date', $date)
            ->whereIn('status', self::BLOCKING_STATUSES)
            ->get(['start_time', 'end_time']);

        $amenitiesList = $facility->amenities
            ? array_map('trim', explode(',', $facility->amenities))
            : [];

        return view('booking.venue-details', [
            'facility'      => $facility,
            'amenitiesList' => $amenitiesList,
            // 'booked' dipakai oleh view venue-details (scheduler); 'bookedSlots' dipertahankan untuk kompatibilitas
            'booked'        => $booked,
            'bookedSlots'   => $booked,
        ]);
    }

    public function store(StoreReservationRequest $request)
    {
        // Validasi aturan reservasi di sisi server (jam operasional, slot 30 menit, H-2, bentrok)
        [$facility, $date, $start, $end] = $this->validateReservationRules($request);

        Reservation::create($request->validated() + [
            'user_id'          => auth()->id(),
            'facility_id'      => $facility->id,
            'reservation_date' => $date,
            'start_time'       => $start,
            'end_time'         => $end,
            'purpose'          => $request->purpose,
            'status'           => 'pending',
        ]);

        return redirect()->route('booking.reserve', [
            'facility' => $facility->id,
        ])->with('submitted', true)
        ->with('date', $date)
        ->with('start_time', $start)
        ->with('end_time', $end);
    }

    /**
     * Aturan bisnis reservasi yang WAJIB dicek di server:
     * 1. Fasilitas ada dan berstatus aktif
     * 2. Tanggal minimal H-2
     * 3. Jam dalam jam operasional 07.00–20.00 dan kelipatan slot 30 menit
     * 4. Jam selesai setelah jam mulai
     * 5. Tidak bentrok dengan reservasi yang sudah disetujui
     * 6. Pengguna belum punya pengajuan aktif di jam yang sama
     *
     * @return array{0: Facility, 1: string, 2: string, 3: string}
     */
    private function validateReservationRules(Request $request): array
    {
        $errors = [];

        // ---------- 1. Fasilitas ----------
        $facility = Facility::find($request->facility_id);

        if (! $facility) {
            throw ValidationException::withMessages(['facility_id' => 'Fasilitas tidak ditemukan.']);
        }
        if ($facility->status !== 'active') {
            throw ValidationException::withMessages([
                'facility_id' => $facility->status === 'maintenance'
                    ? 'Fasilitas sedang dalam perbaikan dan belum bisa dipesan.'
                    : 'Fasilitas sedang tidak aktif dan tidak bisa dipesan.',
            ]);
        }

        // ---------- 2. Tanggal (minimal H-2) ----------
        try {
            $dateObj = Carbon::createFromFormat('Y-m-d', (string) $request->reservation_date)->startOfDay();
        } catch (\Throwable $e) {
            throw ValidationException::withMessages(['reservation_date' => 'Format tanggal reservasi tidak valid.']);
        }

        $earliest = today()->addDays(DashboardController::MIN_BOOKING_DAYS_AHEAD);
        if ($dateObj->lt($earliest)) {
            $errors['reservation_date'] = sprintf(
                'Reservasi minimal H-%d. Untuk saat ini, tanggal paling cepat adalah %s.',
                DashboardController::MIN_BOOKING_DAYS_AHEAD,
                $earliest->locale('id')->translatedFormat('d F Y')
            );
        }

        // ---------- 3 & 4. Jam operasional, slot 30 menit, urutan jam ----------
        // Terima "09:00" maupun "09:00:00", lalu disimpan dalam format H:i
        $start = substr((string) $request->start_time, 0, 5);
        $end   = substr((string) $request->end_time, 0, 5);
        $timePattern = '/^([01]\d|2[0-3]):[0-5]\d$/';

        if (! preg_match($timePattern, $start) || ! preg_match($timePattern, $end)) {
            $errors['start_time'] = 'Format jam reservasi tidak valid.';
        } else {
            foreach (['start_time' => [$start, 'mulai'], 'end_time' => [$end, 'selesai']] as $field => [$time, $label]) {
                if ((int) substr($time, 3, 2) % DashboardController::SLOT_MINUTES !== 0) {
                    $errors[$field] = "Jam {$label} harus kelipatan 30 menit (contoh: 09:00 atau 09:30).";
                }
            }

            if ($start < DashboardController::OPEN_TIME || $end > DashboardController::CLOSE_TIME) {
                $errors['start_time'] = 'Reservasi harus berada dalam jam operasional 07.00–20.00.';
            }

            if ($end <= $start) {
                $errors['end_time'] = 'Jam selesai harus setelah jam mulai.';
            }
        }

        if ($errors) {
            throw ValidationException::withMessages($errors);
        }

        $date = $dateObj->toDateString();

        // ---------- 5. Bentrok dengan reservasi yang sudah disetujui ----------
        // Beririsan jika: mulai_lain < selesai_baru DAN selesai_lain > mulai_baru
        $clash = Reservation::where('facility_id', $facility->id)
            ->whereDate('reservation_date', $date)
            ->whereIn('status', self::BLOCKING_STATUSES)
            ->whereTime('start_time', '<', $end)
            ->whereTime('end_time', '>', $start)
            ->exists();

        if ($clash) {
            throw ValidationException::withMessages([
                'start_time' => 'Jadwal ini sudah dipesan. Silakan pilih jam atau tanggal lain.',
            ]);
        }

        // ---------- 6. Pengajuan ganda oleh pengguna yang sama ----------
        $duplicate = Reservation::where('facility_id', $facility->id)
            ->where('user_id', auth()->id())
            ->whereDate('reservation_date', $date)
            ->where('status', 'pending')
            ->whereTime('start_time', '<', $end)
            ->whereTime('end_time', '>', $start)
            ->exists();

        if ($duplicate) {
            throw ValidationException::withMessages([
                'start_time' => 'Kamu sudah mengajukan reservasi untuk fasilitas ini di jam yang sama dan masih menunggu persetujuan.',
            ]);
        }

        return [$facility, $date, $start, $end];
    }

    // public function success()
    // {
    //     return view('booking.success');
    // }

    public function history()
    {
        $reservations = Reservation::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('booking.history', compact('reservations'));
    }

    /**
     * Pengguna membatalkan reservasinya sendiri sebelum batas waktu
     * (paling lambat 24 jam sebelum jadwal dimulai).
     */
    public function cancel(Request $request, Reservation $reservation)
    {
        abort_unless($reservation->user_id === auth()->id(), 403);

        $data = $request->validate([
            'cancel_reason' => ['required', 'string', 'min:5', 'max:300'],
        ], [
            'cancel_reason.required' => 'Alasan pembatalan wajib diisi.',
            'cancel_reason.min'      => 'Alasan pembatalan minimal 5 karakter.',
            'cancel_reason.max'      => 'Alasan pembatalan maksimal 300 karakter.',
        ]);

        if (! in_array($reservation->status, self::CANCELLABLE_STATUSES, true)) {
            return back()->withErrors(['cancel' => 'Reservasi ini sudah tidak bisa dibatalkan.']);
        }

        $startsAt = Carbon::parse(
            Carbon::parse($reservation->reservation_date)->toDateString() . ' ' . $reservation->start_time
        );

        if (now()->diffInHours($startsAt, false) < self::CANCEL_DEADLINE_HOURS) {
            return back()->withErrors([
                'cancel' => 'Reservasi hanya bisa dibatalkan paling lambat 24 jam sebelum jadwal dimulai.',
            ]);
        }

        $reservation->update([
            'status'        => 'cancelled',
            'cancel_reason' => trim($data['cancel_reason']),
        ]);

        return back()->with('success', 'Reservasi berhasil dibatalkan.');
    }

    public function getBookedSlots(Facility $facility, Request $request)
    {
        $date = $request->get('date', now()->toDateString());

        $bookedSlots = Reservation::where('facility_id', $facility->id)
            ->whereDate('reservation_date', $date)
            ->whereIn('status', self::BLOCKING_STATUSES)
            ->get(['start_time', 'end_time']);

        return response()->json($bookedSlots);
    }

    public function reserve(Facility $facility, Request $request)
    {
        abort_if(!Auth::check(), 403, 'You must be logged in to make a reservation.');

        $date       = $request->get('date', session('date', now()->toDateString()));
        $start_time = $request->get('start_time', session('start_time'));
        $end_time   = $request->get('end_time', session('end_time'));

        return view('booking.reserve', compact('facility', 'date', 'start_time', 'end_time'));
    }

}