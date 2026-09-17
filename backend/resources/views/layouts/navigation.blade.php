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
                <a href="#" class="hover:text-neutral-600 transition">Reports</a>

                @auth
                    <!-- Profile Dropdown (Saat Sudah Login) -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-semibold rounded-full text-white bg-neutral-700 hover:bg-neutral-800 transition">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
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
            <a href="#" class="block px-3 py-2 text-neutral-800">Reports</a>
        </div>

        <div class="pt-4 pb-1 border-t border-[#D8B4B8]">
            @auth
                <div class="px-3 mb-3">
                    <div class="font-medium text-base text-neutral-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-neutral-600">{{ Auth::user()->email }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
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