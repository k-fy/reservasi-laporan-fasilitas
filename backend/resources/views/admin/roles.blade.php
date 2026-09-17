<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Roles - Admin Chloe</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#5c4f50] font-['Poppins',sans-serif] m-0 p-0 text-white min-h-screen flex flex-col">

    <!-- Top Header -->
    <header class="flex justify-between items-center px-8 py-4 border-b border-[#726364]">
        <div class="flex items-center space-x-3">
            <div class="text-2xl font-serif italic font-bold tracking-wider text-[#ffdcdc]">Chloe</div>
            <span class="text-xs text-[#d1c2c2] tracking-wide">Campus Hall & Location Online E-booking</span>
        </div>
        <div class="flex items-center space-x-3 text-sm">
            <span class="text-[#d1c2c2]">Logged in as <strong class="underline italic text-white">Admin-01</strong></span>
            <div class="w-9 h-9 rounded-full bg-[#a86b6b] flex items-center justify-center font-bold text-white shadow">
                A
            </div>
        </div>
    </header>

    <div class="flex flex-1">
        <!-- Sidebar -->
        <aside class="w-64 bg-[#e2b8bc] text-[#4b3839] flex flex-col py-8 space-y-2 shadow-md">
            <a href="{{ route('admin.dashboard') }}" class="px-8 py-3 hover:bg-[#ebd3d6] transition">Dashboard</a>
            <a href="{{ route('admin.roles') }}" class="px-8 py-3 font-semibold bg-[#f4d1d5] border-l-4 border-[#4b3839]">Modify Roles</a>
            <a href="{{ route('admin.facilities') }}" class="px-8 py-3 hover:bg-[#ebd3d6] transition">Master Data</a>
            <a href="{{ route('admin.summary') }}" class="px-8 py-3 hover:bg-[#ebd3d6] transition">Summary</a>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 p-8 flex flex-col">
            <!-- Heading -->
            <div class="mb-6">
                <h1 class="text-3xl font-serif italic font-bold text-[#fff5f5]">Modify Roles</h1>
                <p class="text-xs text-[#d1c2c2] mt-1">Admins can bla bla bla</p>
            </div>

            <!-- Tabs Navigation -->
            <div class="flex space-x-2 items-end mb-[-1px] z-10">
                <button onclick="switchTab('manage')" id="tab-manage" class="px-6 py-2.5 rounded-t-xl bg-[#e5dbdc] text-[#4b3839] font-semibold text-sm shadow-sm transition">
                    Manage Accounts
                </button>
                <button onclick="switchTab('add')" id="tab-add" class="px-6 py-2.5 rounded-t-xl bg-[#c5b5b7] text-[#4b3839] font-semibold text-sm shadow-sm transition">
                    Add Accounts
                </button>
                <button onclick="switchTab('verification')" id="tab-verification" class="px-6 py-2.5 rounded-t-xl bg-[#c5b5b7] text-[#4b3839] font-semibold text-sm shadow-sm transition flex items-center gap-1.5">
                    <span class="w-4 h-4 rounded-full bg-[#d98b92] text-white text-[10px] flex items-center justify-center font-bold">5</span>
                    Registration Verification
                </button>
            </div>

            <!-- Tab Content Container (Kertas Putih Besar) -->
            <div class="flex-1 bg-white rounded-b-2xl rounded-tr-2xl p-8 shadow-lg text-[#4b3839] min-h-[400px]">
                
                <!-- Content: Manage Accounts -->
                <div id="content-manage" class="tab-content">
                    <h2 class="text-xl font-bold mb-4">Manage Existing Accounts</h2>
                    <p class="text-sm text-gray-600">Tabel atau daftar akun pengguna yang bisa diubah rolenya akan tampil di sini.</p>
                </div>

                <!-- Content: Add Accounts -->
                <div id="content-add" class="tab-content hidden">
                    <h2 class="text-xl font-bold mb-4">Add New Account</h2>
                    <p class="text-sm text-gray-600">Form untuk menambahkan akun baru akan tampil di sini.</p>
                </div>

                <!-- Content: Registration Verification -->
                <div id="content-verification" class="tab-content hidden">
                    <h2 class="text-xl font-bold mb-4">Registration Verification</h2>
                    <p class="text-sm text-gray-600">Daftar verifikasi akun yang mendaftar akan tampil di sini.</p>
                </div>

            </div>
        </main>
    </div>

    <!-- Script Sederhana untuk Ganti Tab -->
    <script>
        function switchTab(tabName) {
            // Sembunyikan semua konten tab
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            
            // Kembalikan semua warna tombol tab ke non-aktif
            document.querySelectorAll('[id^="tab-"]').forEach(el => {
                el.classList.remove('bg-[#e5dbdc]');
                el.classList.add('bg-[#c5b5b7]');
            });

            // Tampilkan tab yang dipilih dan ubah warnanya jadi terang
            document.getElementById('content-' + tabName).classList.remove('hidden');
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('bg-[#c5b5b7]');
            activeTab.classList.add('bg-[#e5dbdc]');
        }
    </script>

</body>
</html>