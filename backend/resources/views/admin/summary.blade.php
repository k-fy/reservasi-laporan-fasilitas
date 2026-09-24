<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recap - Admin Chloe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@1,600&display=swap" rel="stylesheet">
</head>
<body class="bg-[#5c4f50] font-['Poppins',sans-serif] m-0 p-0 text-white min-h-screen flex flex-col">

    @include('admin.components.header')

    <div class="flex flex-1">
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-[#e2b8bc] text-[#4b3839] flex flex-col p-0 m-0 space-y-0 shadow-md">
            <a href="{{ route('admin.roles') }}" class="px-6 py-4 hover:bg-[#ebd3d6] font-semibold transition text-center text-base">Accounts</a>
            <a href="{{ route('admin.facilities') }}" class="px-6 py-4 hover:bg-[#ebd3d6] font-semibold transition text-center text-base">Facilities</a>
            <a href="{{ route('admin.summary') }}" class="px-6 py-4 font-bold bg-[#5c4f50] text-white rounded-l-2xl text-center text-base">Recap</a>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 p-6 flex flex-col">
            <div class="mb-4">
                <h1 class="text-3xl font-['Playfair_Display',serif] italic font-semibold text-[#fff5f5]">Recap</h1>
                <p class="text-xs text-[#d1c2c2] mt-0.5">Ringkasan pemakaian dan riwayat kerusakan fasilitas per lokasi.</p>
            </div>

            <!-- Tab Buttons Header -->
            <div class="flex space-x-2 items-end mb-[-1px] z-10">
                <button onclick="switchTab('occupancy')" id="tab-occupancy" class="px-6 py-2.5 rounded-t-2xl bg-white text-[#4b3839] font-bold text-xs shadow-sm transition">
                    Okupansi Fasilitas per Lokasi
                </button>
                <button onclick="switchTab('damage')" id="tab-damage" class="px-6 py-2.5 rounded-t-2xl bg-[#ebd3d6] text-[#4b3839] font-medium text-xs shadow-sm transition hover:bg-[#e0c4c7]">
                    Frekuensi Kerusakan per Lokasi
                </button>
            </div>

            <!-- Main White Container -->
            <div class="flex-1 bg-white rounded-b-2xl rounded-tr-2xl p-6 shadow-lg text-[#4b3839] min-h-[450px] flex flex-col justify-between">
                
                <div>
                    <!-- Filter Bar -->
                    <div class="flex gap-3 mb-5">
                        <div class="relative flex-1">
                            <svg class="w-4 h-4 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" placeholder="Cari nama lokasi atau fasilitas..." class="w-full pl-9 pr-4 py-1.5 text-xs border border-gray-200 rounded-xl bg-white focus:outline-none focus:border-[#d98b92]">
                        </div>
                        <select class="px-3 py-1.5 text-xs border border-gray-200 rounded-xl bg-white text-gray-600 focus:outline-none cursor-pointer">
                            <option value="">Semua Bulan</option>
                            <option value="09">September 2026</option>
                            <option value="08">Agustus 2026</option>
                        </select>
                    </div>

                    <!-- TAB 1: REKAP OKUPANSI FASILITAS PER LOKASI -->
                    <div id="content-occupancy" class="tab-content">
                        <div class="overflow-x-auto rounded-2xl border border-pink-100 mb-4">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-[#ebd3d6] text-[#4b3839] font-semibold">
                                    <tr>
                                        <th class="p-3 pl-4">Lokasi Gedung</th>
                                        <th class="p-3">Total Fasilitas</th>
                                        <th class="p-3">Total Pemakaian</th>
                                        <th class="p-3">Total Jam Terakai</th>
                                        <th class="p-3">Tingkat Okupansi</th>
                                        <th class="p-3">Status Pemakaian</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="p-3 pl-4 font-medium">Gedung Rektorat</td>
                                        <td class="p-3 text-gray-500">12 Fasilitas</td>
                                        <td class="p-3">62 kali</td>
                                        <td class="p-3">186 Jam</td>
                                        <td class="p-3 font-semibold text-emerald-700">81%</td>
                                        <td class="p-3"><span class="bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full text-[10px] font-medium">Sangat Tinggi</span></td>
                                    </tr>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="p-3 pl-4 font-medium">Gedung A - Fakultas Sains</td>
                                        <td class="p-3 text-gray-500">18 Fasilitas</td>
                                        <td class="p-3">88 kali</td>
                                        <td class="p-3">220 Jam</td>
                                        <td class="p-3 font-semibold text-emerald-700">74%</td>
                                        <td class="p-3"><span class="bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full text-[10px] font-medium">Tinggi</span></td>
                                    </tr>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="p-3 pl-4 font-medium">Gedung C - Laboratorium</td>
                                        <td class="p-3 text-gray-500">10 Fasilitas</td>
                                        <td class="p-3">54 kali</td>
                                        <td class="p-3">125 Jam</td>
                                        <td class="p-3 font-semibold text-amber-700">48%</td>
                                        <td class="p-3"><span class="bg-amber-100 text-amber-800 px-2.5 py-0.5 rounded-full text-[10px] font-medium">Sedang</span></td>
                                    </tr>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="p-3 pl-4 font-medium">Kawasan Lapangan Luar</td>
                                        <td class="p-3 text-gray-500">5 Fasilitas</td>
                                        <td class="p-3">24 kali</td>
                                        <td class="p-3">60 Jam</td>
                                        <td class="p-3 font-semibold text-rose-700">31%</td>
                                        <td class="p-3"><span class="bg-rose-100 text-rose-800 px-2.5 py-0.5 rounded-full text-[10px] font-medium">Rendah</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 2: FREKUENSI KERUSAKAN FASILITAS PER LOKASI -->
                    <div id="content-damage" class="tab-content hidden">
                        <div class="overflow-x-auto rounded-2xl border border-pink-100 mb-4">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-[#ebd3d6] text-[#4b3839] font-semibold">
                                    <tr>
                                        <th class="p-3 pl-4">Lokasi Gedung</th>
                                        <th class="p-3">Total Laporan Kerusakan</th>
                                        <th class="p-3">Sedang Diperbaiki</th>
                                        <th class="p-3">Selesai Diperbaiki</th>
                                        <th class="p-3">Tingkat Kerusakan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="p-3 pl-4 font-medium">Gedung A - Fakultas Sains</td>
                                        <td class="p-3 font-semibold">14 Insiden</td>
                                        <td class="p-3 text-amber-600 font-medium">3 Fasilitas</td>
                                        <td class="p-3 text-emerald-600 font-medium">11 Fasilitas</td>
                                        <td class="p-3"><span class="bg-rose-100 text-rose-800 px-2.5 py-0.5 rounded-full text-[10px] font-medium">Tinggi</span></td>
                                    </tr>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="p-3 pl-4 font-medium">Gedung C - Laboratorium</td>
                                        <td class="p-3 font-semibold">8 Insiden</td>
                                        <td class="p-3 text-amber-600 font-medium">1 Fasilitas</td>
                                        <td class="p-3 text-emerald-600 font-medium">7 Fasilitas</td>
                                        <td class="p-3"><span class="bg-amber-100 text-amber-800 px-2.5 py-0.5 rounded-full text-[10px] font-medium">Sedang</span></td>
                                    </tr>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="p-3 pl-4 font-medium">Gedung Rektorat</td>
                                        <td class="p-3 font-semibold">2 Insiden</td>
                                        <td class="p-3 text-amber-600 font-medium">0 Fasilitas</td>
                                        <td class="p-3 text-emerald-600 font-medium">2 Fasilitas</td>
                                        <td class="p-3"><span class="bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full text-[10px] font-medium">Rendah</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- OPSI DOWNLOAD DATA DI BAGIAN BAWAH CONTAINER -->
                <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-[11px] text-gray-400 italic">Unduh laporan rekapitulasi data terbaru.</span>
                    
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold text-[#4b3839] mr-1">Unduh sebagai:</span>
                        
                        <!-- CSV Button -->
                        <a href="{{ route('admin.summary.export', ['type' => 'csv']) }}" class="px-3.5 py-1.5 rounded-xl border border-gray-200 text-[#4b3839] text-xs font-medium hover:bg-gray-50 transition flex items-center gap-1.5 shadow-sm">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            CSV
                        </a>

                        <!-- PDF Button -->
                        <a href="{{ route('admin.summary.export', ['type' => 'pdf']) }}" class="px-3.5 py-1.5 rounded-xl border border-gray-200 text-[#4b3839] text-xs font-medium hover:bg-gray-50 transition flex items-center gap-1.5 shadow-sm">
                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            PDF
                        </a>

                        <!-- Excel Button -->
                        <a href="{{ route('admin.summary.export', ['type' => 'excel']) }}" class="px-3.5 py-1.5 rounded-xl bg-[#785b5d] text-white text-xs font-medium hover:bg-[#5c4f50] transition flex items-center gap-1.5 shadow-sm">
                            <svg class="w-3.5 h-3.5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Excel
                        </a>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- Script Tab Switching -->
    <script>
        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            
            document.querySelectorAll('[id^="tab-"]').forEach(el => {
                el.classList.remove('bg-white', 'font-bold');
                el.classList.add('bg-[#ebd3d6]', 'font-medium');
            });
            
            document.getElementById('content-' + tabName).classList.remove('hidden');
            
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('bg-[#ebd3d6]', 'font-medium');
            activeTab.classList.add('bg-white', 'font-bold');
        }
    </script>

</body>
</html>