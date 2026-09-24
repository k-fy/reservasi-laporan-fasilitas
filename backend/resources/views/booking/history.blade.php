@extends('layouts.app')
@section('title', 'My Booking History')
@section('content')

<div class="min-h-screen bg-[#FCF1F0] px-8 py-10">
    <div class="max-w-3xl mx-auto">
        
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-[#4b4848] font-['Poppins',sans-serif]">Booking History</h1>
            <a href="{{ route('profile.show') }}" class="text-[#814C5B] font-semibold hover:underline">Back to Profile</a>
        </div>

        <div class="space-y-6">
            @forelse($reservations as $booking)
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-[#EDD3D6]">
                    <div class="bg-[#A1868A] px-6 py-4 flex justify-between items-center">
                        <h2 class="font-bold text-white text-lg font-['Poppins',sans-serif]">Reservation Details</h2>
                        <span class="px-3 py-1 rounded-full text-xs font-bold font-['Poppins',sans-serif] shadow-sm
                            @if(strtolower($booking->status) === 'approved') bg-green-100 text-green-700
                            @elseif(strtolower($booking->status) === 'pending') bg-[#FFF4D2] text-[#8C6D1F]
                            @elseif(strtolower($booking->status) === 'rejected') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ ucfirst($booking->status ?? 'Pending') }}
                        </span>
                    </div>

                    <div class="p-6">
                        <div class="mb-5">
                            <p class="text-sm text-gray-500 mb-1 font-['Poppins',sans-serif]">Reserved Facility</p>
                            <p class="font-bold text-lg text-[#4b4848] font-['Poppins',sans-serif]">
                                {{ $booking->facility->name ?? 'Nama Fasilitas' }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                            <div>
                                <p class="text-sm text-gray-500 mb-1 font-['Poppins',sans-serif]">Date</p>
                                <p class="font-bold text-[#4b4848] font-['Poppins',sans-serif]">
                                    {{ \Carbon\Carbon::parse($booking->reservation_date)->format('d M Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1 font-['Poppins',sans-serif]">Time</p>
                                <p class="font-bold text-[#4b4848] font-['Poppins',sans-serif]">
                                    {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}
                                </p>
                            </div>
                        </div>

                        @if($booking->purpose)
                        <div class="mb-6">
                            <p class="text-sm text-gray-500 mb-2 font-['Poppins',sans-serif]">Purpose</p>
                            <div class="bg-[#F8F9FA] rounded-xl p-4 text-sm text-[#5a5858] font-['Poppins',sans-serif]">
                                {{ $booking->purpose }}
                            </div>
                        </div>
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