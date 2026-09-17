<div class="grid grid-cols-1 md:grid-cols-12 gap-10 items-start mb-12 text-[#FCF1F0]">
    
    <!-- Title, Description & Phone -->
    <div class="md:col-span-7 space-y-4">
        <h1 class="text-3xl md:text-4xl font-bold tracking-tight">
            {{ $venue->name ?? $facility->name }}
        </h1>

        <p class="text-sm md:text-base leading-relaxed text-[#FCF1F0]/90">
            <strong class="font-bold">{{ $venue->name ?? $facility->name }}</strong> {{ $venue->description ?? $facility->description }}
        </p>

        <div class="space-y-1 pt-2 text-sm md:text-base text-[#FCF1F0]/95 font-normal">
            <p><span class="font-semibold">Kapasitas:</span> {{ $facility->capacity ?? $venue->capacity }} {{ ($facility->type ?? '') === 'alat' ? 'Pcs' : 'Pax' }}</p>
            <p><span class="font-semibold">Lokasi:</span> {{ $facility->location ?? $venue->location }}</p>
            <p><span class="font-semibold">Luas Area:</span> {{ $facility->area ?? '25 x 15 Meter' }}</p>
            <p><span class="font-semibold">Jam Operasional:</span> 08.00 – 21.00 WIB</p>
            <p><span class="font-semibold">Aksesibilitas:</span> Ramah Kursi Roda</p>
            <p><span class="font-semibold">Status:</span> Ber-AC & Bebas Asap Rokok</p>
        </div>

        @if(!empty($facility->contact_phone ?? $venue->contact_phone))
            <div class="pt-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full border-2 border-[#FCF1F0] flex items-center justify-center text-lg">
                    📞
                </div>
                <a href="tel:{{ $facility->contact_phone ?? $venue->contact_phone }}" class="text-xl font-bold tracking-wider hover:underline">
                    {{ $facility->contact_phone ?? $venue->contact_phone }}
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
            $amenities = !empty($facility->amenities) 
                ? explode(',', $facility->amenities) 
                : ($amenitiesList ?? []);
        @endphp

        @if(count($amenities) > 0)
            <ul class="space-y-2.5 text-sm md:text-base text-[#FCF1F0]">
                @foreach($amenities as $item)
                    <li class="flex items-center gap-2">
                        <span class="text-xs">●</span>
                        <span>{{ trim($item) }}</span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-sm text-[#FCF1F0]/60 italic">Belum ada data fasilitas.</p>
        @endif
    </div>

</div>