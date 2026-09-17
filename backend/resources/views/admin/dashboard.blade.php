<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Chloe</title>
    <!-- Tailwind CSS CDN (atau sesuaikan dengan setup asset mix/vite projectmu) -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#5c4f50] font-['Poppins',sans-serif] m-0 p-0 text-white min-h-screen flex flex-col">

    <!-- Top Header -->
    <header class="flex justify-between items-center px-8 py-4 border-b border-[#726364]">
        <div class="flex items-center space-x-3">
            <!-- Logo Chloe -->
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
            <a href="{{ route('admin.dashboard') }}" class="px-8 py-3 font-semibold bg-[#f4d1d5] border-l-4 border-[#4b3839]">Dashboard</a>
            <a href="{{ route('admin.roles') }}" class="px-8 py-3 hover:bg-[#ebd3d6] transition">Modify Roles</a>
            <a href="{{ route('admin.facilities') }}" class="px-8 py-3 hover:bg-[#ebd3d6] transition">Master Data</a>
            <a href="{{ route('admin.summary') }}" class="px-8 py-3 hover:bg-[#ebd3d6] transition">Summary</a>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 p-8 flex flex-col justify-between">
            <div>
                <!-- Heading -->
                <div class="mb-6">
                    <h1 class="text-3xl font-serif italic font-bold text-[#fff5f5]">Welcome back.</h1>
                    <p class="text-xs text-[#d1c2c2] mt-1">Admins can bla bla bla</p>
                </div>

                <!-- Top Grid Cards (3 Kotak atas) -->
                <div class="grid grid-cols-3 gap-6 mb-6">
                    <div class="bg-white rounded-xl h-32 shadow-md"></div>
                    <div class="bg-white rounded-xl h-32 shadow-md"></div>
                    <div class="bg-white rounded-xl h-32 shadow-md"></div>
                </div>

                <!-- Bottom Grid Cards (2 Kotak bawah) -->
                <div class="grid grid-cols-3 gap-6">
                    <div class="col-span-2 bg-white rounded-xl h-56 shadow-md"></div>
                    <div class="bg-[#4b4848] border border-[#726364] rounded-xl h-56 shadow-md"></div>
                </div>
            </div>

            <!-- Quick Actions Buttons (Bagian Bawah) -->
            <div class="mt-8 flex justify-center space-x-4">
                <button class="bg-[#a8caa4] text-[#2d402b] px-5 py-2.5 rounded-full text-xs font-semibold shadow hover:opacity-90">
                    ✓ Quick Accept Roles
                </button>
                <button class="bg-[#4b4848] text-white px-5 py-2.5 rounded-full text-xs font-semibold shadow hover:bg-[#3b3838]">
                    + Add New Officer
                </button>
                <button class="bg-[#4b4848] text-white px-5 py-2.5 rounded-full text-xs font-semibold shadow hover:bg-[#3b3838]">
                    + Add New Facility
                </button>
                <button class="bg-[#4b4848] text-white px-5 py-2.5 rounded-full text-xs font-semibold shadow hover:bg-[#3b3838]">
                    ↓ Download PDF
                </button>
            </div>
        </main>
    </div>

</body>
</html>