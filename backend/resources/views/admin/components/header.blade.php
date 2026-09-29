<!-- Google Fonts: Poppins (termasuk italic) & Radley -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,500;1,600&family=Radley:ital@0;1&display=swap" rel="stylesheet">

<!-- Alpine.js untuk interaksi dropdown -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    /* Semua elemen memakai Poppins, sama seperti halaman pengguna & pengunjung */
    body,
    body * {
        font-family: 'Poppins', sans-serif !important;
    }

    /* Radley hanya untuk elemen bertanda .use-radley beserta isinya (logo) */
    .use-radley,
    .use-radley * {
        font-family: 'Radley', serif !important;
    }

    body {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
</style>

<header class="flex justify-between items-center px-8 py-2.5 bg-[#42393a] border-b-2 border-[#e2b8bc] text-white">
    <!-- Left Logo Section -->
    <div class="flex items-center space-x-3">
        <img src="{{ asset('images/logoPink.png') }}" alt="Chloe Logo" class="h-10 w-auto object-contain">

        <div class="flex flex-col justify-center">
            <div class="use-radley italic text-3xl font-normal text-[#ffdcdc] leading-none">Chloe</div>
            <span class="text-xs font-normal not-italic text-[#d1c2c2] leading-tight mt-1">Campus Hall & Location Online E-booking</span>
        </div>
    </div>

    <!-- Right Profile & Dropdown Section -->
    <div class="relative" x-data="{ open: false }">
        @php
            $authUser  = Auth::user();
            $userName  = $authUser->name ?? 'Administrator';

            // Ambil foto profil dari kolom yang umum dipakai; sesuaikan jika nama kolommu berbeda
            $photo = $authUser->profile_photo
                ?? $authUser->photo
                ?? $authUser->avatar
                ?? $authUser->profile_photo_path
                ?? null;

            // Foto bisa berupa URL penuh atau path di storage/app/public
            $photoUrl = $photo
                ? (\Illuminate\Support\Str::startsWith($photo, ['http://', 'https://']) ? $photo : asset('storage/' . $photo))
                : null;

            $initial = strtoupper(substr($userName, 0, 1));
        @endphp

        <button @click="open = !open"
                class="flex items-center gap-2.5 bg-[#e2b8bc] hover:bg-[#ffdcdc] text-[#42393a] text-base font-semibold pl-1.5 pr-4 py-1.5 rounded-full shadow transition focus:outline-none cursor-pointer">
            @if ($photoUrl)
                <img src="{{ $photoUrl }}" alt="Foto profil {{ $userName }}"
                     class="w-9 h-9 rounded-full object-cover border-2 border-[#42393a]/20">
            @else
                <span class="w-9 h-9 rounded-full bg-[#42393a] text-[#e2b8bc] flex items-center justify-center text-sm font-semibold">
                    {{ $initial }}
                </span>
            @endif
            <span class="max-w-[160px] truncate">{{ $userName }}</span>
            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <!-- Dropdown Menu -->
        <div x-show="open"
             @click.away="open = false"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl py-2 z-50 text-[#4b3839] border border-[#e2b8bc]"
             style="display: none;">

            <!-- Info akun -->
            <div class="flex items-center gap-3 px-4 py-2">
                @if ($photoUrl)
                    <img src="{{ $photoUrl }}" alt="Foto profil {{ $userName }}" class="w-10 h-10 rounded-full object-cover">
                @else
                    <span class="w-10 h-10 rounded-full bg-[#42393a] text-[#e2b8bc] flex items-center justify-center text-sm font-semibold shrink-0">{{ $initial }}</span>
                @endif
                <div class="min-w-0">
                    <p class="text-xs text-gray-500">Signed in as</p>
                    <p class="text-sm font-semibold text-[#2f2a2b] truncate">{{ $userName }}</p>
                </div>
            </div>

            <div class="border-t border-gray-100 my-1"></div>

            <!-- Edit Account -->
            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-[#fcf7f7] transition">
                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Account
            </a>

            <div class="border-t border-gray-100 my-1"></div>

            <!-- Form Log Out -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center px-4 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50 transition text-left cursor-pointer">
                    <svg class="w-4 h-4 mr-2 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Log Out
                </button>
            </form>
        </div>
    </div>
</header>