<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Roles - Admin Chloe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@1,600&display=swap" rel="stylesheet">
</head>
<body class="bg-[#5c4f50] font-['Poppins',sans-serif] m-0 p-0 text-white min-h-screen flex flex-col">

    @include('admin.components.header')

    <div class="flex flex-1">
        <!-- Sidebar Navigation (Tidak Diubah) -->
        <aside class="w-64 bg-[#e2b8bc] text-[#4b3839] flex flex-col p-0 m-0 space-y-0 shadow-md">
            <a href="{{ route('admin.dashboard') }}" class="px-6 py-4 hover:bg-[#ebd3d6] font-semibold transition text-center text-base">Dashboard</a>
            <a href="{{ route('admin.roles') }}" class="px-6 py-4 font-bold bg-[#5c4f50] text-white rounded-l-2xl text-center text-base">Modify Roles</a>
            <a href="{{ route('admin.facilities') }}" class="px-6 py-4 hover:bg-[#ebd3d6] font-semibold transition text-center text-base">Facilities</a>
            <a href="{{ route('admin.summary') }}" class="px-6 py-4 hover:bg-[#ebd3d6] font-semibold transition text-center text-base">Summary</a>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 p-6 flex flex-col">
            <div class="mb-4">
                <h1 class="text-3xl font-['Playfair_Display',serif] italic font-semibold text-[#fff5f5]">Modify Roles</h1>
                <p class="text-xs text-[#d1c2c2] mt-0.5">Atur siapa yang boleh memesan, menyetujui, dan mengelola data.</p>
            </div>

            <!-- Tab Buttons -->
            <div class="flex space-x-2 items-end mb-[-1px] z-10">
                <button onclick="switchTab('manage')" id="tab-manage" class="px-6 py-2.5 rounded-t-2xl bg-white text-[#4b3839] font-bold text-xs shadow-sm transition">
                    Manage Accounts
                </button>
                <button onclick="switchTab('add')" id="tab-add" class="px-6 py-2.5 rounded-t-2xl bg-[#ebd3d6] text-[#4b3839] font-medium text-xs shadow-sm transition hover:bg-[#e0c4c7]">
                    Add Accounts
                </button>
                <button onclick="switchTab('verification')" id="tab-verification" class="px-6 py-2.5 rounded-t-2xl bg-[#ebd3d6] text-[#4b3839] font-medium text-xs shadow-sm transition hover:bg-[#e0c4c7] flex items-center gap-2">
                    <span class="w-4 h-4 rounded-full bg-[#d98b92] text-white text-[10px] flex items-center justify-center font-bold">5</span>
                    Registration Verification
                </button>
            </div>

            <!-- Container Utama -->
            <div class="flex-1 bg-white rounded-b-2xl rounded-tr-2xl p-6 shadow-lg text-[#4b3839] min-h-[450px]">
                
                <!-- TAB 1: MANAGE ACCOUNTS -->
                <div id="content-manage" class="tab-content">
                    <!-- Search & Filter Bar -->
                    <div class="flex gap-3 mb-5">
                        <div class="relative flex-1">
                            <svg class="w-4 h-4 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" placeholder="Cari nama, email, atau NIM/NIP" class="w-full pl-9 pr-4 py-1.5 text-xs border border-gray-200 rounded-xl bg-white focus:outline-none focus:border-[#d98b92]">
                        </div>
                        <select class="px-3 py-1.5 text-xs border border-gray-200 rounded-xl bg-white text-gray-600 focus:outline-none">
                            <option>Semua role</option>
                        </select>
                        <select class="px-3 py-1.5 text-xs border border-gray-200 rounded-xl bg-white text-gray-600 focus:outline-none">
                            <option>Semua status</option>
                        </select>
                    </div>

                    <!-- Table Container -->
                    <div class="overflow-x-auto rounded-2xl border border-pink-100 mb-4">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-[#ebd3d6] text-[#4b3839] font-semibold">
                                <tr>
                                    <th class="p-3 pl-4">Nama</th>
                                    <th class="p-3">Email kampus</th>
                                    <th class="p-3">Unit</th>
                                    <th class="p-3">Role</th>
                                    <th class="p-3">Status</th>
                                    <th class="p-3">Terakhir aktif</th>
                                    <th class="p-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                <!-- Row 1 -->
                                <tr>
                                    <td class="p-3 pl-4 font-medium">Nadia Ramadhani</td>
                                    <td class="p-3 text-gray-500">nadia.r@kampus.ac.id</td>
                                    <td class="p-3">BAUK</td>
                                    <td class="p-3">
                                        <select class="bg-gray-100 border border-gray-200 rounded-lg px-2 py-1 text-[11px] font-medium text-gray-700">
                                            <option>Admin</option>
                                        </select>
                                    </td>
                                    <td class="p-3"><span class="bg-[#e2f0d9] text-[#2e6b27] px-2.5 py-0.5 rounded-full text-[10px] font-medium">Aktif</span></td>
                                    <td class="p-3 text-gray-500">Baru saja</td>
                                    <td class="p-3 text-gray-400 italic">Akun kamu</td>
                                </tr>
                                <!-- Row 2 -->
                                <tr>
                                    <td class="p-3 pl-4 font-medium">Bagus Tri Prakoso</td>
                                    <td class="p-3 text-gray-500">bagus.tp@kampus.ac.id</td>
                                    <td class="p-3">Gedung Rektorat</td>
                                    <td class="p-3">
                                        <select class="bg-gray-100 border border-gray-200 rounded-lg px-2 py-1 text-[11px] font-medium text-gray-700">
                                            <option>Petugas</option>
                                        </select>
                                    </td>
                                    <td class="p-3"><span class="bg-[#e2f0d9] text-[#2e6b27] px-2.5 py-0.5 rounded-full text-[10px] font-medium">Aktif</span></td>
                                    <td class="p-3 text-gray-500">12 menit lalu</td>
                                    <td class="p-3">
                                        <a href="#" class="text-rose-500 hover:underline font-medium">Ubah</a>
                                        <span class="text-gray-300 mx-1">|</span>
                                        <a href="#" class="text-gray-500 hover:underline">Tangguhkan</a>
                                    </td>
                                </tr>
                                <!-- Row 3 -->
                                <tr>
                                    <td class="p-3 pl-4 font-medium">Sekar Ayu Larasati</td>
                                    <td class="p-3 text-gray-500">sekar.al@kampus.ac.id</td>
                                    <td class="p-3">Gedung C</td>
                                    <td class="p-3">
                                        <select class="bg-gray-100 border border-gray-200 rounded-lg px-2 py-1 text-[11px] font-medium text-gray-700">
                                            <option>Petugas</option>
                                        </select>
                                    </td>
                                    <td class="p-3"><span class="bg-[#e2f0d9] text-[#2e6b27] px-2.5 py-0.5 rounded-full text-[10px] font-medium">Aktif</span></td>
                                    <td class="p-3 text-gray-500">1 jam lalu</td>
                                    <td class="p-3">
                                        <a href="#" class="text-rose-500 hover:underline font-medium">Ubah</a>
                                        <span class="text-gray-300 mx-1">|</span>
                                        <a href="#" class="text-gray-500 hover:underline">Tangguhkan</a>
                                    </td>
                                </tr>
                                <!-- Row 4 -->
                                <tr>
                                    <td class="p-3 pl-4 font-medium">Rizky Aditya</td>
                                    <td class="p-3 text-gray-500">rizky.a@kampus.ac.id</td>
                                    <td class="p-3">HIMASI</td>
                                    <td class="p-3">
                                        <select class="bg-gray-100 border border-gray-200 rounded-lg px-2 py-1 text-[11px] font-medium text-gray-700">
                                            <option>Peminjam</option>
                                        </select>
                                    </td>
                                    <td class="p-3"><span class="bg-[#e2f0d9] text-[#2e6b27] px-2.5 py-0.5 rounded-full text-[10px] font-medium">Aktif</span></td>
                                    <td class="p-3 text-gray-500">Kemarin</td>
                                    <td class="p-3">
                                        <a href="#" class="text-rose-500 hover:underline font-medium">Ubah</a>
                                        <span class="text-gray-300 mx-1">|</span>
                                        <a href="#" class="text-gray-500 hover:underline">Tangguhkan</a>
                                    </td>
                                </tr>
                                <!-- Row 5 -->
                                <tr>
                                    <td class="p-3 pl-4 font-medium">Made Widiarta</td>
                                    <td class="p-3 text-gray-500">made.w@kampus.ac.id</td>
                                    <td class="p-3">UKM Robotik</td>
                                    <td class="p-3">
                                        <select class="bg-gray-100 border border-gray-200 rounded-lg px-2 py-1 text-[11px] font-medium text-gray-700">
                                            <option>Peminjam</option>
                                        </select>
                                    </td>
                                    <td class="p-3"><span class="bg-pink-100 text-pink-500 px-2.5 py-0.5 rounded-full text-[10px] font-medium">Ditangguhkan</span></td>
                                    <td class="p-3 text-gray-500">3 hari lalu</td>
                                    <td class="p-3">
                                        <a href="#" class="text-rose-500 hover:underline font-medium">Ubah</a>
                                        <span class="text-gray-300 mx-1">|</span>
                                        <a href="#" class="text-rose-600 font-semibold hover:underline">Aktifkan</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="text-[11px] text-gray-500 italic">Perubahan role berlaku saat pengguna login berikutnya, dan tercatat di log aktivitas.</p>
                </div>

                <!-- TAB 2: ADD ACCOUNTS -->
                <div id="content-add" class="tab-content hidden">
                    <h2 class="font-bold text-sm text-[#3b2b2c]">Tambah akun petugas atau admin</h2>
                    <p class="text-xs text-gray-400 mb-6">Undangan dikirim ke email kampus. Kata sandi dibuat sendiri oleh penerima saat pertama kali masuk.</p>

                    <form class="space-y-4 max-w-3xl">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">Nama lengkap</label>
                                <input type="text" placeholder="Contoh: Bagus Tri Prakoso" class="w-full bg-[#f8f4f4] border-0 rounded-xl p-2.5 text-xs text-gray-700 focus:ring-1 focus:ring-pink-300 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">NIM / NIP</label>
                                <input type="text" placeholder="19870412 201004 1 002" class="w-full bg-[#f8f4f4] border-0 rounded-xl p-2.5 text-xs text-gray-700 focus:ring-1 focus:ring-pink-300 focus:outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">Email kampus</label>
                                <input type="email" placeholder="nama@kampus.ac.id" class="w-full bg-[#f8f4f4] border-0 rounded-xl p-2.5 text-xs text-gray-700 focus:ring-1 focus:ring-pink-300 focus:outline-none">
                                <span class="text-[10px] text-gray-400 mt-0.5 block">Hanya domain kampus.ac.id yang diterima.</span>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">Unit penempatan</label>
                                <select class="w-full bg-[#f8f4f4] border-0 rounded-xl p-2.5 text-xs text-gray-700 focus:ring-1 focus:ring-pink-300 focus:outline-none">
                                    <option>Gedung Rektorat</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1 text-gray-700">Role</label>
                            <select class="w-full bg-[#f8f4f4] border-0 rounded-xl p-2.5 text-xs text-gray-700 focus:ring-1 focus:ring-pink-300 focus:outline-none">
                                <option>Petugas — mengelola jadwal dan menyetujui booking di unitnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1 text-gray-700">Catatan untuk penerima <span class="text-gray-400 font-normal">(opsional)</span></label>
                            <textarea rows="3" placeholder="Misalnya: pegang jadwal Aula mulai 1 Oktober." class="w-full bg-[#f8f4f4] border-0 rounded-xl p-2.5 text-xs text-gray-700 focus:ring-1 focus:ring-pink-300 focus:outline-none resize-none"></textarea>
                        </div>

                        <div class="flex space-x-2 pt-2">
                            <button type="button" class="bg-[#785b5d] text-white px-5 py-2 rounded-xl text-xs font-medium hover:bg-[#5c4f50] transition">Kirim undangan</button>
                            <button type="reset" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-xl text-xs font-medium hover:bg-gray-200 transition">Kosongkan isian</button>
                        </div>
                    </form>
                </div>

                <!-- TAB 3: REGISTRATION VERIFICATION -->
                <div id="content-verification" class="tab-content hidden">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="font-bold text-sm text-[#3b2b2c]">Pendaftar menunggu verifikasi</h2>
                            <p class="text-xs text-gray-400">Cocokkan nama pada kartu mahasiswa dengan data pendaftaran sebelum menyetujui.</p>
                        </div>
                        <button class="border border-gray-200 text-gray-600 px-4 py-1.5 rounded-xl text-xs font-medium hover:bg-gray-50 transition">Setujui semua yang cocok</button>
                    </div>

                    <div class="grid grid-cols-4 gap-4">
                        <!-- Card 1 -->
                        <div class="bg-[#fcf7f7] border border-pink-50 rounded-2xl p-4 flex flex-col justify-between">
                            <div class="space-y-1">
                                <h3 class="font-bold text-xs text-[#3b2b2c]">Alya Kusuma</h3>
                                <p class="text-[11px] text-gray-400 pb-2">alya.k@kampus.ac.id</p>
                                <div class="text-[11px] grid grid-cols-3 gap-1">
                                    <span class="text-gray-400">NIM</span>
                                    <span class="col-span-2 font-medium">21.11.4831</span>
                                    <span class="text-gray-400">Unit</span>
                                    <span class="col-span-2 font-medium">HIMASI</span>
                                    <span class="text-gray-400">Diminta</span>
                                    <span class="col-span-2 font-medium">Peminjam</span>
                                </div>
                                <div class="pt-2">
                                    <a href="#" class="text-[11px] text-rose-400 flex items-center gap-1 hover:underline">
                                        📎 KTM_alya.jpg
                                    </a>
                                </div>
                            </div>
                            <div class="flex space-x-2 mt-4">
                                <button class="bg-[#e2f0d9] text-[#2e6b27] px-3 py-1 rounded-lg text-[10px] font-medium hover:bg-green-200 transition">Verifikasi</button>
                                <button class="text-rose-400 px-2 py-1 text-[10px] font-medium hover:underline">Tolak</button>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="bg-[#fcf7f7] border border-pink-50 rounded-2xl p-4 flex flex-col justify-between">
                            <div class="space-y-1">
                                <h3 class="font-bold text-xs text-[#3b2b2c]">Dimas Prasetyo</h3>
                                <p class="text-[11px] text-gray-400 pb-2">dimas.p@kampus.ac.id</p>
                                <div class="text-[11px] grid grid-cols-3 gap-1">
                                    <span class="text-gray-400">NIM</span>
                                    <span class="col-span-2 font-medium">21.11.5027</span>
                                    <span class="text-gray-400">Unit</span>
                                    <span class="col-span-2 font-medium">UKM Basket</span>
                                    <span class="text-gray-400">Diminta</span>
                                    <span class="col-span-2 font-medium">Peminjam</span>
                                </div>
                                <div class="pt-2">
                                    <a href="#" class="text-[11px] text-rose-400 flex items-center gap-1 hover:underline">
                                        📎 KTM_dimas.jpg
                                    </a>
                                </div>
                            </div>
                            <div class="flex space-x-2 mt-4">
                                <button class="bg-[#e2f0d9] text-[#2e6b27] px-3 py-1 rounded-lg text-[10px] font-medium hover:bg-green-200 transition">Verifikasi</button>
                                <button class="text-rose-400 px-2 py-1 text-[10px] font-medium hover:underline">Tolak</button>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="bg-[#fcf7f7] border border-pink-50 rounded-2xl p-4 flex flex-col justify-between">
                            <div class="space-y-1">
                                <h3 class="font-bold text-xs text-[#3b2b2c]">Putri Handayani</h3>
                                <p class="text-[11px] text-gray-400 pb-2">putri.h@kampus.ac.id</p>
                                <div class="text-[11px] grid grid-cols-3 gap-1">
                                    <span class="text-gray-400">NIP</span>
                                    <span class="col-span-2 font-medium">19900218 201503 2 001</span>
                                    <span class="text-gray-400">Unit</span>
                                    <span class="col-span-2 font-medium">Gedung A</span>
                                    <span class="text-gray-400">Diminta</span>
                                    <span class="col-span-2 font-medium">Petugas</span>
                                </div>
                                <div class="pt-2">
                                    <a href="#" class="text-[11px] text-rose-400 flex items-center gap-1 hover:underline">
                                        📎 SK_penugasan.pdf
                                    </a>
                                </div>
                            </div>
                            <div class="flex space-x-2 mt-4">
                                <button class="bg-[#e2f0d9] text-[#2e6b27] px-3 py-1 rounded-lg text-[10px] font-medium hover:bg-green-200 transition">Verifikasi</button>
                                <button class="text-rose-400 px-2 py-1 text-[10px] font-medium hover:underline">Tolak</button>
                            </div>
                        </div>

                        <!-- Card 4 (Warning State) -->
                        <div class="bg-[#fcf7f7] border border-pink-50 rounded-2xl p-4 flex flex-col justify-between">
                            <div class="space-y-1">
                                <h3 class="font-bold text-xs text-[#3b2b2c]">Fajar Nugroho</h3>
                                <p class="text-[11px] text-gray-400 pb-2">fajar.n@kampus.ac.id</p>
                                <div class="text-[11px] grid grid-cols-3 gap-1">
                                    <span class="text-gray-400">NIM</span>
                                    <span class="col-span-2 font-medium">22.11.1190</span>
                                    <span class="text-gray-400">Unit</span>
                                    <span class="col-span-2 font-medium">UKM Film</span>
                                    <span class="text-gray-400">Diminta</span>
                                    <span class="col-span-2 font-medium">Peminjam</span>
                                </div>
                                <div class="pt-2">
                                    <p class="text-[10px] text-rose-500 font-medium">⚠ Foto KTM buram, nama tidak terbaca</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button class="border border-gray-200 text-gray-600 px-3 py-1 rounded-lg text-[10px] font-medium hover:bg-gray-50 transition w-full">Minta unggah ulang</button>
                            </div>
                        </div>

                        <!-- Card 5 -->
                        <div class="bg-[#fcf7f7] border border-pink-50 rounded-2xl p-4 flex flex-col justify-between">
                            <div class="space-y-1">
                                <h3 class="font-bold text-xs text-[#3b2b2c]">Laras Widodo</h3>
                                <p class="text-[11px] text-gray-400 pb-2">laras.w@kampus.ac.id</p>
                                <div class="text-[11px] grid grid-cols-3 gap-1">
                                    <span class="text-gray-400">NIM</span>
                                    <span class="col-span-2 font-medium">20.11.0774</span>
                                    <span class="text-gray-400">Unit</span>
                                    <span class="col-span-2 font-medium">BEM Teknik</span>
                                    <span class="text-gray-400">Diminta</span>
                                    <span class="col-span-2 font-medium">Peminjam</span>
                                </div>
                                <div class="pt-2">
                                    <a href="#" class="text-[11px] text-rose-400 flex items-center gap-1 hover:underline">
                                        📎 KTM_laras.jpg
                                    </a>
                                </div>
                            </div>
                            <div class="flex space-x-2 mt-4">
                                <button class="bg-[#e2f0d9] text-[#2e6b27] px-3 py-1 rounded-lg text-[10px] font-medium hover:bg-green-200 transition">Verifikasi</button>
                                <button class="text-rose-400 px-2 py-1 text-[10px] font-medium hover:underline">Tolak</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- Script Tab Switching -->
    <script>
        function switchTab(tabName) {
            // Sembunyikan semua kontainer tab
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            
            // Reset style semua button tab
            document.querySelectorAll('[id^="tab-"]').forEach(el => {
                el.classList.remove('bg-white', 'font-bold');
                el.classList.add('bg-[#ebd3d6]', 'font-medium');
            });
            
            // Tampilkan kontainer yang dipilih
            document.getElementById('content-' + tabName).classList.remove('hidden');
            
            // Highlight button tab yang aktif
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('bg-[#ebd3d6]', 'font-medium');
            activeTab.classList.add('bg-white', 'font-bold');
        }
    </script>

</body>
</html>