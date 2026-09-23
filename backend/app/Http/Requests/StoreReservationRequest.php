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
            'requester_name'   => 'required|string|max:255',
            'nim_nip'          => 'required|string|max:50',
            'whatsapp'         => 'required|string|max:20',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $start = $this->start_time;
            $end   = $this->end_time;

            if ($start < '07:00' || $end > '20:00') {
                $validator->errors()->add('start_time', 'Time must be within operating hours 07:00–20:00.');
            }

            foreach (['start_time', 'end_time'] as $field) {
                $minute = (int) date('i', strtotime($this->$field));
                if (!in_array($minute, [0, 30])) {
                    $validator->errors()->add($field, 'Time must be in 30-minute increments.');
                }
            }

            $conflict = Reservation::where('facility_id', $this->facility_id)
                ->where('reservation_date', $this->reservation_date)
                ->whereIn('status', ['approved'])
                ->where(function ($q) use ($start, $end) {
                    $q->where('start_time', '<', $end)
                      ->where('end_time', '>', $start);
                })
                ->exists();

            if ($conflict) {
                $validator->errors()->add('start_time', 'This time slot is already booked. Please choose a different time.');
            }
        });
    }
}