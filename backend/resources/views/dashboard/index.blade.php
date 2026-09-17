@extends('layouts.app')

@section('title', 'Home - Chloe')

@section('content')

<!-- HERO -->
@php $heroBg = asset('images/hero.png'); @endphp
<section class="relative min-h-[570px] bg-cover bg-center flex items-center justify-between px-[6%] py-[70px] text-white"
         style="background-image: url('{{ $heroBg }}')">

    {{-- Overlay --}}
    <div class="absolute inset-0" style="background: linear-gradient(to bottom, rgba(35,35,35,0.4) 0%, rgba(35,35,35,0.75) 60%, #4A4A4A 100%);"></div>

    {{-- Hero Content --}}
    <div class="relative z-10 -mt-12 text-left">
        <p class="font-['Georgia',serif] italic text-[25px] mb-3 text-[#F7D6D0]">Welcome to Chloe.</p>
        <h1 class="font-['Poppins',sans-serif] text-left text-[#FFF5F5] font-bold leading-tight text-[48px]">
            Looking for your<br>
            <span class="italic text-[#F7D6D0]">Perfect</span> Venue?
        </h1>
        <div class="flex items-center gap-4 mt-6">
            <a href="{{ route('booking.index') }}">
                <button class="border-none bg-[#f3d8d5] px-10 py-3 rounded-[30px] text-base font-['Poppins',sans-serif] font-semibold text-[#4A4A4A] cursor-pointer">
                    Book Now
                </button>
            </a>
            <button class="bg-transparent border-none text-[15px] font-['Poppins',sans-serif] text-[#FFF5F5] cursor-pointer">
                Learn more →
            </button>
        </div>
    </div>

    <!-- Availability Card -->
    <div class="relative z-10 w-[365px] bg-[rgba(55,53,53,0.85)] border-2 border-[#f3d8d5] rounded-[25px] p-6 shadow-[0_0_8px_rgba(255,220,220,0.8)] text-left font-['Poppins',sans-serif]">
        <h2 class="font-bold text-[#FFF5F5] text-lg">Availability Check</h2>
        <div class="h-[2px] bg-[#F7D6D0] my-3 rounded"></div>

        <form method="GET" action="{{ route('booking.index') }}">
            <label class="block text-[13px] text-[#FFF5F5] mt-2 mb-1">Search Facility</label>
            <input type="text" name="q" placeholder="⌕" value="{{ request('q') }}"
                   class="w-full h-[38px] border-2 border-white rounded-xl bg-transparent text-[#FFF5F5] placeholder-[#FFF5F5] px-2 text-sm">

            <div class="bg-[#b9aaaa] text-white rounded-[10px] text-[13px] p-2.5 my-2 font-semibold">
                Hint: Use Indonesian to search<br>keywords for venues or equipments
            </div>

            <label class="block text-[13px] text-[#FFF5F5] mt-2 mb-1">Select Date</label>
            <input type="date" name="date"
                   class="w-full h-[38px] border-2 border-white rounded-xl bg-transparent text-[#FFF5F5] px-2 text-sm">

            <div class="flex gap-3 mt-1">
                <div class="flex-1 flex flex-col">
                    <label class="text-[13px] text-[#FFF5F5] mt-2 mb-1">Start Time</label>
                    <input type="time" name="start_time"
                           class="w-full h-[38px] border-2 border-[#FFF5F5] rounded-xl bg-transparent text-[#FFF5F5] px-2">
                </div>
                <div class="flex-1 flex flex-col">
                    <label class="text-[13px] text-[#FFF5F5] mt-2 mb-1">End Time</label>
                    <input type="time" name="end_time"
                           class="w-full h-[38px] border-2 border-[#FFF5F5] rounded-xl bg-transparent text-[#FFF5F5] px-2">
                </div>
            </div>

            <button type="submit"
                    class="w-full mt-3 border-none rounded-[15px] bg-[#f3d8d5] py-2.5 text-base font-['Poppins',sans-serif] font-black text-[#4b4848] cursor-pointer">
                Check
            </button>
        </form>
    </div>
</section>

