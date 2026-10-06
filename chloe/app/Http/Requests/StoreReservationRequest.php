<?php

namespace App\Http\Requests;

use App\Models\Reservation;
use App\Support\FormRules;
use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Rapikan input sebelum divalidasi: hapus spasi / tanda hubung pada NIM/NIP dan nomor WhatsApp.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'nim_nip'        => preg_replace('/\D/', '', (string) $this->nim_nip),
            'whatsapp'       => preg_replace('/\D/', '', (string) $this->whatsapp),
            'requester_name' => trim((string) $this->requester_name),
        ]);
    }

    public function rules(): array
    {
        return [
            'facility_id'      => 'required|exists:facilities,id',
            'reservation_date' => 'required|date|after_or_equal:' . now()->addDays(2)->toDateString(),
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'purpose'          => 'required|string|min:10|max:255',
            'requester_name'   => FormRules::name(),
            'nim_nip'          => FormRules::nimNip(),
            'whatsapp'         => FormRules::phone(),
        ];
    }

    public function messages(): array
    {
        return FormRules::messages() + [
            'reservation_date.after_or_equal' => 'Reservasi minimal H-2 sebelum tanggal pemakaian.',
            'end_time.after'                  => 'Jam selesai harus setelah jam mulai.',
            'purpose.required'                => 'Tujuan penggunaan wajib diisi.',
            'purpose.min'                     => 'Tujuan penggunaan minimal 10 karakter.',
            'purpose.max'                     => 'Tujuan penggunaan maksimal 255 karakter.',
            'requester_name.required'         => 'Nama pemohon wajib diisi.',
            'whatsapp.required'               => 'Nomor WhatsApp wajib diisi.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $start = $this->start_time;
            $end   = $this->end_time;

            if ($start < '07:00' || $end > '20:00') {
                $validator->errors()->add('start_time', 'Waktu harus berada dalam jam operasional 07.00–20.00.');
            }

            foreach (['start_time', 'end_time'] as $field) {
                $minute = (int) date('i', strtotime($this->$field));
                if (!in_array($minute, [0, 30])) {
                    $validator->errors()->add($field, 'Jam harus kelipatan 30 menit.');
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
                $validator->errors()->add('start_time', 'Jadwal ini sudah dipesan. Silakan pilih jam lain.');
            }
        });
    }
}