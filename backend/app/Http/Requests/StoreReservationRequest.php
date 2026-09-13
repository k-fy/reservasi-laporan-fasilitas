<?php

namespace App\Http\Requests;

use App\Models\Reservation;
use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'facility_id'      => 'required|exists:facilities,id',
            'reservation_date' => 'required|date|after_or_equal:today',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'purpose'          => 'required|string|max:255',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $start = $this->start_time;
            $end   = $this->end_time;

            if ($start < '07:00' || $end > '20:00') {
                $validator->errors()->add('start_time', 'Waktu harus dalam jam operasional 07:00–20:00.');
            }

            foreach (['start_time', 'end_time'] as $field) {
                $minute = (int) date('i', strtotime($this->$field));
                if (!in_array($minute, [0, 30])) {
                    $validator->errors()->add($field, 'Waktu harus kelipatan slot 30 menit.');
                }
            }

            $conflict = Reservation::where('facility_id', $this->facility_id)
                ->where('reservation_date', $this->reservation_date)
                ->whereIn('status', ['pending', 'approved'])
                ->where(function ($q) use ($start, $end) {
                    $q->where('start_time', '<', $end)
                      ->where('end_time', '>', $start);
                })
                ->exists();

            if ($conflict) {
                $validator->errors()->add('start_time', 'Slot waktu ini sudah dipesan / masih diproses untuk fasilitas ini.');
            }
        });
    }
}