@php
    // Data foto profil untuk tombol akun (dipakai versi desktop & mobile)
    $navUser     = Auth::user();
    $navPhotoUrl = $navUser && $navUser->photo ? asset('storage/' . $navUser->photo) : null;
    $navInitial  = $navUser ? strtoupper(substr($navUser->name, 0, 1)) : '';
@endphp

<nav x-data="{ open: false }" class="bg-[#EDD3D6] border-b border-[#D8B4B8]">
    <!-- Desktop Navigation Menu -->
    <div class="w-full px-10">
        <div class="flex justify-between h-20">
            <!-- Logo & Brand -->
            <div class="flex items-center">
                <x-application-logo />
            </div>

            <!-- Navigation Links & User Menu -->
            <div class="hidden sm:flex sm:items-center sm:space-x-8 font-semibold text-neutral-800">
                <a href="{{ route('dashboard') }}" class="hover:text-neutral-600 transition">Dashboard</a>
                <a href="{{ route('booking.index') }}" class="hover:text-neutral-600 transition">Booking</a>
                <a href="{{ route('reports.create') }}" class="hover:text-neutral-600 transition">Reports</a>

                @auth
                    <!-- Profile Dropdown (Saat Sudah Login) -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 pl-1.5 pr-4 py-1.5 border border-transparent text-sm font-semibold rounded-full text-white bg-neutral-700 hover:bg-neutral-800 transition">
                                {{-- Foto profil (jika gagal dimuat / belum ada, tampil inisial) --}}
                                @if ($navPhotoUrl)
                                    <img src="{{ $navPhotoUrl }}" alt="Foto profil {{ $navUser->name }}"
                                         class="w-8 h-8 rounded-full object-cover border-2 border-white/20"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <span class="w-8 h-8 rounded-full bg-[#EDD3D6] text-neutral-800 items-center justify-center text-sm font-semibold" style="display:none">{{ $navInitial }}</span>
                                @else
                                    <span class="w-8 h-8 rounded-full bg-[#EDD3D6] text-neutral-800 flex items-center justify-center text-sm font-semibold">{{ $navInitial }}</span>
                                @endif

                                <div class="max-w-[150px] truncate">{{ $navUser->name }}</div>
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            {{-- Info akun --}}
                            <div class="flex items-center gap-3 px-4 py-3 border-b border-gray-100">
                                @if ($navPhotoUrl)
                                    <img src="{{ $navPhotoUrl }}" alt="Foto profil {{ $navUser->name }}"
                                         class="w-9 h-9 rounded-full object-cover shrink-0"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <span class="w-9 h-9 rounded-full bg-neutral-700 text-white items-center justify-center text-sm font-semibold shrink-0" style="display:none">{{ $navInitial }}</span>
                                @else
                                    <span class="w-9 h-9 rounded-full bg-neutral-700 text-white flex items-center justify-center text-sm font-semibold shrink-0">{{ $navInitial }}</span>
                                @endif
                                <div class="min-w-0">
                                    <p class="text-xs text-gray-500">Signed in as</p>
                                    <p class="text-sm font-semibold text-neutral-800 truncate">{{ $navUser->name }}</p>
                                </div>
                            </div>

                            <x-dropdown-link :href="route('profile.show')">
                                <span class="inline-flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    {{ __('Profile') }}
                                </span>
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('profile.edit')">
                                <span class="inline-flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    {{ __('Edit Account') }}
                                </span>
                            </x-dropdown-link>

                            <div class="border-t border-gray-100 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').requestSubmit();">
                                    <span class="inline-flex items-center gap-2 font-semibold text-rose-600">
                                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        {{ __('Log Out') }}
                                    </span>
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <!-- Tombol Sign In (Jika Belum Login) -->
                    <a href="{{ route('login') }}" class="bg-neutral-700 hover:bg-neutral-800 text-white rounded-full px-6 py-2 text-sm transition">
                        Sign In
                    </a>
                @endauth
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-neutral-700 hover:text-neutral-900 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-[#EDD3D6] pb-4 px-4">
        <div class="pt-2 pb-3 space-y-2 font-semibold">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-neutral-800">Dashboard</a>
            <a href="{{ route('booking.index') }}" class="block px-3 py-2 text-neutral-800">Booking</a>
            <a href="{{ route('reports.create') }}" class="block px-3 py-2 text-neutral-800">Reports</a>
        </div>

        <div class="pt-4 pb-1 border-t border-[#D8B4B8]">
            @auth
                <div class="flex items-center gap-3 px-3 mb-3">
                    @if ($navPhotoUrl)
                        <img src="{{ $navPhotoUrl }}" alt="Foto profil {{ $navUser->name }}"
                             class="w-10 h-10 rounded-full object-cover shrink-0"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <span class="w-10 h-10 rounded-full bg-neutral-700 text-white items-center justify-center font-semibold shrink-0" style="display:none">{{ $navInitial }}</span>
                    @else
                        <span class="w-10 h-10 rounded-full bg-neutral-700 text-white flex items-center justify-center font-semibold shrink-0">{{ $navInitial }}</span>
                    @endif
                    <div class="min-w-0">
                        <div class="font-medium text-base text-neutral-800 truncate">{{ $navUser->name }}</div>
                        <div class="font-medium text-sm text-neutral-600 truncate">{{ $navUser->email }}</div>
                    </div>
                </div>

                <x-responsive-nav-link :href="route('profile.show')">
                    <span class="inline-flex items-center gap-2"><svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg> {{ __('Profile') }}</span>
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('profile.edit')">
                    <span class="inline-flex items-center gap-2"><svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg> {{ __('Edit Account') }}</span>
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').requestSubmit();">
                        <span class="inline-flex items-center gap-2 font-semibold text-rose-600"><svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg> {{ __('Log Out') }}</span>
                    </x-responsive-nav-link>
                </form>
            @else
                <a href="{{ route('login') }}" class="block text-center bg-neutral-700 text-white rounded-full px-6 py-2 text-sm mt-2">
                    Sign In
                </a>
            @endauth
        </div>
    </div>
</nav>