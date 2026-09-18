<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard Petugas - Chloe')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="min-h-screen flex flex-col">

        {{-- Header --}}
        <header class="bg-[#3F3B3D] px-8 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2 text-white">
                <x-application-logo class="h-8 w-auto" />
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right text-white">
                    <div class="text-xs text-neutral-300">Logged in as</div>
                    <div class="text-sm font-semibold underline decoration-rose-300">
                        {{ Auth::user()->name }}
                    </div>
                </div>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="w-9 h-9 rounded-full bg-rose-300 flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </header>

        <div class="flex flex-1">

            {{-- Sidebar --}}
            <aside class="w-64 flex-shrink-0 bg-[#F3DEE1] py-8">
                <nav class="flex flex-col">
                    @php
                        $navItems = [
                            ['label' => 'Dashboard', 'route' => 'dashboard.petugas'],
                            ['label' => 'Reservations', 'route' => 'petugas.reservations'],
                            ['label' => 'Reports', 'route' => 'petugas.reports'],
                            ['label' => 'Facility Status', 'route' => 'petugas.facility-status'],
                        ];
                    @endphp
                    @foreach ($navItems as $item)
                        <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                           class="px-6 py-4 font-semibold transition
                                  {{ request()->routeIs($item['route'])
                                        ? 'bg-[#B98A93] text-white border-l-4 border-[#5C2E36]'
                                        : 'text-neutral-700 hover:bg-[#EAC9CE]' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
            </aside>

            {{-- Main content --}}
            <main class="flex-1 bg-[#7C6169] p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>