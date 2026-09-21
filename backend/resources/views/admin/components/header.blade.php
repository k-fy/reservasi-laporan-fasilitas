<!-- Google Fonts: Poppins & Radley -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Radley:ital@0;1&display=swap" rel="stylesheet">

<style>
    body, button, input, table, span, p, a, div:not(.use-radley) {
        font-family: 'Poppins', sans-serif !important;
    }
    .use-radley {
        font-family: 'Radley', serif !important;
    }
</style>

<header class="flex justify-between items-center px-8 py-2.5 bg-[#42393a] border-b-2 border-[#e2b8bc] text-white">
    <div class="flex items-center space-x-3">
      
        <img src="{{ asset('images/logoPink.png') }}" alt="Chloe Logo" class="h-10 w-auto object-contain">
        
        <div class="flex flex-col justify-center">
            <div class="use-radley italic text-2xl font-bold tracking-wider text-[#ffdcdc] leading-tight">Chloe</div>
            <span class="use-radley italic text-xs text-[#ffdcdc] leading-tight">Campus Hall & Location Online E-booking</span>
        </div>
    </div>

  
    <div class="flex items-center space-x-3 text-xs">
        <div class="flex flex-col text-right">
            <span class="text-[#d1c2c2] text-[11px] leading-tight">Logged in as</span>
            <strong class="underline italic text-white use-radley text-sm font-normal leading-tight">{{ Auth::user()->name ?? 'Admin Chloe' }}</strong>
        </div>
        <div class="w-8 h-8 rounded-full bg-[#a86b6b] flex items-center justify-center font-bold text-white shadow">
            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
        </div>
        <svg class="w-4 h-4 text-[#d1c2c2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </div>
</header>