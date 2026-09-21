<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Chloe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@1,600&display=swap" rel="stylesheet">
</head>
<body class="bg-[#5c4f50] font-['Poppins',sans-serif] m-0 p-0 text-white min-h-screen flex flex-col">

    @include('admin.components.header')

    <div class="flex flex-1">
        <!-- Sidebar Navigation (Tidak diubah) -->
        <aside class="w-64 bg-[#e2b8bc] text-[#4b3839] flex flex-col p-0 m-0 space-y-0 shadow-md">
            <a href="{{ route('admin.dashboard') }}" class="px-6 py-4 font-bold bg-[#5c4f50] text-white rounded-l-2xl text-center text-base">Dashboard</a>
            <a href="{{ route('admin.roles') }}" class="px-6 py-4 hover:bg-[#ebd3d6] font-semibold transition text-center text-base">Modify Roles</a>
            <a href="{{ route('admin.facilities') }}" class="px-6 py-4 hover:bg-[#ebd3d6] font-semibold transition text-center text-base">Facilities</a>
            <a href="{{ route('admin.summary') }}" class="px-6 py-4 hover:bg-[#ebd3d6] font-semibold transition text-center text-base">Summary</a>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 p-6 flex flex-col justify-between">
            <div>
                <!-- Greeting Header -->
                <div class="mb-5">
                    <h1 class="text-3xl font-['Playfair_Display',serif] italic font-semibold text-[#fff5f5]">
                        Welcome back, {{ Auth::user()->name ?? 'Nadia' }}.
                    </h1>
                    <p class="text-xs text-[#d1c2c2] mt-1">12 permintaan booking menunggu keputusanmu hari ini.</p>
                </div>

                <!-- Top Stats Cards -->
                <div class="grid grid-cols-3 gap-4 mb-5">
                    <!-- Stat 1: Menunggu persetujuan -->
                    <div class="bg-white rounded-2xl p-4 shadow-sm text-[#4b3839] flex flex-col justify-between h-28">
                        <span class="text-xs font-semibold text-gray-600">Menunggu persetujuan</span>
                        <div>
                            <div class="text-3xl font-bold leading-none">12</div>
                            <p class="text-[10px] text-gray-400 mt-1">4 di antaranya untuk besok pagi</p>
                        </div>
                    </div>

                    <!-- Stat 2: Booking berjalan hari ini -->
                    <div class="bg-white rounded-2xl p-4 shadow-sm text-[#4b3839] flex flex-col justify-between h-28">
                        <span class="text-xs font-semibold text-gray-600">Booking berjalan hari ini</span>
                        <div>
                            <div class="text-2xl font-bold leading-none">
                                8 <span class="text-base font-normal text-gray-400">/ 14 slot</span>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1">2 ruang sedang dipakai sekarang</p>
                        </div>
                    </div>

                    <!-- Stat 3: Pemakaian fasilitas minggu ini -->
                    <div class="bg-white rounded-2xl p-4 shadow-sm text-[#4b3839] flex flex-col justify-between h-28">
                        <span class="text-xs font-semibold text-gray-600">Pemakaian fasilitas minggu ini</span>
                        <div>
                            <div class="text-2xl font-bold leading-none">64<span class="text-sm font-normal">%</span></div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5 mt-3">
                                <div class="bg-[#5c4f50] h-1.5 rounded-full" style="width: 64%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Section Grid -->
                <div class="grid grid-cols-3 gap-4">
                    <!-- Left Column: Permintaan Terbaru -->
                    <div class="col-span-2 bg-white rounded-2xl p-5 shadow-sm text-[#4b3839] flex flex-col justify-between min-h-[320px]">
                        <div>
                            <h2 class="font-bold text-sm text-[#3b2b2c]">Permintaan terbaru</h2>
                            <p class="text-[11px] text-gray-400 mb-4">Urut dari yang paling dekat jadwalnya.</p>

                            <div class="space-y-4">
                                <!-- Item 1 -->
                                <div class="flex items-center justify-between text-xs border-b border-gray-50 pb-3">
                                    <div class="w-16 text-[10px] text-gray-400 leading-tight">
                                        <div>Besok</div>
                                        <div>08.00</div>
                                    </div>
                                    <div class="flex-1 px-2">
                                        <div class="font-semibold text-gray-400">Aula Gedung Rektorat</div>
                                        <div class="text-[10px] text-gray-300">Himpunan Mahasiswa Informatika · Seminar Karier · 150 orang</div>
                                    </div>
                                    <div>
                                        <span class="bg-pink-50 text-pink-300 px-3 py-1 rounded-full text-[10px] font-medium">Ditolak</span>
                                    </div>
                                </div>

                                <!-- Item 2 -->
                                <div class="flex items-center justify-between text-xs border-b border-gray-50 pb-3">
                                    <div class="w-16 text-[10px] text-gray-400 leading-tight">
                                        <div>Besok</div>
                                        <div>13.00</div>
                                    </div>
                                    <div class="flex-1 px-2">
                                        <div class="font-semibold text-gray-400">Laboratorium Jaringan – Gedung C Lt.2</div>
                                        <div class="text-[10px] text-gray-300">UKM Robotik · Latihan rutin · 22 orang</div>
                                    </div>
                                    <div>
                                        <span class="bg-emerald-50 text-emerald-400 px-3 py-1 rounded-full text-[10px] font-medium">Disetujui</span>
                                    </div>
                                </div>

                                <!-- Item 3 -->
                                <div class="flex items-center justify-between text-xs border-b border-gray-50 pb-3">
                                    <div class="w-16 text-[10px] text-gray-500 font-medium leading-tight">
                                        <div>Kam</div>
                                        <div>09.30</div>
                                    </div>
                                    <div class="flex-1 px-2">
                                        <div class="font-bold text-[#3b2b2c]">Ruang kelas A-304</div>
                                        <div class="text-[10px] text-gray-500">Prodi Sistem Informasi · Kuliah pengganti · 40 orang</div>
                                        <div class="text-[10px] text-rose-400 mt-0.5">Bentrok dengan 1 booking lain pada jam yang sama.</div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button class="border border-gray-200 text-gray-600 px-3 py-1 rounded-lg text-[10px] font-medium hover:bg-gray-50">Lihat bentrok</button>
                                        <button class="bg-[#fcebeb] text-[#d9534f] px-3 py-1 rounded-lg text-[10px] font-medium hover:bg-red-100">Tolak</button>
                                    </div>
                                </div>

                                <!-- Item 4 -->
                                <div class="flex items-center justify-between text-xs">
                                    <div class="w-16 text-[10px] text-gray-500 font-medium leading-tight">
                                        <div>Jum</div>
                                        <div>15.00</div>
                                    </div>
                                    <div class="flex-1 px-2">
                                        <div class="font-bold text-[#3b2b2c]">Lapangan Basket</div>
                                        <div class="text-[10px] text-gray-500">BEM Fakultas Teknik · Turnamen internal · 90 orang</div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button class="bg-[#e2f0d9] text-[#2e6b27] px-4 py-1 rounded-lg text-[10px] font-medium hover:bg-green-200">Setujui</button>
                                        <button class="bg-[#fcebeb] text-[#d9534f] px-3 py-1 rounded-lg text-[10px] font-medium hover:bg-red-100">Tolak</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Jadwal Hari Ini -->
                    <div class="bg-[#4a3e3f] rounded-2xl p-5 shadow-sm text-white flex flex-col justify-between min-h-[320px]">
                        <div>
                            <h2 class="font-bold text-sm text-white">Jadwal hari ini</h2>
                            <p class="text-[11px] text-[#c4b5b6] mb-4">Selasa, 22 September · 6 agenda</p>

                            <div class="space-y-3.5 text-xs">
                                <div class="flex justify-between items-start">
                                    <span class="text-[11px] font-medium text-[#c4b5b6] w-12">07.30</span>
                                    <div class="flex-1 px-1">
                                        <div class="font-semibold text-sm">Ruang kelas A-201</div>
                                        <div class="text-[10px] text-[#c4b5b6]">Kuliah Basis Data · selesai</div>
                                    </div>
                                </div>

                                <div class="flex justify-between items-start">
                                    <span class="text-[11px] font-medium text-[#c4b5b6] w-12">09.00</span>
                                    <div class="flex-1 px-1">
                                        <div class="font-semibold text-sm">Aula Gedung Rektorat</div>
                                        <div class="text-[10px] text-[#c4b5b6]">Wisuda gladi bersih</div>
                                    </div>
                                    <span class="bg-[#e5989b] text-white text-[9px] px-2 py-0.5 rounded-full font-medium">Berlangsung</span>
                                </div>

                                <div class="flex justify-between items-start">
                                    <span class="text-[11px] font-medium text-[#c4b5b6] w-12">10.00</span>
                                    <div class="flex-1 px-1">
                                        <div class="font-semibold text-sm">Lab Multimedia</div>
                                        <div class="text-[10px] text-[#c4b5b6]">Workshop editing · UKM Film</div>
                                    </div>
                                    <span class="bg-[#e5989b] text-white text-[9px] px-2 py-0.5 rounded-full font-medium">Berlangsung</span>
                                </div>

                                <div class="flex justify-between items-start">
                                    <span class="text-[11px] font-medium text-[#c4b5b6] w-12">13.00</span>
                                    <div class="flex-1 px-1">
                                        <div class="font-semibold text-sm">Ruang Rapat Dekanat</div>
                                        <div class="text-[10px] text-[#c4b5b6]">Rapat kurikulum</div>
                                    </div>
                                </div>

                                <div class="flex justify-between items-start">
                                    <span class="text-[11px] font-medium text-[#c4b5b6] w-12">15.30</span>
                                    <div class="flex-1 px-1">
                                        <div class="font-semibold text-sm">Lapangan Basket</div>
                                        <div class="text-[10px] text-[#c4b5b6]">Latihan UKM Basket</div>
                                    </div>
                                </div>

                                <div class="flex justify-between items-start">
                                    <span class="text-[11px] font-medium text-[#c4b5b6] w-12">18.00</span>
                                    <div class="flex-1 px-1">
                                        <div class="font-semibold text-sm">Aula Gedung B</div>
                                        <div class="text-[10px] text-[#c4b5b6]">Buka bersama HIMASI</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Action Buttons -->
            <div class="mt-6 flex justify-center space-x-3">
                <a href="{{ route('admin.roles') }}" class="bg-[#a8caa4] text-[#223620] px-5 py-2 rounded-full text-xs font-semibold shadow hover:opacity-90 transition flex items-center">
                    ✓ &nbsp; Quick Accept Roles
                </a>
                <a href="{{ route('admin.roles') }}" class="bg-[#4b3839] text-white px-5 py-2 rounded-full text-xs font-semibold shadow hover:bg-[#3b2b2c] transition flex items-center">
                    + &nbsp; Add New Officer
                </a>
                <a href="{{ route('admin.facilities') }}" class="bg-[#4b3839] text-white px-5 py-2 rounded-full text-xs font-semibold shadow hover:bg-[#3b2b2c] transition flex items-center">
                    + &nbsp; Add New Facility
                </a>
                <a href="{{ route('admin.summary') }}" class="bg-[#4b3839] text-white px-5 py-2 rounded-full text-xs font-semibold shadow hover:bg-[#3b2b2c] transition flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1 fill-current" viewBox="0 0 20 20">
                        <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"/>
                    </svg> Download PDF
                </a>
            </div>
        </main>
    </div>

</body>
</html>