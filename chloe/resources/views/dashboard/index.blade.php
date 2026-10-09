@extends('layouts.app')

@section('title', 'Home - Chloe')

@section('content')

{{-- ================= ANIMASI BERANDA ================= --}}
<style>
    /* Muncul perlahan saat halaman dibuka */
    .hero-fade { animation: heroFade .8s ease-out both; }
    .hero-fade-delay { animation: heroFade .8s ease-out .2s both; }
    @keyframes heroFade {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Muncul dari bawah saat di-scroll ke bagian tersebut */
    .reveal { opacity: 0; transform: translateY(28px); transition: opacity .7s ease, transform .7s ease; }
    .reveal.is-visible { opacity: 1; transform: translateY(0); }

    /* Kartu sedikit terangkat saat disorot */
    .lift { transition: transform .25s ease, box-shadow .25s ease; }
    .lift:hover { transform: translateY(-5px); box-shadow: 0 12px 24px rgba(0, 0, 0, .25); }

    /* Ikon FAQ berputar saat dibuka */
    .faq-icon { transition: transform .3s ease; }
    details[open] > summary .faq-icon { transform: rotate(180deg); }
    .faq-item > summary { transition: background-color .2s ease; }
    .faq-item > summary:hover { background-color: #5c5959; }

    /* Hasil Availability Check muncul halus */
    .result-pop { animation: resultPop .35s ease-out; }
    @keyframes resultPop {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Matikan animasi untuk pengguna yang memilih "kurangi gerakan" */
    @media (prefers-reduced-motion: reduce) {
        .hero-fade, .hero-fade-delay, .result-pop { animation: none; }
        .reveal { opacity: 1; transform: none; transition: none; }
        .lift, .lift:hover { transform: none; transition: none; }
        .faq-icon { transition: none; }
    }
</style>

<!-- HERO -->
@php $heroBg = asset('images/hero.png'); @endphp
<section class="relative min-h-[570px] bg-cover bg-center flex flex-wrap items-center justify-between gap-8 px-[6%] py-[70px] text-white"
         style="background-image: url('{{ $heroBg }}')">

    {{-- Overlay --}}
    <div class="absolute inset-0" style="background: linear-gradient(to bottom, rgba(35,35,35,0.4) 0%, rgba(35,35,35,0.75) 60%, #4A4A4A 100%);"></div>

    {{-- Hero Content --}}
    <div class="relative z-10 -mt-12 text-left hero-fade">
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
    <div id="availability-card" class="hero-fade-delay relative z-10 w-[365px] max-w-full bg-[rgba(55,53,53,0.85)] border-2 border-[#f3d8d5] rounded-[25px] p-6 shadow-[0_0_8px_rgba(255,220,220,0.8)] text-left font-['Poppins',sans-serif]">
        <h2 class="font-bold text-[#FFF5F5] text-lg">Availability Check</h2>
        <div class="h-[2px] bg-[#F7D6D0] my-3 rounded"></div>

        @php
            // Slot tetap 30 menit dalam jam operasional 07.00–20.00
            $timeOptions = [];
            for ($i = 7; $i <= 19; $i++) {
                $timeOptions[] = sprintf('%02d:00', $i);
                $timeOptions[] = sprintf('%02d:30', $i);
            }
            $timeOptions[] = '20:00';
        @endphp

        {{-- Dikirim lewat JavaScript ke route availability.check; hasil tampil di bawah hero tanpa pindah halaman --}}
        <form method="GET" action="{{ route('availability.check') }}" id="availability-form" novalidate autocomplete="off">

            <input type="hidden" id="facility_id" name="facility_id">

            <!-- Search Facility + saran -->
            <label for="q" class="block text-[13px] text-[#FFF5F5] mt-2 mb-1">Search Facility</label>
            <div class="relative w-full">
                <input type="text" id="q" name="q" maxlength="100"
                       placeholder="Type, then pick a facility..."
                       role="combobox" aria-autocomplete="list" aria-expanded="false" aria-controls="facility-suggestions"
                       class="w-full h-[38px] border-2 border-white rounded-xl bg-transparent text-[#FFF5F5] placeholder-[#FFF5F5]/60 pl-3 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-[#f3d8d5]">
                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[#FFF5F5] text-lg pointer-events-none">⌕</span>

                <!-- Daftar saran fasilitas -->
                <ul id="facility-suggestions" role="listbox"
                    class="hidden absolute left-0 right-0 top-[calc(100%+6px)] z-30 max-h-64 overflow-y-auto bg-white text-[#4b4848] rounded-xl shadow-xl border border-[#f3d8d5] py-1"></ul>
            </div>

            <div class="bg-[#b9aaaa] text-white rounded-[10px] text-[13px] p-2.5 my-2 font-semibold">
                Hint: Use Indonesian to search<br>keywords for venues or equipments
            </div>

            <!-- Select Date -->
            <label for="date" class="block text-[13px] text-[#FFF5F5] mt-2 mb-1">Select Date <span class="text-[#f3d8d5]">*</span></label>
            <input type="date" id="date" name="date" required
                   min="{{ now()->toDateString() }}"
                   onclick="this.showPicker && this.showPicker()"
                   class="w-full h-[38px] border-2 border-white rounded-xl bg-transparent text-[#FFF5F5] px-2 text-sm cursor-pointer font-['Poppins',sans-serif] focus:outline-none focus:ring-2 focus:ring-[#f3d8d5] [color-scheme:dark]">

            <div class="flex gap-3 mt-1" x-data="{ globalStartTime: '' }">
                <!-- Start Time -->
                <div class="flex-1 flex flex-col" x-data="{ 
                        open: false, 
                        selectedValue: '{{ old('start_time') }}', 
                        selectedLabel: '{{ old('start_time') ?: '--:--' }}',
                        options: [
                            @foreach($timeOptions as $time)
                                @if($time !== '20:00')
                                    { label: '{{ $time }}', value: '{{ $time }}' },
                                @endif
                            @endforeach
                        ]
                    }" 
                    class="relative w-full font-sans" 
                    @click.outside="open = false">
                    
                    <label class="text-[13px] text-[#FFF5F5] mt-2 mb-1">Start Time <span class="text-[#f3d8d5]">*</span></label>
                    
                    <select name="start_time" id="start_time" x-model="selectedValue" required class="hidden">
                        <option value="">--:--</option>
                        @foreach($timeOptions as $time)
                            @if($time !== '20:00')
                                <option value="{{ $time }}">{{ $time }}</option>
                            @endif
                        @endforeach
                    </select>

                    <div class="relative">
                        <button 
                            @click="open = !open" 
                            type="button" 
                            class="w-full h-[38px] bg-[#4b4848] text-[#FFF5F5] border-2 border-[#FFF5F5] rounded-xl px-3 text-sm flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-[#f3d8d5] transition-all">
                            <span x-text="selectedLabel"></span>
                            <svg class="w-4 h-4 text-[#FFF5F5] transition-transform duration-200" 
                                :class="open ? 'rotate-180' : ''" 
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div 
                            x-show="open" 
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute z-50 w-full mt-2 bg-white border border-[#f3d8d5] rounded-xl shadow-xl overflow-hidden"
                            style="display: none;">
                            
                            <ul class="py-1 max-h-48 overflow-y-auto">
                                <template x-for="option in options" :key="option.value">
                                    <li 
                                        @click="
                                            selectedValue = option.value; 
                                            selectedLabel = option.label;
                                            open = false;
                                            // Kirim data ke parent scope agar End Time langsung merespons
                                            globalStartTime = option.value;
                                            
                                            let endTimeInput = document.getElementById('end_time');
                                            if (endTimeInput.value && option.value >= endTimeInput.value) {
                                                window.dispatchEvent(new CustomEvent('reset-end-time'));
                                            }
                                        "
                                        class="px-4 py-2 cursor-pointer text-sm transition-colors"
                                        :class="selectedValue === option.value 
                                            ? 'bg-[#FCF1F0] text-[#814C5B] font-bold' 
                                            : 'text-[#4b4848] hover:bg-[#FDF8F8] hover:text-[#814C5B]'">
                                        <span x-text="option.label"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- End Time -->
                <div class="flex-1 flex flex-col" x-data="{ 
                        open: false, 
                        selectedValue: '{{ old('end_time') }}', 
                        selectedLabel: '{{ old('end_time') ?: '--:--' }}',
                        options: [
                            @foreach($timeOptions as $time)
                                @if($time !== '07:00')
                                    { label: '{{ $time }}', value: '{{ $time }}' },
                                @endif
                            @endforeach
                        ]
                    }" 
                    @reset-end-time.window="selectedValue = ''; selectedLabel = '--:--'"
                    class="relative w-full font-sans" 
                    @click.outside="open = false">
                    
                    <label class="text-[13px] text-[#FFF5F5] mt-2 mb-1">End Time <span class="text-[#f3d8d5]">*</span></label>
                    
                    <select name="end_time" id="end_time" x-model="selectedValue" required class="hidden">
                        <option value="">--:--</option>
                        @foreach($timeOptions as $time)
                            @if($time !== '07:00')
                                <option value="{{ $time }}">{{ $time }}</option>
                            @endif
                        @endforeach
                    </select>

                    <div class="relative">
                        <button 
                            @click="open = !open" 
                            type="button" 
                            class="w-full h-[38px] bg-[#4b4848] text-[#FFF5F5] border-2 border-[#FFF5F5] rounded-xl px-3 text-sm flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-[#f3d8d5] transition-all">
                            <span x-text="selectedLabel"></span>
                            <svg class="w-4 h-4 text-[#FFF5F5] transition-transform duration-200" 
                                :class="open ? 'rotate-180' : ''" 
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div 
                            x-show="open" 
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute z-50 w-full mt-2 bg-white border border-[#f3d8d5] rounded-xl shadow-xl overflow-hidden"
                            style="display: none;">
                            
                            <ul class="py-1 max-h-48 overflow-y-auto">
                                <template x-for="option in options" :key="option.value">
                                    <li 
                                        @click="
                                            if (!globalStartTime || option.value > globalStartTime) {
                                                selectedValue = option.value; 
                                                selectedLabel = option.label;
                                                open = false;
                                            }
                                        "
                                        class="px-4 py-2 text-sm transition-colors"
                                        :class="{
                                            'bg-[#FCF1F0] text-[#814C5B] font-bold': selectedValue === option.value,
                                            'opacity-40 cursor-not-allowed text-gray-400 bg-gray-50 select-none': globalStartTime && option.value <= globalStartTime,
                                            'cursor-pointer text-[#4b4848] hover:bg-[#FDF8F8] hover:text-[#814C5B]': !globalStartTime || option.value > globalStartTime
                                        }">
                                        <span x-text="option.label"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pesan error (validasi client & server) -->
            <p id="availability-error" class="hidden mt-3 text-[13px] text-[#ffd1d1] bg-[#b2455a]/40 border border-[#ffd1d1]/40 rounded-lg px-3 py-2"></p>

            <p class="mt-2 text-[11px] text-[#FFF5F5]/70">Operating hours 07:00–20:00, in 30-minute slots.</p>

            <button type="submit" id="availability-submit"
                    class="w-full mt-3 border-none rounded-[15px] bg-[#f3d8d5] hover:bg-white transition py-2.5 text-base font-['Poppins',sans-serif] font-black text-[#4b4848] cursor-pointer disabled:opacity-60 disabled:cursor-wait">
                Check
            </button>

            <!-- Hasil: Available / Not Available (tampil sebagai status, bukan tombol) -->
            <div id="availability-result" class="hidden mt-4 pt-4 border-t border-[#FFF5F5]/20" aria-live="polite">
                <div class="flex items-start gap-3">
                    <span id="result-icon" class="w-9 h-9 rounded-full flex items-center justify-center shrink-0"></span>
                    <div class="min-w-0">
                        <p class="text-[10px] uppercase tracking-[0.15em] text-[#FFF5F5]/60">Status</p>
                        <p id="result-title" class="text-[17px] font-bold leading-tight"></p>
                        <p id="result-detail" class="mt-1 text-[12px] text-[#FFF5F5]/80 leading-relaxed"></p>
                    </div>
                </div>
                <div id="result-action" class="mt-3"></div>
            </div>
        </form>
    </div>

</section>



<!-- ANNOUNCEMENTS -->
<section class="bg-[#4d4b4b] text-white px-[5%] py-[60px]">
    <h2 class="reveal text-center font-['Radley',Georgia,serif] italic text-[28px] text-[#FFF5F5]">Announcements</h2>
    <div class="reveal w-[90%] h-[2px] bg-white mx-auto my-2.5 mb-5"></div>

    <div class="grid grid-cols-3 gap-5 items-start">

        <!-- Kartu 1: Jadwal Pemeliharaan Aula -->
        <div class="reveal lift bg-[#fff7f7] text-[#4b4848] rounded-[10px] p-[18px] text-center font-['Poppins',sans-serif] flex flex-col justify-between">
            <div>
                <p class="text-[11px] mb-1.5 font-semibold">03/09/2026</p>
                <h3 class="text-[13px] mb-2 font-semibold">Jadwal Pemeliharaan Aula</h3>
                <div class="min-h-[32px] flex items-center justify-center">
                    <span class="text-[9px] leading-tight">Aula utama akan ditutup sementara untuk pemeliharaan rutin.</span>
                </div>
            </div>
            <details class="group mt-3 text-left" data-accordion>
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
        <div class="reveal lift bg-[#fff7f7] text-[#4b4848] rounded-[10px] p-[18px] text-center font-['Poppins',sans-serif] flex flex-col justify-between">
            <div>
                <p class="text-[11px] mb-1.5 font-semibold">03/09/2026</p>
                <h3 class="text-[13px] mb-2 font-semibold">Fitur Reservasi Baru</h3>
                <div class="min-h-[32px] flex items-center justify-center">
                    <span class="text-[9px] leading-tight">Sekarang kamu bisa cek ketersediaan fasilitas langsung dari halaman utama.</span>
                </div>
            </div>
            <details class="group mt-3 text-left" data-accordion>
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
        <div class="reveal lift bg-[#fff7f7] text-[#4b4848] rounded-[10px] p-[18px] text-center font-['Poppins',sans-serif] flex flex-col justify-between">
            <div>
                <p class="text-[11px] mb-1.5 font-semibold">03/09/2026</p>
                <h3 class="text-[13px] mb-2 font-semibold">Jam Operasional Berubah</h3>
                <div class="min-h-[32px] flex items-center justify-center">
                    <span class="text-[9px] leading-tight">Jam operasional gedung diperbarui mulai bulan ini.</span>
                </div>
            </div>
            <details class="group mt-3 text-left" data-accordion>
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
    <h2 class="reveal text-center font-['Radley',Georgia,serif] italic text-[28px] text-[#FFF5F5]">How to Use</h2>
    <div class="reveal w-[90%] h-[2px] bg-white mx-auto my-2.5 mb-5"></div>
    <div class="grid grid-cols-3 gap-9">
        <div class="reveal lift bg-[#f0d3cf] text-[#4b4848] rounded-[10px] min-h-[145px] p-[15px] text-center font-['Poppins',sans-serif]">
            <div class="w-[22px] h-[22px] mx-auto mb-4 bg-white rounded-full text-[11px] flex items-center justify-center font-bold">1</div>
            <h3 class="text-[12px] mb-2 font-semibold">Find your venue</h3>
            <p class="text-[10px] leading-tight">Search and select the space you want to book</p>
        </div>
        <div class="reveal lift bg-[#f0d3cf] text-[#4b4848] rounded-[10px] min-h-[145px] p-[15px] text-center font-['Poppins',sans-serif]">
            <div class="w-[22px] h-[22px] mx-auto mb-4 bg-white rounded-full text-[11px] flex items-center justify-center font-bold">2</div>
            <h3 class="text-[12px] mb-2 font-semibold">Choose your date & time</h3>
            <p class="text-[10px] leading-tight">Pick your preferred schedule for the reservation</p>
        </div>
        <div class="reveal lift bg-[#f0d3cf] text-[#4b4848] rounded-[10px] min-h-[145px] p-[15px] text-center font-['Poppins',sans-serif]">
            <div class="w-[22px] h-[22px] mx-auto mb-4 bg-white rounded-full text-[11px] flex items-center justify-center font-bold">3</div>
            <h3 class="text-[12px] mb-2 font-semibold">Confirm & Done</h3>
            <p class="text-[10px] leading-tight">Finalize your order</p>
        </div>
    </div>
</section>

<!-- FAQ -->
<section id="faq" class="px-[5%] py-[60px] min-h-[420px]" style="background: linear-gradient(to bottom, #4d4b4b 0%, #FFF5F5 100%);">
    <h2 class="reveal text-center font-['Radley',Georgia,serif] italic text-[28px] text-white">Frequently Asked Questions</h2>
    <div class="reveal w-[90%] h-[2px] bg-white mx-auto my-2.5 mb-5"></div>
    <div class="w-[85%] mx-auto">

        <!-- FAQ Item 1 -->
        <details class="faq-item reveal group mb-[18px] rounded-lg overflow-hidden" data-accordion="faq">
            <summary class="list-none bg-[#4d4b4b] text-white px-4 py-2.5 text-[11px] flex justify-between items-center cursor-pointer font-['Poppins',sans-serif]">
                Bagaimana cara reservasi fasilitas?
                <svg class="faq-icon w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
            </summary>
            <div class="bg-white border border-[#555] border-t-0 p-5 text-[12px] leading-relaxed font-['Poppins',sans-serif]">
                Pilih fasilitas yang ingin digunakan, tentukan tanggal dan waktu, kemudian lakukan reservasi.
            </div>
        </details>

        <!-- FAQ Item 2 -->
        <details class="faq-item reveal group mb-[18px] rounded-lg overflow-hidden" data-accordion="faq">
            <summary class="list-none bg-[#4d4b4b] text-white px-4 py-2.5 text-[11px] flex justify-between items-center cursor-pointer font-['Poppins',sans-serif]">
                Apakah reservasi bisa dibatalkan?
                <svg class="faq-icon w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
            </summary>
            <div class="bg-white border border-[#555] border-t-0 p-5 text-[12px] leading-relaxed font-['Poppins',sans-serif]">
                Ya, reservasi dapat dibatalkan sesuai dengan ketentuan yang berlaku.
            </div>
        </details>

        <!-- FAQ Item 3 -->
        <details class="faq-item reveal group mb-[18px] rounded-lg overflow-hidden" data-accordion="faq">
            <summary class="list-none bg-[#4d4b4b] text-white px-4 py-2.5 text-[11px] flex justify-between items-center cursor-pointer font-['Poppins',sans-serif]">
                Berapa lama proses persetujuan reservasi?
                <svg class="faq-icon w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
            </summary>
            <div class="bg-white border border-[#555] border-t-0 p-5 text-[12px] leading-relaxed font-['Poppins',sans-serif]">
                Reservasi akan diproses oleh petugas setelah pengajuan dilakukan.
            </div>
        </details>

    </div>
</section>

<script>
    (function () {
        const facilities  = @json($facilitySuggestions ?? []);
        const form        = document.getElementById('availability-form');
        const searchInput = document.getElementById('q');
        const suggestBox  = document.getElementById('facility-suggestions');
        const dateInput   = document.getElementById('date');
        const startSelect = document.getElementById('start_time');
        const endSelect   = document.getElementById('end_time');
        const errorBox    = document.getElementById('availability-error');
        const submitBtn   = document.getElementById('availability-submit');

        /* ===== Cookie pencarian terakhir (preferensi "Ingat pencarian terakhir") ===== */
        const rememberSearch = document.documentElement.dataset.rememberSearch !== 'off';
        const LAST_SEARCH_COOKIE = 'chloe_last_search';

        function saveLastSearch(data) {
            if (!rememberSearch) return;
            const value = encodeURIComponent(JSON.stringify(data));
            document.cookie = `${LAST_SEARCH_COOKIE}=${value}; max-age=${60 * 60 * 24 * 30}; path=/; SameSite=Lax`;
        }

        function readLastSearch() {
            const match = document.cookie.split('; ').find(row => row.startsWith(LAST_SEARCH_COOKIE + '='));
            if (!match) return null;
            try { return JSON.parse(decodeURIComponent(match.split('=')[1])); } catch (e) { return null; }
        }

        const escapeHtml = (text) => String(text ?? '').replace(/[&<>"']/g, c => (
            { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]
        ));

        /* ================= 1. SARAN PENCARIAN ================= */
        let activeIndex = -1;
        let currentMatches = [];

        function renderSuggestions() {
            const keyword = searchInput.value.trim().toLowerCase();
            currentMatches = facilities
                .filter(f => !keyword || [f.name, f.type, f.location].join(' ').toLowerCase().includes(keyword))
                .slice(0, 8);
            activeIndex = -1;

            if (!currentMatches.length) {
                suggestBox.innerHTML = `<li class="px-4 py-3 text-sm text-gray-400">No matching facilities.</li>`;
            } else {
                suggestBox.innerHTML = currentMatches.map((f, i) => `
                    <li role="option" data-index="${i}"
                        class="suggestion px-4 py-2.5 cursor-pointer hover:bg-[#fcf1f0]">
                        <p class="text-sm font-semibold">${escapeHtml(f.name)}</p>
                        <p class="text-xs text-gray-500">${escapeHtml(f.type)}${f.location ? ' · ' + escapeHtml(f.location) : ''}</p>
                    </li>`).join('');
            }
            openSuggestions();
        }

        function openSuggestions()  { suggestBox.classList.remove('hidden'); searchInput.setAttribute('aria-expanded', 'true'); }
        function closeSuggestions() { suggestBox.classList.add('hidden');    searchInput.setAttribute('aria-expanded', 'false'); }

        function highlight(index) {
            suggestBox.querySelectorAll('.suggestion').forEach((li, i) => {
                li.classList.toggle('bg-[#fcf1f0]', i === index);
                if (i === index) li.scrollIntoView({ block: 'nearest' });
            });
        }

        const facilityIdInput = document.getElementById('facility_id');

        function chooseSuggestion(index) {
            const picked = currentMatches[index];
            if (!picked) return;
            searchInput.value = picked.name;
            facilityIdInput.value = picked.id;
            closeSuggestions();
            hideResult();
        }

        searchInput.addEventListener('focus', renderSuggestions);
        searchInput.addEventListener('input', () => {
            facilityIdInput.value = '';   // ketik ulang = pilihan sebelumnya batal
            hideResult();
            renderSuggestions();
        });
        searchInput.addEventListener('keydown', (e) => {
            if (suggestBox.classList.contains('hidden')) return;
            if (e.key === 'ArrowDown') { e.preventDefault(); activeIndex = Math.min(activeIndex + 1, currentMatches.length - 1); highlight(activeIndex); }
            if (e.key === 'ArrowUp')   { e.preventDefault(); activeIndex = Math.max(activeIndex - 1, 0); highlight(activeIndex); }
            if (e.key === 'Enter' && activeIndex >= 0) { e.preventDefault(); chooseSuggestion(activeIndex); }
            if (e.key === 'Escape') closeSuggestions();
        });
        // mousedown dipakai agar pilihan tercatat sebelum input kehilangan fokus
        suggestBox.addEventListener('mousedown', (e) => {
            const li = e.target.closest('.suggestion');
            if (li) { e.preventDefault(); chooseSuggestion(Number(li.dataset.index)); }
        });
        document.addEventListener('click', (e) => {
            if (!e.target.closest('#facility-suggestions') && e.target !== searchInput) closeSuggestions();
        });

        /* ================= 2. JAM MULAI / SELESAI ================= */
        function syncEndOptions() {
            const startVal = startSelect.value;
            Array.from(endSelect.options).forEach(option => {
                if (option.value === '') return;
                const disabled = startVal && option.value <= startVal;
                option.disabled = disabled;
                option.style.color = disabled ? 'gray' : 'white';
            });
            if (startVal && endSelect.value && endSelect.value <= startVal) {
                const firstAvailable = Array.from(endSelect.options).find(opt => !opt.disabled && opt.value !== '');
                endSelect.value = firstAvailable ? firstAvailable.value : '';
            }
        }

        function showError(message) {
            errorBox.textContent = message;
            errorBox.classList.remove('hidden');
        }
        function hideError() { errorBox.classList.add('hidden'); }

        startSelect.addEventListener('change', () => { syncEndOptions(); hideError(); });

        /* Isi otomatis dengan pencarian terakhir (jika preferensinya aktif dan tanggalnya belum lewat) */
        (function prefillLastSearch() {
            if (!rememberSearch) return;
            const last = readLastSearch();
            const today = new Date().toLocaleDateString('en-CA');
            if (!last || !facilities.some(f => String(f.id) === String(last.facility_id))) return;

            searchInput.value     = last.facility_name || '';
            facilityIdInput.value = last.facility_id;
            if (last.date && last.date >= today) dateInput.value = last.date;
            if (last.start_time) startSelect.value = last.start_time;
            syncEndOptions();
            if (last.end_time) endSelect.value = last.end_time;
        })();

        [dateInput, startSelect, endSelect].forEach(el => el.addEventListener('change', () => { hideError(); hideResult(); }));

        /* ================= 3. CEK KETERSEDIAAN ================= */
        const resultBox    = document.getElementById('availability-result');
        const resultIcon   = document.getElementById('result-icon');
        const resultTitle  = document.getElementById('result-title');
        const resultDetail = document.getElementById('result-detail');
        const resultAction = document.getElementById('result-action');

        const iconCheck = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>';
        const iconCross = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>';

        function hideResult() { resultBox.classList.add('hidden'); }

        function renderResult(data) {
            const f = data.results[0];
            const when = `${data.date_label}, ${data.start_time}–${data.end_time}`;

            if (f.status === 'available') {
                resultIcon.className  = 'w-9 h-9 rounded-full flex items-center justify-center shrink-0 bg-[#b5d3b0] text-[#3f6b3b]';
                resultIcon.innerHTML  = iconCheck;
                resultTitle.className = 'text-[17px] font-bold leading-tight text-[#b5d3b0]';
                resultTitle.textContent = 'Available';
                resultDetail.innerHTML = `<span class="font-semibold text-white">${escapeHtml(f.name)}</span> is free on ${escapeHtml(when)}.`
                    + (f.has_waiting ? '<br><span class="text-[#f3d8d5]">Another request is awaiting approval.</span>' : '');

                resultAction.innerHTML = f.can_book
                    ? `<a href="${escapeHtml(f.url)}" class="block w-full text-center rounded-[15px] bg-[#f3d8d5] hover:bg-white transition py-2 text-sm font-bold text-[#4b4848]">Book now →</a>`
                    : `<p class="text-[11.5px] text-[#FFF5F5]/85 bg-[#FFF5F5]/10 border border-[#FFF5F5]/20 rounded-[10px] px-3 py-2 leading-relaxed">Bookings must be made at least 2 days in advance. For this time slot, you can book dates from <span class="font-semibold text-white">${escapeHtml(data.earliest_booking_label)}</span> onwards.</p>`;
            } else {
                resultIcon.className  = 'w-9 h-9 rounded-full flex items-center justify-center shrink-0 bg-[#e07a72] text-[#7a2222]';
                resultIcon.innerHTML  = iconCross;
                resultTitle.className = 'text-[17px] font-bold leading-tight text-[#f2a39c]';
                resultTitle.textContent = 'Not Available';
                resultDetail.innerHTML = `<span class="font-semibold text-white">${escapeHtml(f.name)}</span>: ${escapeHtml(f.label)} (${escapeHtml(when)}). Try another date or time.`;
                resultAction.innerHTML = '';
            }

            resultBox.classList.remove('hidden', 'result-pop');
            void resultBox.offsetWidth;          // reset agar animasi diputar ulang
            resultBox.classList.add('result-pop');
        }

        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            closeSuggestions();
            hideError();
            hideResult();

            // Validasi sisi client
            const today = new Date().toLocaleDateString('en-CA'); // format YYYY-MM-DD sesuai zona waktu lokal
            // Jika nama diketik persis tanpa klik saran, cocokkan otomatis
            if (!facilityIdInput.value && searchInput.value.trim()) {
                const exact = facilities.find(f => f.name.toLowerCase() === searchInput.value.trim().toLowerCase());
                if (exact) facilityIdInput.value = exact.id;
            }
            if (!facilityIdInput.value)                return showError('Please pick a facility from the suggestions.');
            if (!dateInput.value)                      return showError('Please select a date.');
            if (dateInput.value < today)               return showError('The date cannot be earlier than today.');
            if (!startSelect.value || !endSelect.value) return showError('Please select a start and end time.');
            if (endSelect.value <= startSelect.value)  return showError('The end time must be later than the start time.');

            const params = new URLSearchParams({
                facility_id: facilityIdInput.value,
                date: dateInput.value,
                start_time: startSelect.value,
                end_time: endSelect.value,
            });

            submitBtn.disabled = true;
            submitBtn.textContent = 'Checking...';

            try {
                const response = await fetch(`${form.action}?${params}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                });
                const data = await response.json();

                if (!response.ok) {
                    // Pesan validasi dari server (422) atau error lain
                    const firstError = data.errors ? Object.values(data.errors).flat()[0] : null;
                    showError(firstError || data.message || 'Something went wrong. Please try again.');
                    return;
                }
                if (!data.results.length) {
                    showError('Facility not found or no longer active.');
                    return;
                }
                renderResult(data);

                saveLastSearch({
                    facility_id: facilityIdInput.value,
                    facility_name: searchInput.value,
                    date: dateInput.value,
                    start_time: startSelect.value,
                    end_time: endSelect.value,
                });
            } catch (err) {
                showError('Could not reach the server. Check your connection and try again.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Check';
            }
        });

    })();
</script>

<script>
    (function () {
        // Animasi mati jika perangkat meminta "kurangi gerakan" ATAU preferensi di Personalization = off
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches
            || document.documentElement.classList.contains('reduce-motion');

        /* ===== Muncul saat di-scroll ===== */
        const revealItems = document.querySelectorAll('.reveal');

        // Kartu dalam satu baris muncul bergantian
        document.querySelectorAll('.grid').forEach(grid => {
            grid.querySelectorAll(':scope > .reveal').forEach((card, i) => {
                card.style.transitionDelay = `${i * 120}ms`;
            });
        });

        if (reduceMotion || !('IntersectionObserver' in window)) {
            revealItems.forEach(el => el.classList.add('is-visible'));
        } else {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15 });
            revealItems.forEach(el => observer.observe(el));
        }

        /* ===== Buka-tutup halus untuk FAQ & "Read more" ===== */
        const accordions = document.querySelectorAll('details[data-accordion]');

        function animateOpen(details) {
            const content = details.querySelector(':scope > summary').nextElementSibling;
            details.open = true;
            if (reduceMotion) return;

            const height = content.scrollHeight;
            content.style.overflow = 'hidden';
            content.animate(
                [{ height: '0px', opacity: 0 }, { height: height + 'px', opacity: 1 }],
                { duration: 300, easing: 'ease-out' }
            ).onfinish = () => { content.style.overflow = ''; };
        }

        function animateClose(details) {
            const content = details.querySelector(':scope > summary').nextElementSibling;
            if (reduceMotion) { details.open = false; return; }

            const height = content.offsetHeight;
            content.style.overflow = 'hidden';
            content.animate(
                [{ height: height + 'px', opacity: 1 }, { height: '0px', opacity: 0 }],
                { duration: 250, easing: 'ease-in' }
            ).onfinish = () => {
                details.open = false;
                content.style.overflow = '';
            };
        }

        accordions.forEach(details => {
            details.querySelector(':scope > summary').addEventListener('click', (event) => {
                event.preventDefault();

                if (details.open) {
                    animateClose(details);
                    return;
                }

                // FAQ: hanya satu pertanyaan terbuka dalam satu waktu
                const group = details.dataset.accordion;
                if (group) {
                    accordions.forEach(other => {
                        if (other !== details && other.open && other.dataset.accordion === group) {
                            animateClose(other);
                        }
                    });
                }

                animateOpen(details);
            });
        });
    })();
</script>

@endsection