<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Summary Reports - Admin Chloe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@1,600&display=swap" rel="stylesheet">
</head>
<body class="bg-[#5c4f50] font-['Poppins',sans-serif] m-0 p-0 text-white min-h-screen flex flex-col">

    @include('admin.components.header')

    <div class="flex flex-1">
        <!-- Sidebar Navigation (Tidak diubah) -->
        <aside class="w-64 bg-[#e2b8bc] text-[#4b3839] flex flex-col p-0 m-0 space-y-0 shadow-md">
            <a href="{{ route('admin.dashboard') }}" class="px-6 py-4 hover:bg-[#ebd3d6] font-semibold transition text-center text-base">Dashboard</a>
            <a href="{{ route('admin.roles') }}" class="px-6 py-4 hover:bg-[#ebd3d6] font-semibold transition text-center text-base">Modify Roles</a>
            <a href="{{ route('admin.facilities') }}" class="px-6 py-4 hover:bg-[#ebd3d6] font-semibold transition text-center text-base">Facilities</a>
            <a href="{{ route('admin.summary') }}" class="px-6 py-4 font-bold bg-[#5c4f50] text-white rounded-l-2xl text-center text-base">Summary</a>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 p-6 flex flex-col gap-5">
            <!-- Header Title -->
            <div>
                <h1 class="text-3xl font-['Playfair_Display',serif] italic font-semibold text-[#fff5f5]">Recapitulation</h1>
                <p class="text-xs text-[#d1c2c2] mt-0.5">Ringkasan pemakaian fasilitas untuk laporan bulanan.</p>
            </div>

            <!-- Top Filter & Export Bar -->
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <select class="bg-white text-[#4b3839] text-xs px-4 py-2 rounded-full font-medium border-0 focus:outline-none shadow-sm cursor-pointer">
                        <option>September 2026</option>
                    </select>
                    <select class="bg-white text-[#4b3839] text-xs px-4 py-2 rounded-full font-medium border-0 focus:outline-none shadow-sm cursor-pointer">
                        <option>Semua unit</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button class="bg-white hover:bg-gray-50 text-[#4b3839] px-4 py-2 rounded-full text-xs font-semibold shadow-sm transition">
                        Ekspor Excel
                    </button>
                    <button class="bg-[#8c5259] hover:bg-[#78434a] text-white px-4 py-2 rounded-full text-xs font-semibold shadow-sm transition">
                        Download PDF
                    </button>
                </div>
            </div>

            <!-- Top Stat Cards Grid -->
            <div class="grid grid-cols-4 gap-4">
                <!-- Card 1 -->
                <div class="bg-white rounded-2xl p-4 shadow-sm text-[#4b3839] flex flex-col justify-between h-28">
                    <span class="text-xs font-semibold text-gray-600">Total pengajuan</span>
                    <div>
                        <div class="text-3xl font-['Playfair_Display',serif] font-bold leading-none">248</div>
                        <p class="text-[10px] text-gray-400 mt-1">naik 18 dari Agustus</p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white rounded-2xl p-4 shadow-sm text-[#4b3839] flex flex-col justify-between h-28">
                    <span class="text-xs font-semibold text-gray-600">Disetujui</span>
                    <div>
                        <div class="text-3xl font-['Playfair_Display',serif] font-bold leading-none">191</div>
                        <p class="text-[10px] text-gray-400 mt-1">77% dari pengajuan</p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white rounded-2xl p-4 shadow-sm text-[#4b3839] flex flex-col justify-between h-28">
                    <span class="text-xs font-semibold text-gray-600">Ditolak / bentrok</span>
                    <div>
                        <div class="text-3xl font-['Playfair_Display',serif] font-bold leading-none">37</div>
                        <p class="text-[10px] text-gray-400 mt-1">sebagian besar karena bentrok jam</p>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="bg-white rounded-2xl p-4 shadow-sm text-[#4b3839] flex flex-col justify-between h-28">
                    <span class="text-xs font-semibold text-gray-600">Rata-rata respons</span>
                    <div>
                        <div class="text-3xl font-['Playfair_Display',serif] font-bold leading-none">4,2 <span class="text-base font-normal font-sans">jam</span></div>
                        <p class="text-[10px] text-gray-400 mt-1">target maksimal 8 jam</p>
                    </div>
                </div>
            </div>

            <!-- Analytics Middle Grid -->
            <div class="grid grid-cols-5 gap-4">
                <!-- Left: Chart Pengajuan per minggu (Span 3) -->
                <div class="col-span-3 bg-white rounded-2xl p-5 shadow-sm text-[#4b3839] flex flex-col justify-between min-h-[300px]">
                    <div>
                        <h2 class="font-bold text-sm text-[#3b2b2c]">Pengajuan per minggu</h2>
                        <p class="text-[11px] text-gray-400 mb-6">September 2026 · disetujui dibanding ditolak</p>

                        <!-- Bar Chart Representation -->
                        <div class="relative h-40 flex items-end justify-between px-8 border-b border-gray-100 pb-2">
                            <!-- Background Grid Lines -->
                            <div class="absolute inset-0 flex flex-col justify-between pointer-events-none text-[10px] text-gray-300">
                                <div class="border-b border-gray-100 w-full flex justify-between"><span>60</span></div>
                                <div class="border-b border-gray-100 w-full flex justify-between"><span>40</span></div>
                                <div class="border-b border-gray-100 w-full flex justify-between"><span>20</span></div>
                                <div class="border-b border-gray-100 w-full flex justify-between"><span>0</span></div>
                            </div>

                            <!-- Week 1 -->
                            <div class="z-10 flex flex-col items-center gap-1">
                                <div class="flex items-end gap-1.5 h-32">
                                    <div class="bg-[#784e52] w-6 rounded-t-sm" style="height: 75%;"></div>
                                    <div class="bg-[#ebd3d6] w-6 rounded-t-sm" style="height: 30%;"></div>
                                </div>
                                <span class="text-[10px] text-gray-500 mt-2">Minggu 1</span>
                            </div>

                            <!-- Week 2 -->
                            <div class="z-10 flex flex-col items-center gap-1">
                                <div class="flex items-end gap-1.5 h-32">
                                    <div class="bg-[#784e52] w-6 rounded-t-sm" style="height: 90%;"></div>
                                    <div class="bg-[#ebd3d6] w-6 rounded-t-sm" style="height: 20%;"></div>
                                </div>
                                <span class="text-[10px] text-gray-500 mt-2">Minggu 2</span>
                            </div>

                            <!-- Week 3 -->
                            <div class="z-10 flex flex-col items-center gap-1">
                                <div class="flex items-end gap-1.5 h-32">
                                    <div class="bg-[#784e52] w-6 rounded-t-sm" style="height: 65%;"></div>
                                    <div class="bg-[#ebd3d6] w-6 rounded-t-sm" style="height: 25%;"></div>
                                </div>
                                <span class="text-[10px] text-gray-500 mt-2">Minggu 3</span>
                            </div>

                            <!-- Week 4 -->
                            <div class="z-10 flex flex-col items-center gap-1">
                                <div class="flex items-end gap-1.5 h-32">
                                    <div class="bg-[#784e52] w-6 rounded-t-sm" style="height: 45%;"></div>
                                    <div class="bg-[#ebd3d6] w-6 rounded-t-sm" style="height: 15%;"></div>
                                </div>
                                <span class="text-[10px] text-gray-500 mt-2">Minggu 4</span>
                            </div>
                        </div>

                        <!-- Legend -->
                        <div class="flex items-center gap-4 mt-4 text-[11px] text-gray-600">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#784e52]"></span>
                                <span>Disetujui</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#ebd3d6]"></span>
                                <span>Ditolak atau bentrok</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Fasilitas paling sering dipakai (Span 2) -->
                <div class="col-span-2 bg-white rounded-2xl p-5 shadow-sm text-[#4b3839] flex flex-col justify-between min-h-[300px]">
                    <div>
                        <h2 class="font-bold text-sm text-[#3b2b2c]">Fasilitas paling sering dipakai</h2>
                        <p class="text-[11px] text-gray-400 mb-5">Jumlah jam pemakaian bulan ini</p>

                        <div class="space-y-3.5 text-xs">
                            <!-- Item 1 -->
                            <div class="flex items-center justify-between gap-2">
                                <span class="w-36 text-[11px] font-medium text-gray-700 truncate">Aula Gedung Rektorat</span>
                                <div class="flex-1 bg-gray-100 rounded-full h-2">
                                    <div class="bg-[#784e52] h-2 rounded-full" style="width: 90%;"></div>
                                </div>
                                <span class="text-[11px] font-semibold text-gray-600 w-7 text-right">96j</span>
                            </div>

                            <!-- Item 2 -->
                            <div class="flex items-center justify-between gap-2">
                                <span class="w-36 text-[11px] font-medium text-gray-700 truncate">Ruang kelas A-304</span>
                                <div class="flex-1 bg-gray-100 rounded-full h-2">
                                    <div class="bg-[#784e52] h-2 rounded-full" style="width: 75%;"></div>
                                </div>
                                <span class="text-[11px] font-semibold text-gray-600 w-7 text-right">75j</span>
                            </div>

                            <!-- Item 3 -->
                            <div class="flex items-center justify-between gap-2">
                                <span class="w-36 text-[11px] font-medium text-gray-700 truncate">Lab Jaringan</span>
                                <div class="flex-1 bg-gray-100 rounded-full h-2">
                                    <div class="bg-[#784e52] h-2 rounded-full" style="width: 55%;"></div>
                                </div>
                                <span class="text-[11px] font-semibold text-gray-600 w-7 text-right">53j</span>
                            </div>

                            <!-- Item 4 -->
                            <div class="flex items-center justify-between gap-2">
                                <span class="w-36 text-[11px] font-medium text-gray-700 truncate">Lapangan Basket</span>
                                <div class="flex-1 bg-gray-100 rounded-full h-2">
                                    <div class="bg-[#784e52] h-2 rounded-full" style="width: 40%;"></div>
                                </div>
                                <span class="text-[11px] font-semibold text-gray-600 w-7 text-right">39j</span>
                            </div>

                            <!-- Item 5 -->
                            <div class="flex items-center justify-between gap-2">
                                <span class="w-36 text-[11px] font-medium text-gray-700 truncate">Ruang Rapat Dekanat</span>
                                <div class="flex-1 bg-gray-100 rounded-full h-2">
                                    <div class="bg-[#784e52] h-2 rounded-full" style="width: 25%;"></div>
                                </div>
                                <span class="text-[11px] font-semibold text-gray-600 w-7 text-right">23j</span>
                            </div>
                        </div>
                    </div>

                    <p class="text-[10px] text-gray-400 italic mt-4">
                        Aula hampir penuh tiap akhir pekan. Pertimbangkan membuka slot pagi hari kerja.
                    </p>
                </div>
            </div>

            <!-- Bottom Table Section: Rekap per unit -->
            <div class="bg-white rounded-2xl p-5 shadow-sm text-[#4b3839]">
                <h2 class="font-bold text-sm text-[#3b2b2c]">Rekap per unit</h2>
                <p class="text-[11px] text-gray-400 mb-4">Angka diambil dari booking berstatus selesai dan disetujui.</p>

                <div class="overflow-x-auto rounded-2xl border border-pink-100">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-[#ebd3d6] text-[#4b3839] font-semibold">
                            <tr>
                                <th class="p-3 pl-4">Unit</th>
                                <th class="p-3">Pengajuan</th>
                                <th class="p-3">Disetujui</th>
                                <th class="p-3">Ditolak</th>
                                <th class="p-3">Jam pakai</th>
                                <th class="p-3">Tingkat pakai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            <!-- Row 1 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-3 pl-4 font-medium text-gray-800">Gedung Rektorat</td>
                                <td class="p-3 text-gray-600">62</td>
                                <td class="p-3 text-gray-600">54</td>
                                <td class="p-3 text-gray-600">8</td>
                                <td class="p-3 text-gray-600">96</td>
                                <td class="p-3">
                                    <span class="bg-[#e2f0d9] text-[#2e6b27] px-2.5 py-0.5 rounded-full text-[10px] font-semibold">81%</span>
                                </td>
                            </tr>
                            <!-- Row 2 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-3 pl-4 font-medium text-gray-800">Gedung A</td>
                                <td class="p-3 text-gray-600">88</td>
                                <td class="p-3 text-gray-600">71</td>
                                <td class="p-3 text-gray-600">17</td>
                                <td class="p-3 text-gray-600">112</td>
                                <td class="p-3">
                                    <span class="bg-[#e2f0d9] text-[#2e6b27] px-2.5 py-0.5 rounded-full text-[10px] font-semibold">74%</span>
                                </td>
                            </tr>
                            <!-- Row 3 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-3 pl-4 font-medium text-gray-800">Gedung C</td>
                                <td class="p-3 text-gray-600">54</td>
                                <td class="p-3 text-gray-600">42</td>
                                <td class="p-3 text-gray-600">12</td>
                                <td class="p-3 text-gray-600">53</td>
                                <td class="p-3">
                                    <span class="bg-[#fff2cc] text-[#8a6d3b] px-2.5 py-0.5 rounded-full text-[10px] font-semibold">48%</span>
                                </td>
                            </tr>
                            <!-- Row 4 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-3 pl-4 font-medium text-gray-800">Area luar</td>
                                <td class="p-3 text-gray-600">44</td>
                                <td class="p-3 text-gray-600">24</td>
                                <td class="p-3 text-gray-600">20</td>
                                <td class="p-3 text-gray-600">39</td>
                                <td class="p-3">
                                    <span class="bg-[#fcebeb] text-[#d9534f] px-2.5 py-0.5 rounded-full text-[10px] font-semibold">31%</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer Caption -->
            <p class="text-[11px] text-[#d1c2c2] italic">
                Data ditarik 21 September 2026, 09.14 WIB. Angka pemakaian dihitung ulang tiap tengah malam.
            </p>
        </main>
    </div>

</body>
</html>