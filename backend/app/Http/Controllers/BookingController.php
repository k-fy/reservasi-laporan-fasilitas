<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Facility;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
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

        return view('booking.index', compact('facilities'));
    }
    
    public function show(Facility $facility, Request $request)
    {
        $date = $request->get('date', now()->toDateString());

        $booked = Reservation::where('facility_id', $facility->id)
            ->where('reservation_date', $date)
            ->whereIn('status', ['approved'])
            ->get(['start_time', 'end_time']);

        $amenitiesList = $facility->amenities 
            ? array_map('trim', explode(',', $facility->amenities)) 
            : [];

        return view('booking.venue-details', [
            'facility'      => $facility,
            'amenitiesList' => $amenitiesList,
            'bookedSlots'   => $booked,
        ]);
    }

    public function store(StoreReservationRequest $request)
    {
        Reservation::create($request->validated() + [
            'user_id' => auth()->id(),
            'facility_id'      => $request->facility_id,
            'reservation_date' => $request->reservation_date,
            'start_time'       => $request->start_time,
            'end_time'         => $request->end_time,
            'purpose'          => $request->purpose,
            'status'  => 'pending',
        ]);

        return redirect()->route('booking.reserve', [
            'facility' => $request->facility_id,
        ])->with('submitted', true)
        ->with('date', $request->reservation_date)
        ->with('start_time', $request->start_time)
        ->with('end_time', $request->end_time);
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

    public function cancel(Reservation $reservation)
    {
        abort_unless($reservation->user_id === auth()->id(), 403);
        abort_if($reservation->status !== 'pending', 400, 'Accepted reservation cannot be cancelled.');

        $reservation->update(['status' => 'cancelled']);

        return back()->with('success', 'Reservation cancelled.');
    }

    public function getBookedSlots(Facility $facility, Request $request)
    {
        $date = $request->get('date', now()->toDateString());

        $bookedSlots = Reservation::where('facility_id', $facility->id)
            ->where('reservation_date', $date)
            ->whereIn('status', 'approved')
            ->get(['start_time', 'end_time']);

        return response()->json($bookedSlots);
    }

    public function reserve(Facility $facility, Request $request)
    {
        abort_if(!Auth::check(), 403, 'You must be logged in to make a reservation.');

        $date       = $request->get('date', now()->toDateString());
        $start_time = $request->get('start_time');
        $end_time   = $request->get('end_time');

        return view('booking.reserve', compact('facility', 'date', 'start_time', 'end_time'));
    }

}
