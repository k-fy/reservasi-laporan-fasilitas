@php
    $item      = $facility ?? $venue;
    $isTool    = ($item->type ?? '') === 'alat';
    $typeLabel = \App\Http\Controllers\AdminController::FACILITY_TYPES[$item->type ?? ''] ?? null;
    $minDays   = \App\Http\Controllers\DashboardController::MIN_BOOKING_DAYS_AHEAD;
@endphp

<div class="grid grid-cols-1 md:grid-cols-12 gap-10 items-start mb-12 text-[#FCF1F0]">

    <!-- Title, Description & Phone -->
    <div class="md:col-span-7 space-y-4">
        <div class="space-y-2">
            @if ($typeLabel)
                <span class="inline-block text-xs font-semibold uppercase tracking-wider text-[#FCF1F0]/70">{{ $typeLabel }}</span>
            @endif

            <h1 class="text-3xl md:text-4xl font-bold tracking-tight">
                {{ $item->name }}
            </h1>

            {{-- Fasilitas yang sedang diperbaiki tidak bisa dipesan --}}
            @if (($item->status ?? '') === 'maintenance')
                <span class="inline-flex items-center gap-2 bg-amber-100 text-amber-800 text-sm font-semibold px-3 py-1 rounded-full">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span> Sedang dalam perbaikan
                </span>
            @endif
        </div>

        @if ($item->description)
            <p class="text-sm md:text-base leading-relaxed text-[#FCF1F0]/90">
                <strong class="font-bold">{{ $item->name }}</strong> {{ $item->description }}
            </p>
        @endif

        <div class="space-y-1 pt-2 text-sm md:text-base text-[#FCF1F0]/95 font-normal">
            <p><span class="font-semibold">Kapasitas:</span> {{ $item->capacity }} {{ $isTool ? 'Pcs' : 'Pax' }}</p>

            @if ($item->location)
                <p><span class="font-semibold">Lokasi:</span> {{ $item->location }}</p>
            @endif

            {{-- Luas area hanya tampil jika diisi (alat tidak memiliki luas area) --}}
            @if (!empty($item->area))
                <p><span class="font-semibold">Luas Area:</span> {{ $item->area }}</p>
            @endif

            <p><span class="font-semibold">Jam Operasional:</span> 07.00 – 20.00 WIB (slot per 30 menit)</p>
        </div>

        {{-- Ketentuan peminjaman (asumsi kelompok) --}}
        <div class="flex items-start gap-3 bg-[#FCF1F0]/10 border border-[#FCF1F0]/25 rounded-2xl px-4 py-3 text-sm text-[#FCF1F0]/90">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p>Peminjaman fasilitas tidak dipungut biaya. Reservasi diajukan paling lambat H-{{ $minDays }} sebelum tanggal pemakaian dan menunggu persetujuan petugas.</p>
        </div>

        @if (!empty($item->contact_phone))
            <div class="pt-2 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full border-2 border-[#FCF1F0] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
                <a href="tel:{{ $item->contact_phone }}" class="text-xl font-bold tracking-wider hover:underline">
                    {{ $item->contact_phone }}
                </a>
            </div>
        @endif
    </div>

    <!-- Facilities Provided Box -->
    <div class="md:col-span-5 border-2 border-[#FCF1F0]/70 rounded-[32px] p-8 min-h-[320px] bg-transparent">
        <h2 class="text-2xl font-bold mb-4 text-[#FCF1F0]">
            Facilities Provided
        </h2>

        @php
            $amenities = !empty($item->amenities)
                ? array_filter(array_map('trim', explode(',', $item->amenities)))
                : ($amenitiesList ?? []);
        @endphp

        @if (count($amenities) > 0)
            <ul class="space-y-2.5 text-sm md:text-base text-[#FCF1F0]">
                @foreach ($amenities as $amenity)
                    <li class="flex items-center gap-2">
                        <span class="text-xs">●</span>
                        <span>{{ $amenity }}</span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-sm text-[#FCF1F0]/60 italic">Belum ada data fasilitas.</p>
        @endif
    </div>

</div>