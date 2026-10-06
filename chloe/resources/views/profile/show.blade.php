@extends('layouts.app')
@section('title', 'Profile - Chloe')
@section('content')


<div class="min-h-screen bg-[#FCF1F0] px-8 py-10">
    <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10">


        {{-- KOLOM KIRI --}}
        <div>
            {{-- Avatar + Nama --}}
            <div class="flex items-center gap-5 mb-6">
                @include('profile.partials.sidebar')
            </div>
        </div>


        {{-- KOLOM KANAN --}}
        <div class="space-y-5">


            {{-- RESERVATION WIDGET --}}
            <div class="bg-white rounded-2xl overflow-hidden border border-[#EDD3D6] shadow-sm">
                <div class="bg-[#c9a0a8] px-5 py-3 flex justify-between items-center">
                    <span class="font-bold text-[#4b4848] text-sm font-['Poppins',sans-serif]">Reservation</span>
                    <a href="{{ route('booking.history') }}" class="text-[#4b4848] font-bold text-lg hover:text-white transition">›</a>
                </div>
                <div class="divide-y divide-[#EDD3D6]">
                    @forelse($reservations as $r)
                        <div class="flex justify-between items-center px-5 py-4">
                            <div>
                                <p class="font-semibold text-sm text-[#4b4848] font-['Poppins',sans-serif]">
                                    {{ $r->facility->name }}
                                </p>
                                <p class="text-xs text-[#9b7d84] mt-0.5 font-['Poppins',sans-serif]">
                                    {{ \Carbon\Carbon::parse($r->reservation_date)->format('d M Y') }}
                                    · {{ substr($r->start_time, 0, 5) }}–{{ substr($r->end_time, 0, 5) }}
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold font-['Poppins',sans-serif]
                                    @if($r->status === 'approved') bg-green-100 text-green-700
                                    @elseif($r->status === 'pending') bg-yellow-100 text-yellow-700
                                    @elseif($r->status === 'rejected') bg-red-100 text-red-600
                                    @else bg-gray-100 text-gray-500 @endif">
                                    {{ ucfirst($r->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center">
                            <p class="text-sm text-[#9b7d84] font-['Poppins',sans-serif]">Belum ada reservasi.</p>
                        </div>
                    @endforelse
                </div>
            </div>


            {{-- REPORT STATUS WIDGET --}}
            <div class="bg-white rounded-2xl overflow-hidden border border-[#EDD3D6] shadow-sm">
                <div class="bg-[#c9a0a8] px-5 py-3 flex justify-between items-center">
                    <span class="font-bold text-[#4b4848] text-sm font-['Poppins',sans-serif]">Report Status</span>
                    <a href="{{ route('reports.history') }}" class="text-[#4b4848] font-bold text-lg hover:text-white transition">›</a>
                </div>
                <div class="divide-y divide-[#EDD3D6]">
                    @forelse($reports as $report)
                        <div class="flex justify-between items-center px-5 py-4">
                            <div>
                                <p class="font-semibold text-sm text-[#4b4848] font-['Poppins',sans-serif]">
                                    {{ $report->facility->name ?? 'Fasilitas' }}
                                </p>
                                <p class="text-xs text-[#9b7d84] mt-0.5 font-['Poppins',sans-serif]">
                                    {{ \Carbon\Carbon::parse($report->created_at)->format('d M Y') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold font-['Poppins',sans-serif]
                                        @if(in_array(strtolower($report->status ?? ''), ['resolved', 'selesai']))
                                            bg-green-100 text-green-700
                                        @elseif(in_array(strtolower($report->status ?? ''), ['progress', 'diproses']))
                                            bg-[#814C5B] text-white
                                        @elseif(in_array(strtolower($report->status ?? ''), ['rejected', 'ditolak']))
                                            bg-red-100 text-red-700
                                        @else
                                            bg-yellow-100 text-yellow-700
                                        @endif">
                                        @if(in_array(strtolower($report->status ?? ''), ['new', 'baru', 'pending']))
                                            New
                                        @elseif(in_array(strtolower($report->status ?? ''), ['progress', 'diproses']))
                                            Progress
                                        @elseif(in_array(strtolower($report->status ?? ''), ['resolved', 'selesai']))
                                            Resolved
                                        @else
                                            {{ ucfirst($report->status) }}
                                        @endif
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center">
                            <p class="text-sm text-[#9b7d84] font-['Poppins',sans-serif]">Belum ada laporan.</p>
                        </div>
                    @endforelse
                </div>
            </div>


        </div>
    </div>
</div>


@endsection