<!-- ANNOUNCEMENTS -->
<section class="bg-[#4d4b4b] text-white px-[5%] py-[60px]">
    <h2 class="text-center font-['Radley',Georgia,serif] italic text-[28px] text-[#FFF5F5]">Announcements</h2>
    <div class="w-[90%] h-[2px] bg-white mx-auto my-2.5 mb-5"></div>
    <div class="grid grid-cols-3 gap-5">
        <div class="bg-[#fff7f7] text-[#4b4848] rounded-[10px] p-[18px] min-h-[115px] text-center font-['Poppins',sans-serif]">
            <p class="text-[11px] mb-1.5 font-semibold">03/09/2026</p>
            <h3 class="text-[13px] mb-2 font-semibold">Jadwal Pemeliharaan Aula</h3>
            <span class="block text-[9px] leading-tight mt-1">Aula utama akan ditutup sementara untuk pemeliharaan rutin.</span>
            <a href="#" class="block text-right text-[9px] italic mt-2">Read more ›</a>
        </div>
        <div class="bg-[#fff7f7] text-[#4b4848] rounded-[10px] p-[18px] min-h-[115px] text-center font-['Poppins',sans-serif]">
            <p class="text-[11px] mb-1.5 font-semibold">03/09/2026</p>
            <h3 class="text-[13px] mb-2 font-semibold">Fitur Reservasi Baru</h3>
            <span class="block text-[9px] leading-tight mt-1">Sekarang kamu bisa cek ketersediaan fasilitas langsung dari halaman utama.</span>
            <a href="#" class="block text-right text-[9px] italic mt-2">Read more ›</a>
        </div>
        <div class="bg-[#fff7f7] text-[#4b4848] rounded-[10px] p-[18px] min-h-[115px] text-center font-['Poppins',sans-serif]">
            <p class="text-[11px] mb-1.5 font-semibold">03/09/2026</p>
            <h3 class="text-[13px] mb-2 font-semibold">Jam Operasional Berubah</h3>
            <span class="block text-[9px] leading-tight mt-1">Jam operasional gedung diperbarui mulai bulan ini.</span>
            <a href="#" class="block text-right text-[9px] italic mt-2">Read more ›</a>
        </div>
    </div>
</section>

<!-- HOW TO USE --> 
<section class="bg-[#4d4b4b] text-white px-[5%] py-[60px]">
    <h2 class="text-center font-['Radley',Georgia,serif] italic text-[28px] text-[#FFF5F5]">How to Use</h2>
    <div class="w-[90%] h-[2px] bg-white mx-auto my-2.5 mb-5"></div>
    <div class="grid grid-cols-3 gap-9">
        <div class="bg-[#f0d3cf] text-[#4b4848] rounded-[10px] min-h-[145px] p-[15px] text-center font-['Poppins',sans-serif]">
            <div class="w-[22px] h-[22px] mx-auto mb-4 bg-white rounded-full text-[11px] flex items-center justify-center font-bold">1</div>
            <h3 class="text-[12px] mb-2 font-semibold">Find your venue</h3>
            <p class="text-[10px] leading-tight">Search and select the space you want to book</p>
        </div>
        <div class="bg-[#f0d3cf] text-[#4b4848] rounded-[10px] min-h-[145px] p-[15px] text-center font-['Poppins',sans-serif]">
            <div class="w-[22px] h-[22px] mx-auto mb-4 bg-white rounded-full text-[11px] flex items-center justify-center font-bold">2</div>
            <h3 class="text-[12px] mb-2 font-semibold">Choose your date & time</h3>
            <p class="text-[10px] leading-tight">Pick your preferred schedule for the reservation</p>
        </div>
        <div class="bg-[#f0d3cf] text-[#4b4848] rounded-[10px] min-h-[145px] p-[15px] text-center font-['Poppins',sans-serif]">
            <div class="w-[22px] h-[22px] mx-auto mb-4 bg-white rounded-full text-[11px] flex items-center justify-center font-bold">3</div>
            <h3 class="text-[12px] mb-2 font-semibold">Confirm & Done</h3>
            <p class="text-[10px] leading-tight">Finalize your order</p>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="px-[5%] py-[60px] min-h-[420px]" style="background: linear-gradient(to bottom, #4d4b4b 0%, #FFF5F5 100%);">
    <h2 class="text-center font-['Radley',Georgia,serif] italic text-[28px] text-white">Frequently Asked Questions</h2>
    <div class="w-[90%] h-[2px] bg-white mx-auto my-2.5 mb-5"></div>
    <div class="w-[85%] mx-auto">
        <details class="mb-[18px]" open>
            <summary class="list-none bg-[#4d4b4b] text-white px-4 py-2.5 text-[11px] flex justify-between cursor-pointer font-['Poppins',sans-serif]">
                Bagaimana cara reservasi fasilitas? <span>×</span>
            </summary>
            <div class="min-h-[120px] bg-white border border-[#555] p-5 text-[12px] font-['Poppins',sans-serif]">
                Pilih fasilitas yang ingin digunakan, tentukan tanggal dan waktu, kemudian lakukan reservasi.
            </div>
        </details>
        <details class="mb-[18px]">
            <summary class="list-none bg-[#4d4b4b] text-white px-4 py-2.5 text-[11px] flex justify-between cursor-pointer font-['Poppins',sans-serif]">
                Apakah reservasi bisa dibatalkan? <span>□</span>
            </summary>
            <div class="min-h-[120px] bg-white border border-[#555] p-5 text-[12px] font-['Poppins',sans-serif]">
                Ya, reservasi dapat dibatalkan sesuai dengan ketentuan yang berlaku.
            </div>
        </details>
        <details class="mb-[18px]">
            <summary class="list-none bg-[#4d4b4b] text-white px-4 py-2.5 text-[11px] flex justify-between cursor-pointer font-['Poppins',sans-serif]">
                Berapa lama proses persetujuan reservasi? <span>□</span>
            </summary>
            <div class="min-h-[120px] bg-white border border-[#555] p-5 text-[12px] font-['Poppins',sans-serif]">
                Reservasi akan diproses oleh petugas setelah pengajuan dilakukan.
            </div>
        </details>
    </div>
</section>

@endsection