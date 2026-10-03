@extends('layouts.app')
@section('title', 'My Report History')
@section('content')

<div class="min-h-screen bg-[#FCF1F0] px-8 py-10">
    <div class="max-w-3xl mx-auto">

        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-[#4b4848] font-['Poppins',sans-serif]">Report History</h1>
            <a href="{{ route('profile.show') }}" class="text-[#814C5B] font-semibold hover:underline font-['Poppins',sans-serif]">Back to Profile</a>
        </div>

        <div class="space-y-6">
            @forelse($reports as $report)
                @php
                    $rs = strtolower($report->status ?? 'new');
                    $isNew      = in_array($rs, ['new', 'baru', 'pending']);
                    $isProgress = in_array($rs, ['progress', 'diproses']);
                    $isResolved = in_array($rs, ['resolved', 'selesai']);
                    $isRejected = in_array($rs, ['rejected', 'ditolak']);
                @endphp

                {{-- Card Report Details --}}
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-[#EDD3D6]">
                    {{-- Card Header --}}
                    <div class="bg-[#c9a0a8] px-6 py-4 flex justify-between items-center">
                        <h2 class="font-bold text-[#4b4848] text-lg font-['Poppins',sans-serif]">Report Details</h2>
                        <span class="px-3 py-1 rounded-full text-xs font-bold font-['Poppins',sans-serif] shadow-sm
                            @if($isNew) bg-[#FFF4D2] text-[#8C6D1F]
                            @elseif($isProgress) bg-blue-100 text-blue-700
                            @elseif($isResolved) bg-green-100 text-green-700
                            @elseif($isRejected) bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ ucfirst($report->status ?? 'New') }}
                        </span>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-6">
                        <div class="mb-5">
                            <p class="text-sm text-[#9b7d84] mb-1 font-['Poppins',sans-serif]">Reported Facility</p>
                            <p class="font-bold text-lg text-[#4b4848] font-['Poppins',sans-serif]">
                                {{ $report->facility->name ?? 'Nama Fasilitas (Atribut)' }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <p class="text-sm text-[#9b7d84] mb-1 font-['Poppins',sans-serif]">Date Reported</p>
                                <p class="font-bold text-[#4b4848] font-['Poppins',sans-serif]">
                                    {{ \Carbon\Carbon::parse($report->created_at)->format('d M Y') }}
                                </p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <p class="text-sm text-[#9b7d84] mb-2 font-['Poppins',sans-serif]">Issue Description</p>
                            <div class="bg-[#FCF1F0] rounded-xl p-4 text-sm text-[#5a5858] font-['Poppins',sans-serif] leading-relaxed">
                                {{ $report->description ?? 'Deskripsi masalah dari atribut database kamu...' }}
                            </div>
                        </div>

                        {{-- Catatan dari petugas (saat ditolak atau selesai) --}}
                        @if(($isRejected || $isResolved) && $report->resolution_notes)
                            <div class="mb-2">
                                <p class="text-sm text-[#9b7d84] mb-2 font-['Poppins',sans-serif]">Catatan Petugas</p>
                                <div class="rounded-xl p-4 text-sm font-['Poppins',sans-serif] leading-relaxed
                                    {{ $isRejected ? 'bg-red-50 text-red-700 border border-red-100' : 'bg-green-50 text-green-700 border border-green-100' }}">
                                    {{ $report->resolution_notes }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-10 bg-white rounded-2xl border border-[#EDD3D6]">
                    <p class="text-[#9b7d84] font-['Poppins',sans-serif]">Belum ada riwayat laporan.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>

@endsection