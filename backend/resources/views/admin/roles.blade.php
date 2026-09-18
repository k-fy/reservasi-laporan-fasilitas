<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Roles - Admin Chloe</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#5c4f50] font-['Poppins',sans-serif] m-0 p-0 text-white min-h-screen flex flex-col">

    @include('admin.components.header')

    <div class="flex flex-1">
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-[#e2b8bc] text-[#4b3839] flex flex-col p-0 m-0 space-y-0 shadow-md">
            <a href="{{ route('admin.dashboard') }}" class="px-6 py-4 hover:bg-[#ebd3d6] font-semibold transition text-center text-base">Dashboard</a>
            <a href="{{ route('admin.roles') }}" class="px-6 py-4 font-bold bg-[#5c4f50] text-white rounded-l-2xl text-center text-base">Modify Roles</a>
            <a href="{{ route('admin.facilities') }}" class="px-6 py-4 hover:bg-[#ebd3d6] font-semibold transition text-center text-base">Facilities</a>
            <a href="{{ route('admin.summary') }}" class="px-6 py-4 hover:bg-[#ebd3d6] font-semibold transition text-center text-base">Summary</a>
        </aside>

        <main class="flex-1 p-6 flex flex-col">
            <div class="mb-4">
                <h1 class="text-2xl font-serif italic font-bold text-[#fff5f5]">Modify Roles</h1>
                <p class="text-xs text-[#d1c2c2] mt-0.5">Manage user permissions and account role status.</p>
            </div>

            <div class="flex space-x-2 items-end mb-[-1px] z-10">
                <button onclick="switchTab('manage')" id="tab-manage" class="px-5 py-2 rounded-t-xl bg-[#e5dbdc] text-[#4b3839] font-semibold text-xs shadow-sm transition">
                    Manage Accounts
                </button>
                <button onclick="switchTab('add')" id="tab-add" class="px-5 py-2 rounded-t-xl bg-[#c5b5b7] text-[#4b3839] font-semibold text-xs shadow-sm transition">
                    Add Accounts
                </button>
                <button onclick="switchTab('verification')" id="tab-verification" class="px-5 py-2 rounded-t-xl bg-[#c5b5b7] text-[#4b3839] font-semibold text-xs shadow-sm transition flex items-center gap-1.5">
                    <span class="w-3.5 h-3.5 rounded-full bg-[#d98b92] text-white text-[9px] flex items-center justify-center font-bold">5</span>
                    Registration Verification
                </button>
            </div>

            <div class="flex-1 bg-white rounded-b-2xl rounded-tr-2xl p-6 shadow-lg text-[#4b3839] min-h-[350px]">
                <div id="content-manage" class="tab-content">
                    <h2 class="text-lg font-bold mb-3">Manage Existing Accounts</h2>
                    <p class="text-xs text-gray-600">Tabel atau daftar akun pengguna yang bisa diubah rolenya akan tampil di sini.</p>
                </div>

                <div id="content-add" class="tab-content hidden">
                    <h2 class="text-lg font-bold mb-3">Add New Account</h2>
                    <p class="text-xs text-gray-600">Form untuk menambahkan akun baru akan tampil di sini.</p>
                </div>

                <div id="content-verification" class="tab-content hidden">
                    <h2 class="text-lg font-bold mb-3">Registration Verification</h2>
                    <p class="text-xs text-gray-600">Daftar verifikasi akun yang mendaftar akan tampil di sini.</p>
                </div>
            </div>
        </main>
    </div>

    <script>
        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('[id^="tab-"]').forEach(el => {
                el.classList.remove('bg-[#e5dbdc]');
                el.classList.add('bg-[#c5b5b7]');
            });
            document.getElementById('content-' + tabName).classList.remove('hidden');
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('bg-[#c5b5b7]');
            activeTab.classList.add('bg-[#e5dbdc]');
        }
    </script>

</body>
</html>