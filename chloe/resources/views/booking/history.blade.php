@extends('layouts.app')
@section('title', 'My Booking History')
@section('content')

<div class="min-h-screen bg-[#FCF1F0] px-8 py-10">
    <div class="max-w-3xl mx-auto">

        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-[#4b4848] font-['Poppins',sans-serif]">Booking History</h1>
            <a href="{{ route('profile.show') }}" class="text-[#814C5B] font-semibold hover:underline font-['Poppins',sans-serif]">Back to Profile</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 rounded-xl px-4 py-3 mb-6 text-sm font-['Poppins',sans-serif]">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 text-red-700 rounded-xl px-4 py-3 mb-6 text-sm font-['Poppins',sans-serif]">
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 text-red-700 rounded-xl px-4 py-3 mb-6 text-sm font-['Poppins',sans-serif]">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="space-y-6">
            @forelse($reservations as $booking)
                @php
                    $status   = strtolower($booking->status ?? 'pending');
                    $startsAt = \Carbon\Carbon::parse(
                        \Carbon\Carbon::parse($booking->reservation_date)->toDateString() . ' ' . $booking->start_time
                    );
                    $isActive  = in_array($status, ['pending', 'approved']);
                    // Aturan sama dengan BookingController@cancel: maksimal 24 jam sebelum jadwal
                    $canCancel = $isActive && now()->diffInHours($startsAt, false) >= 24;
                @endphp

                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-[#EDD3D6]"
                     x-data="{ open: false }">
                    {{-- Header --}}
                    <div class="bg-[#c9a0a8] px-6 py-4 flex justify-between items-center">
                        <h2 class="font-bold text-[#4b4848] text-lg font-['Poppins',sans-serif]">Reservation Details</h2>
                        <span class="px-3 py-1 rounded-full text-xs font-bold font-['Poppins',sans-serif] shadow-sm
                            @if($status === 'approved') bg-green-100 text-green-700
                            @elseif($status === 'pending') bg-[#FFF4D2] text-[#8C6D1F]
                            @elseif($status === 'rejected') bg-red-100 text-red-700
                            @elseif($status === 'cancelled') bg-gray-200 text-gray-600
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ ucfirst($status) }}
                        </span>
                    </div>

                    {{-- Body --}}
                    <div class="p-6">
                        <div class="mb-5">
                            <p class="text-sm text-[#9b7d84] mb-1 font-['Poppins',sans-serif]">Reserved Facility</p>
                            <p class="font-bold text-lg text-[#4b4848] font-['Poppins',sans-serif]">
                                {{ $booking->facility->name ?? 'Nama Fasilitas' }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <p class="text-sm text-[#9b7d84] mb-1 font-['Poppins',sans-serif]">Date</p>
                                <p class="font-bold text-[#4b4848] font-['Poppins',sans-serif]">
                                    {{ \Carbon\Carbon::parse($booking->reservation_date)->format('d M Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-[#9b7d84] mb-1 font-['Poppins',sans-serif]">Time</p>
                                <p class="font-bold text-[#4b4848] font-['Poppins',sans-serif]">
                                    {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}
                                </p>
                            </div>
                        </div>

                        @if($booking->purpose)
                            <div class="mb-4">
                                <p class="text-sm text-[#9b7d84] mb-2 font-['Poppins',sans-serif]">Purpose</p>
                                <div class="bg-[#FCF1F0] rounded-xl p-4 text-sm text-[#5a5858] font-['Poppins',sans-serif]">
                                    {{ $booking->purpose }}
                                </div>
                            </div>
                        @endif

                        {{-- Catatan petugas (reject) / alasan pembatalan --}}
                        @if(in_array($status, ['rejected', 'cancelled']) && $booking->cancel_reason)
                            <div class="mb-2">
                                <p class="text-sm text-[#9b7d84] mb-2 font-['Poppins',sans-serif]">
                                    {{ $status === 'rejected' ? 'Catatan Petugas' : 'Alasan Pembatalan' }}
                                </p>
                                <div class="rounded-xl p-4 text-sm font-['Poppins',sans-serif]
                                    {{ $status === 'rejected' ? 'bg-red-50 text-red-700 border border-red-100' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $booking->cancel_reason }}
                                </div>
                            </div>
                        @endif

                        {{-- Cancel --}}
                        @if($canCancel)
                            <div class="mt-6 pt-5 border-t border-[#EDD3D6]">
                                <div class="flex justify-end" x-show="!open">
                                    <button type="button" @click="open = true"
                                            class="px-5 py-2 rounded-full text-sm font-semibold font-['Poppins',sans-serif]
                                                   border border-[#814C5B] text-[#814C5B]
                                                   hover:bg-[#814C5B] hover:text-white transition">
                                        Cancel Booking
                                    </button>
                                </div>

                                <form method="POST"
                                      action="{{ route('booking.cancel', $booking->id) }}"
                                      x-show="open" x-transition style="display:none"
                                      class="font-['Poppins',sans-serif]">
                                    @csrf
                                    <label class="block text-sm font-semibold text-[#4b4848] mb-2">
                                        Alasan pembatalan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="cancel_reason" rows="3" required minlength="5" maxlength="300"
                                              placeholder="Tulis alasan kenapa reservasi ini dibatalkan"
                                              class="w-full rounded-xl border border-[#c9a0a8] px-4 py-3 text-sm text-[#4b4848] resize-none
                                                     focus:outline-none focus:ring-2 focus:ring-[#c9a0a8]"></textarea>
                                    <div class="flex justify-end gap-3 mt-3">
                                        <button type="button" @click="open = false"
                                                class="px-5 py-2 rounded-full text-sm font-semibold border border-[#c9a0a8] text-[#814C5B]
                                                       hover:bg-[#FCF1F0] transition">
                                            Back
                                        </button>
                                        <button type="submit"
                                                class="px-5 py-2 rounded-full text-sm font-semibold bg-[#814C5B] text-white
                                                       hover:bg-[#6b3e4b] transition">
                                            Confirm Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @elseif($isActive)
                            <p class="mt-6 pt-5 border-t border-[#EDD3D6] text-xs text-[#9b7d84] font-['Poppins',sans-serif]">
                                Pembatalan hanya bisa dilakukan paling lambat 24 jam sebelum jadwal dimulai.
                            </p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-10 bg-white rounded-2xl border border-[#EDD3D6]">
                    <p class="text-[#9b7d84] font-['Poppins',sans-serif]">Belum ada riwayat reservasi.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>

@endsection