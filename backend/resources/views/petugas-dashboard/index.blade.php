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
            <a href="#faq" class="bg-transparent border-none text-[15px] font-['Poppins',sans-serif] text-[#FFF5F5] cursor-pointer no-underline">
                Learn more →
            </a>
        </div>
    </div>

    <!-- Availability Card -->
    <div class="relative z-10 w-[365px] bg-[rgba(55,53,53,0.85)] border-2 border-[#f3d8d5] rounded-[25px] p-6 shadow-[0_0_8px_rgba(255,220,220,0.8)] text-left font-['Poppins',sans-serif]">
        <h2 class="font-bold text-[#FFF5F5] text-lg">Availability Check</h2>
        <div class="h-[2px] bg-[#F7D6D0] my-3 rounded"></div>

        <form method="GET" action="{{ route('booking.index') }}">
            <!-- Search Facility -->
            <label class="block text-[13px] text-[#FFF5F5] mt-2 mb-1">Search Facility</label>
            <div class="relative w-full">
                <input type="text" name="q" value="{{ request('q') }}"
                       class="w-full h-[38px] border-2 border-white rounded-xl bg-transparent text-[#FFF5F5] placeholder-[#FFF5F5] pl-3 pr-8 text-sm">
                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[#FFF5F5] text-lg pointer-events-none">
                    ⌕
                </span>
            </div>

            <div class="bg-[#b9aaaa] text-white rounded-[10px] text-[13px] p-2.5 my-2 font-semibold">
                Hint: Use Indonesian to search<br>keywords for venues or equipments
            </div>

            <!-- Select Date -->
            <label class="block text-[13px] text-[#FFF5F5] mt-2 mb-1">Select Date</label>
            <input type="date" name="date"
                   onclick="this.showPicker()"
                   class="w-full h-[38px] border-2 border-white rounded-xl bg-transparent text-[#FFF5F5] px-2 text-sm cursor-pointer font-['Poppins',sans-serif]">

            @php
                $timeOptions = [];
                for ($i = 7; $i <= 19; $i++) {
                    $timeOptions[] = sprintf('%02d:00', $i);
                    $timeOptions[] = sprintf('%02d:30', $i);
                }
                $timeOptions[] = '20:00';
            @endphp

            <div class="flex gap-3 mt-1">
                <!-- Start Time -->
                <div class="flex-1 flex flex-col">
                    <label class="text-[13px] text-[#FFF5F5] mt-2 mb-1">Start Time</label>
                    <select name="start_time" id="start_time" required
                            class="w-full h-[38px] border-2 border-[#FFF5F5] rounded-xl bg-[#4b4848] text-[#FFF5F5] px-2 text-sm font-['Poppins',sans-serif]">
                        <option value="" disabled selected></option>
                        @foreach($timeOptions as $time)
                            @if($time !== '20:00')
                                <option value="{{ $time }}" {{ request('start_time') == $time ? 'selected' : '' }}>
                                    {{ $time }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <!-- End Time -->
                <div class="flex-1 flex flex-col">
                    <label class="text-[13px] text-[#FFF5F5] mt-2 mb-1">End Time</label>
                    <select name="end_time" id="end_time" required
                            class="w-full h-[38px] border-2 border-[#FFF5F5] rounded-xl bg-[#4b4848] text-[#FFF5F5] px-2 text-sm font-['Poppins',sans-serif]">
                        <option value="" disabled selected></option>
                        @foreach($timeOptions as $time)
                            @if($time !== '07:00')
                                <option value="{{ $time }}" {{ request('end_time') == $time ? 'selected' : '' }}>
                                    {{ $time }}
                                </option>
                            @endif
                        @endforeach
                    </select>
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

    <div class="grid grid-cols-3 gap-5 items-start">

        <!-- Kartu 1: Jadwal Pemeliharaan Aula -->
        <div class="bg-[#fff7f7] text-[#4b4848] rounded-[10px] p-[18px] text-center font-['Poppins',sans-serif] flex flex-col justify-between">
            <div>
                <p class="text-[11px] mb-1.5 font-semibold">03/09/2026</p>
                <h3 class="text-[13px] mb-2 font-semibold">Jadwal Pemeliharaan Aula</h3>
                <div class="min-h-[32px] flex items-center justify-center">
                    <span class="text-[9px] leading-tight">Aula utama akan ditutup sementara untuk pemeliharaan rutin.</span>
                </div>
            </div>
            <details class="group mt-3 text-left">
                <summary class="list-none text-right text-[9px] italic text-[#4b4848] cursor-pointer font-semibold select-none">
                    <span class="group-open:hidden">Read more ›</span>
                    <span class="hidden group-open:inline">Tutup ‹</span>
                </summary>
                <div class="mt-2 pt-2 border-t border-gray-300 text-[10px] leading-relaxed text-gray-600 text-justify">
                    Pemeliharaan rutin aula utama akan dilaksanakan mulai pukul 08:00 WIB hingga selesai. Hal ini dilakukan untuk memastikan fasilitas pendingin ruangan, kelistrikan, dan sistem audio dalam kondisi optimal demi kenyamanan bersama. Mohon maaf atas ketidaknyamanannya.
                </div>
            </details>
        </div>

        <!-- Kartu 2: Fitur Reservasi Baru -->
        <div class="bg-[#fff7f7] text-[#4b4848] rounded-[10px] p-[18px] text-center font-['Poppins',sans-serif] flex flex-col justify-between">
            <div>
                <p class="text-[11px] mb-1.5 font-semibold">03/09/2026</p>
                <h3 class="text-[13px] mb-2 font-semibold">Fitur Reservasi Baru</h3>
                <div class="min-h-[32px] flex items-center justify-center">
                    <span class="text-[9px] leading-tight">Sekarang kamu bisa cek ketersediaan fasilitas langsung dari halaman utama.</span>
                </div>
            </div>
            <details class="group mt-3 text-left">
                <summary class="list-none text-right text-[9px] italic text-[#4b4848] cursor-pointer font-semibold select-none">
                    <span class="group-open:hidden">Read more ›</span>
                    <span class="hidden group-open:inline">Tutup ‹</span>
                </summary>
                <div class="mt-2 pt-2 border-t border-gray-300 text-[10px] leading-relaxed text-gray-600 text-justify">
                    Kini pengunjung dapat langsung memeriksa ketersediaan ruangan atau peralatan secara real-time melalui kotak "Availability Check" di halaman utama tanpa harus repot masuk atau daftar akun terlebih dahulu. Proses pengecekan menjadi jauh lebih cepat dan praktis!
                </div>
            </details>
        </div>

        <!-- Kartu 3: Jam Operasional Berubah -->
        <div class="bg-[#fff7f7] text-[#4b4848] rounded-[10px] p-[18px] text-center font-['Poppins',sans-serif] flex flex-col justify-between">
            <div>
                <p class="text-[11px] mb-1.5 font-semibold">03/09/2026</p>
                <h3 class="text-[13px] mb-2 font-semibold">Jam Operasional Berubah</h3>
                <div class="min-h-[32px] flex items-center justify-center">
                    <span class="text-[9px] leading-tight">Jam operasional gedung diperbarui mulai bulan ini.</span>
                </div>
            </div>
            <details class="group mt-3 text-left">
                <summary class="list-none text-right text-[9px] italic text-[#4b4848] cursor-pointer font-semibold select-none">
                    <span class="group-open:hidden">Read more ›</span>
                    <span class="hidden group-open:inline">Tutup ‹</span>
                </summary>
                <div class="mt-2 pt-2 border-t border-gray-300 text-[10px] leading-relaxed text-gray-600 text-justify">
                    Menyesuaikan dengan jadwal kegiatan kampus terbaru, jam operasional layanan peminjaman fasilitas dan gedung kini dibuka setiap hari Senin hingga Sabtu mulai pukul 07:00 WIB dan ditutup pada pukul 20:00 WIB. Hari Minggu dan tanggal merah layanan libur.
                </div>
            </details>
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
<section id="faq" class="px-[5%] py-[60px] min-h-[420px]" style="background: linear-gradient(to bottom, #4d4b4b 0%, #FFF5F5 100%);">
    <h2 class="text-center font-['Radley',Georgia,serif] italic text-[28px] text-white">Frequently Asked Questions</h2>
    <div class="w-[90%] h-[2px] bg-white mx-auto my-2.5 mb-5"></div>
    <div class="w-[85%] mx-auto">

        <!-- FAQ Item 1 -->
        <details class="group mb-[18px]">
            <summary class="list-none bg-[#4d4b4b] text-white px-4 py-2.5 text-[11px] flex justify-between items-center cursor-pointer font-['Poppins',sans-serif]">
                Bagaimana cara reservasi fasilitas?
                <span class="text-sm font-bold group-open:hidden">▼</span>
                <span class="text-base font-bold hidden group-open:inline">×</span>
            </summary>
            <div class="min-h-[120px] bg-white border border-[#555] p-5 text-[12px] font-['Poppins',sans-serif]">
                Pilih fasilitas yang ingin digunakan, tentukan tanggal dan waktu, kemudian lakukan reservasi.
            </div>
        </details>

        <!-- FAQ Item 2 -->
        <details class="group mb-[18px]">
            <summary class="list-none bg-[#4d4b4b] text-white px-4 py-2.5 text-[11px] flex justify-between items-center cursor-pointer font-['Poppins',sans-serif]">
                Apakah reservasi bisa dibatalkan?
                <span class="text-sm font-bold group-open:hidden">▼</span>
                <span class="text-base font-bold hidden group-open:inline">×</span>
            </summary>
            <div class="min-h-[120px] bg-white border border-[#555] p-5 text-[12px] font-['Poppins',sans-serif]">
                Ya, reservasi dapat dibatalkan sesuai dengan ketentuan yang berlaku.
            </div>
        </details>

        <!-- FAQ Item 3 -->
        <details class="group mb-[18px]">
            <summary class="list-none bg-[#4d4b4b] text-white px-4 py-2.5 text-[11px] flex justify-between items-center cursor-pointer font-['Poppins',sans-serif]">
                Berapa lama proses persetujuan reservasi?
                <span class="text-sm font-bold group-open:hidden">▼</span>
                <span class="text-base font-bold hidden group-open:inline">×</span>
            </summary>
            <div class="min-h-[120px] bg-white border border-[#555] p-5 text-[12px] font-['Poppins',sans-serif]">
                Reservasi akan diproses oleh petugas setelah pengajuan dilakukan.
            </div>
        </details>

    </div>
</section>

<script>
    const startSelect = document.getElementById('start_time');
    const endSelect = document.getElementById('end_time');

    startSelect.addEventListener('change', function() {
        const startVal = this.value;
        if (!startVal) return;

        Array.from(endSelect.options).forEach(option => {
            if (option.value === "") return;
            if (option.value <= startVal) {
                option.disabled = true;
                option.style.color = 'gray';
            } else {
                option.disabled = false;
                option.style.color = 'white';
            }
        });

        if (endSelect.value && endSelect.value <= startVal) {
            const firstAvailable = Array.from(endSelect.options).find(opt => !opt.disabled && opt.value !== "");
            if (firstAvailable) {
                endSelect.value = firstAvailable.value;
            } else {
                endSelect.value = "";
            }
        }
    });

    if (startSelect.value) {
        startSelect.dispatchEvent(new Event('change'));
    }
</script>

@endsection