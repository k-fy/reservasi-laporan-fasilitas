<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Data Facility - Admin Chloe</title>
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
            <a href="{{ route('admin.facilities') }}" class="px-6 py-4 font-bold bg-[#5c4f50] text-white rounded-l-2xl text-center text-base">Facilities</a>
            <a href="{{ route('admin.summary') }}" class="px-6 py-4 hover:bg-[#ebd3d6] font-semibold transition text-center text-base">Summary</a>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 p-6 flex flex-col gap-6">
            <!-- Header Section -->
            <div>
                <h1 class="text-3xl font-['Playfair_Display',serif] italic font-semibold text-[#fff5f5]">Master Data Facilities</h1>
                <p class="text-xs text-[#d1c2c2] mt-0.5">Admins can bla bla bla</p>
            </div>

            <!-- Search, Filters, and Add Button Bar -->
            <div class="flex items-center justify-between gap-3">
                <div class="flex-1 max-w-md">
                    <input type="text" placeholder="" class="w-full bg-white rounded-full px-4 py-1.5 text-xs text-[#4b3839] focus:outline-none shadow-sm">
                </div>
                <div class="flex items-center gap-2">
                    <select class="bg-white text-[#4b3839] text-xs px-3 py-1.5 rounded-full font-medium border-0 focus:outline-none shadow-sm cursor-pointer">
                        <option>Type</option>
                    </select>
                    <select class="bg-white text-[#4b3839] text-xs px-3 py-1.5 rounded-full font-medium border-0 focus:outline-none shadow-sm cursor-pointer">
                        <option>Location</option>
                    </select>
                    <select class="bg-white text-[#4b3839] text-xs px-3 py-1.5 rounded-full font-medium border-0 focus:outline-none shadow-sm cursor-pointer">
                        <option>Status</option>
                    </select>
                    <button class="bg-[#fcebeb] hover:bg-[#f5d0d0] text-[#4b3839] px-4 py-1.5 rounded-full text-xs font-semibold shadow-sm transition flex items-center gap-1 ml-2">
                        + Add Facility
                    </button>
                </div>
            </div>

            <!-- Table Container -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg text-[#4b3839]">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="bg-[#ebd3d6] text-[#4b3839] font-semibold">
                        <tr>
                            <th class="py-3 px-4 w-8"></th>
                            <th class="py-3 px-4">Nama</th>
                            <th class="py-3 px-4">Lokasi</th>
                            <th class="py-3 px-4">Kapasitas</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Deskripsi</th>
                            <th class="py-3 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        <!-- Row 1 -->
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3 px-4">
                                <input type="checkbox" checked class="rounded border-gray-300 text-[#4b3839] focus:ring-0 accent-[#5c4f50] cursor-pointer">
                            </td>
                            <td class="py-3 px-4 font-medium text-gray-700">... Ruang kelas</td>
                            <td class="py-3 px-4 text-gray-600">Gedung A - Lt.3</td>
                            <td class="py-3 px-4 text-gray-600">40</td>
                            <td class="py-3 px-4">
                                <span class="bg-[#e2f0d9] text-[#2e6b27] px-2.5 py-0.5 rounded-md text-[10px] font-medium">Aktif</span>
                            </td>
                            <td class="py-3 px-4 text-gray-600">AC, proyektor</td>
                            <td class="py-3 px-4">
                                <a href="#" class="text-rose-500 hover:underline font-medium flex-inline items-center gap-1">
                                    ✏ Ubah
                                </a>
                                <span class="text-gray-300 mx-1">|</span>
                                <a href="#" class="text-gray-700 hover:underline font-medium">Nonaktifkan</a>
                            </td>
                        </tr>

                        <!-- Row 2 -->
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3 px-4">
                                <input type="checkbox" class="rounded border-gray-300 text-[#4b3839] focus:ring-0 accent-[#5c4f50] cursor-pointer">
                            </td>
                            <td class="py-3 px-4 font-medium text-gray-700">... Laboratorium</td>
                            <td class="py-3 px-4 text-gray-600">Gedung C - Lt.2</td>
                            <td class="py-3 px-4 text-gray-600">25</td>
                            <td class="py-3 px-4">
                                <span class="bg-[#fff2cc] text-[#8a6d3b] px-2.5 py-0.5 rounded-md text-[10px] font-medium">Dalam perbaikan</span>
                            </td>
                            <td class="py-3 px-4 text-gray-400">—</td>
                            <td class="py-3 px-4">
                                <a href="#" class="text-rose-500 hover:underline font-medium flex-inline items-center gap-1">
                                    ✏ Ubah
                                </a>
                                <span class="text-gray-300 mx-1">|</span>
                                <a href="#" class="text-gray-700 hover:underline font-medium">Nonaktifkan</a>
                            </td>
                        </tr>

                        <!-- Row 3 -->
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3 px-4">
                                <input type="checkbox" class="rounded border-gray-300 text-[#4b3839] focus:ring-0 accent-[#5c4f50] cursor-pointer">
                            </td>
                            <td class="py-3 px-4 font-medium text-gray-700">... Aula</td>
                            <td class="py-3 px-4 text-gray-600">Gedung Rektorat</td>
                            <td class="py-3 px-4 text-gray-600">300</td>
                            <td class="py-3 px-4">
                                <span class="bg-[#fcebeb] text-[#d9534f] px-2.5 py-0.5 rounded-md text-[10px] font-medium">Nonaktif</span>
                            </td>
                            <td class="py-3 px-4 text-gray-400">—</td>
                            <td class="py-3 px-4">
                                <a href="#" class="text-rose-500 hover:underline font-medium flex-inline items-center gap-1">
                                    ✏ Ubah
                                </a>
                                <span class="text-gray-300 mx-1">|</span>
                                <a href="#" class="text-emerald-600 hover:underline font-medium">Aktifkan</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Nonactivation Confirmation Section -->
            <div class="bg-[#ebd3d6] rounded-2xl p-6 text-[#4b3839] shadow-md">
                <h3 class="font-bold text-sm mb-3">Nonactivation Confirmation</h3>
                
                <div class="bg-[#f8eeee] border border-dashed border-[#b89b9e] rounded-xl p-8 mb-4 min-h-[80px]">
                </div>

                <div class="flex items-center gap-2 mb-3">
                    <button class="bg-[#fcebeb] text-gray-400 px-4 py-1.5 rounded-lg text-xs font-medium cursor-not-allowed">
                        Nonaktifkan
                    </button>
                    <button class="bg-[#6b5859] hover:bg-[#5a494a] text-white px-4 py-1.5 rounded-lg text-xs font-medium transition">
                        Batal
                    </button>
                </div>

                <p class="text-[11px] text-[#6b5859] italic">Tidak ada penghapusan permanen, hanya soft delete.</p>
            </div>
        </main>
    </div>

</body>
</html>