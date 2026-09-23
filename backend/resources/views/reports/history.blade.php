@extends('layouts.app')
@section('title', 'My Report History')
@section('content')

<div class="min-h-screen bg-[#FCF1F0] px-8 py-10">
    <div class="max-w-3xl mx-auto">
        
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-[#4b4848] font-['Poppins',sans-serif]">Report History</h1>
            <a href="{{ route('profile.show') }}" class="text-[#814C5B] font-semibold hover:underline">Back to Profile</a>
        </div>

        <div class="space-y-6">
            @forelse($reports as $report)
                {{-- Card Report Details --}}
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-[#EDD3D6]">
                    {{-- Card Header --}}
                    <div class="bg-[#A1868A] px-6 py-4 flex justify-between items-center">
                        <h2 class="font-bold text-white text-lg font-['Poppins',sans-serif]">Report Details</h2>
                        <span class="px-3 py-1 rounded-full text-xs font-bold font-['Poppins',sans-serif] shadow-sm
                            @if(in_array(strtolower($report->status ?? ''), ['new', 'baru', 'pending'])) bg-[#FFF4D2] text-[#8C6D1F]
                            @elseif(in_array(strtolower($report->status ?? ''), ['progress', 'diproses'])) bg-blue-100 text-blue-700
                            @elseif(in_array(strtolower($report->status ?? ''), ['resolved', 'selesai'])) bg-green-100 text-green-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ ucfirst($report->status ?? 'New') }}
                        </span>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-6">
                        <div class="mb-5">
                            <p class="text-sm text-gray-500 mb-1 font-['Poppins',sans-serif]">Reported Facility</p>
                            <p class="font-bold text-lg text-[#4b4848] font-['Poppins',sans-serif]">
                                {{ $report->facility->name ?? 'Nama Fasilitas (Atribut)' }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <p class="text-sm text-gray-500 mb-1 font-['Poppins',sans-serif]">Date Reported</p>
                                <p class="font-bold text-[#4b4848] font-['Poppins',sans-serif]">
                                    {{ \Carbon\Carbon::parse($report->created_at)->format('d M Y') }}
                                </p>
                            </div>
                        </div>

                        <div class="mb-6">
                            <p class="text-sm text-gray-500 mb-2 font-['Poppins',sans-serif]">Issue Description</p>
                            <div class="bg-[#F8F9FA] rounded-xl p-4 text-sm text-[#5a5858] font-['Poppins',sans-serif] leading-relaxed">
                                {{ $report->description ?? 'Deskripsi masalah dari atribut database kamu...' }}
                            </div>
                        </div>
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