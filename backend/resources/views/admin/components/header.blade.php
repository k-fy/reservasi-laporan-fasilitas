<!-- Google Fonts: Poppins & Radley -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Radley:ital@0;1&display=swap" rel="stylesheet">

<!-- Alpine.js untuk interaksi dropdown -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    body, button, input, table, span, p, a, div:not(.use-radley) {
        font-family: 'Poppins', sans-serif !important;
    }
    .use-radley {
        font-family: 'Radley', serif !important;
    }
</style>

<header class="flex justify-between items-center px-8 py-2.5 bg-[#42393a] border-b-2 border-[#e2b8bc] text-white">
    <!-- Left Logo Section -->
    <div class="flex items-center space-x-3">
        <img src="{{ asset('images/logoPink.png') }}" alt="Chloe Logo" class="h-10 w-auto object-contain">
        
        <div class="flex flex-col justify-center">
            <div class="use-radley italic text-2xl font-bold tracking-wider text-[#ffdcdc] leading-tight">Chloe</div>
            <span class="use-radley italic text-xs text-[#ffdcdc] leading-tight">Campus Hall & Location Online E-booking</span>
        </div>
    </div>

    <!-- Right Profile & Dropdown Section -->
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="flex items-center space-x-3 text-xs focus:outline-none cursor-pointer group">
            <div class="flex flex-col text-right">
                <span class="text-[#d1c2c2] text-[11px] leading-tight">Logged in as</span>
                <strong class="underline italic text-white use-radley text-sm font-normal leading-tight group-hover:text-[#ffdcdc] transition">
                    {{ Auth::user()->name ?? 'Admin Chloe' }}
                </strong>
            </div>
            <div class="w-8 h-8 rounded-full bg-[#a86b6b] flex items-center justify-center font-bold text-white shadow">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
            </div>
            <svg class="w-4 h-4 text-[#d1c2c2] transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
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
             class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl py-2 z-50 text-[#4b3839] border border-[#e2b8bc]"
             style="display: none;">
            
           
            <!-- Ke Recap (Pengganti Main Dashboard) -->
            <a href="{{ route('admin.summary') }}" class="flex items-center px-4 py-2 text-xs font-medium text-gray-700 hover:bg-[#fcf7f7] transition">
                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Recap
            </a>

            <div class="border-t border-gray-100 my-1"></div>

            <!-- Form Log Out -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition text-left cursor-pointer">
                    <svg class="w-4 h-4 mr-2 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Log Out
                </button>
            </form>
        </div>
    </div>
</header>