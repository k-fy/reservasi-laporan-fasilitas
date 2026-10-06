@php $current = request()->routeIs('profile.*') ? request()->route()->getName() : ''; @endphp

<div class="w-64 flex-shrink-0">

    {{-- Back to Profile (muncul di semua halaman kecuali show) --}}
    @unless(request()->routeIs('profile.show'))
        <a href="{{ route('profile.show') }}"
           class="flex items-center gap-2 text-sm text-[#814C5B] hover:text-[#6b3e4b] font-semibold font-['Poppins',sans-serif] mb-5 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
            Back
        </a>
    @endunless

    {{-- Avatar --}}
    <div class="flex items-center gap-4 mb-6">
        <div class="w-16 h-16 rounded-full bg-[#814C5B] overflow-hidden flex-shrink-0">
            @if(auth()->user()->photo)
                <img src="{{ asset('storage/'.auth()->user()->photo) }}" class="w-full h-full object-cover">
            @endif
        </div>
        <div>
            <p class="font-bold text-[#4b4848] font-['Poppins',sans-serif]">{{ auth()->user()->name }}</p>
            <p class="text-xs text-[#814C5B] font-['Poppins',sans-serif]">{{ auth()->user()->bio ?? '' }}</p>
        </div>
    </div>

    <div class="border-b border-[#c9a0a8] mb-5"></div>

    @php
    $links = [
        'ACCOUNT' => [
            ['label' => 'Edit Profile',     'route' => 'profile.edit'],
            ['label' => 'Personalization',  'route' => 'profile.personalization'],
        ],
        'ACCESSIBILITY' => [
            ['label' => 'Text Settings',    'route' => 'profile.text-settings'],
            ['label' => 'Text-to-Speech',   'route' => 'profile.tts'],
        ],
        'PRIVACY' => [
            ['label' => 'Permissions',      'route' => 'profile.permissions'],
            ['label' => 'Data Biometrics',  'route' => 'profile.biometrics'],
            ['label' => 'Delete My Data',   'route' => 'profile.delete', 'bold' => true],
        ],
    ];
    @endphp

    @foreach($links as $section => $items)
        <div class="mb-5">
            <h3 class="text-[#814C5B] font-bold text-xs mb-2 font-['Poppins',sans-serif] uppercase tracking-wide">
                {{ $section }}
            </h3>
            @foreach($items as $item)
                <a href="{{ route($item['route']) }}"
                   class="block py-2 text-sm font-['Poppins',sans-serif] transition
                          {{ request()->routeIs($item['route']) 
                              ? 'text-[#814C5B] font-semibold' 
                              : 'text-[#4b4848] hover:text-[#814C5B]' }}
                          {{ isset($item['bold']) && $item['bold'] ? 'font-bold' : '' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>
    @endforeach

</div>