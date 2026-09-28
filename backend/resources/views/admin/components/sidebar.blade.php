{{-- Sidebar admin — dipakai di semua halaman admin. Menu aktif ditentukan otomatis dari route. --}}
@php
    $sidebarMenus = [
        [
            'route' => 'admin.accounts',
            'label' => 'Accounts',
            'icon'  => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
        ],
        [
            'route' => 'admin.facilities',
            'label' => 'Facilities',
            'icon'  => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
        ],
        [
            'route' => 'admin.recap',
            'label' => 'Recap',
            'icon'  => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        ],
    ];
@endphp

<aside class="w-64 shrink-0 bg-[#e2b8bc] text-[#4b3839] shadow-md">
    <nav class="sticky top-0 flex flex-col gap-1.5 px-4 py-6">
        <p class="px-4 mb-2 text-xs font-semibold uppercase tracking-widest text-[#86545e]">Menu</p>

        @foreach ($sidebarMenus as $menu)
            @php $active = request()->routeIs($menu['route'] . '*'); @endphp
            <a href="{{ route($menu['route']) }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-base transition
                      {{ $active
                            ? 'bg-[#5c4f50] text-white font-semibold shadow'
                            : 'font-medium hover:bg-white/50' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $menu['icon'] }}"/>
                </svg>
                {{ $menu['label'] }}
            </a>
        @endforeach
    </nav>
</aside>