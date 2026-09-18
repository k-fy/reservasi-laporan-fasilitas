<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Chloe</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#5c4f50] font-['Poppins',sans-serif] m-0 p-0 text-white min-h-screen flex flex-col">

    @include('admin.components.header')

    <div class="flex flex-1">
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-[#e2b8bc] text-[#4b3839] flex flex-col p-0 m-0 space-y-0 shadow-md">
            <a href="{{ route('admin.dashboard') }}" class="px-6 py-4 font-bold bg-[#5c4f50] text-white rounded-l-2xl text-center text-base">Dashboard</a>
            <a href="{{ route('admin.roles') }}" class="px-6 py-4 hover:bg-[#ebd3d6] font-semibold transition text-center text-base">Modify Roles</a>
            <a href="{{ route('admin.facilities') }}" class="px-6 py-4 hover:bg-[#ebd3d6] font-semibold transition text-center text-base">Facilities</a>
            <a href="{{ route('admin.summary') }}" class="px-6 py-4 hover:bg-[#ebd3d6] font-semibold transition text-center text-base">Summary</a>
        </aside>

        <main class="flex-1 p-6 flex flex-col justify-between">
            <div>
                <div class="mb-4">
                    <h1 class="text-2xl font-serif italic font-bold text-[#fff5f5]">Welcome back, {{ Auth::user()->name ?? 'Admin' }}.</h1>
                    <p class="text-xs text-[#d1c2c2] mt-0.5">Admins can manage system overview, roles, facilities, and activity summaries.</p>
                </div>

                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div class="bg-white rounded-xl h-24 p-4 shadow-md text-[#4b3839] flex flex-col justify-center">
                        <p class="text-xs text-gray-500 font-medium">Total Users</p>
                        <h3 class="text-xl font-bold mt-0.5">124</h3>
                    </div>
                    <div class="bg-white rounded-xl h-24 p-4 shadow-md text-[#4b3839] flex flex-col justify-center">
                        <p class="text-xs text-gray-500 font-medium">Total Facilities</p>
                        <h3 class="text-xl font-bold mt-0.5">14</h3>
                    </div>
                    <div class="bg-white rounded-xl h-24 p-4 shadow-md text-[#4b3839] flex flex-col justify-center">
                        <p class="text-xs text-gray-500 font-medium">Active Bookings</p>
                        <h3 class="text-xl font-bold mt-0.5">8</h3>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-2 bg-white rounded-xl h-44 p-4 shadow-md text-[#4b3839]">
                        <h3 class="font-bold text-xs mb-1">System Analytics Overview</h3>
                        <p class="text-[11px] text-gray-500">Overview graphic or chart space...</p>
                    </div>
                    <div class="bg-[#4b4848] border border-[#726364] rounded-xl h-44 p-4 shadow-md text-white">
                        <h3 class="font-bold text-xs mb-1">Quick Information</h3>
                        <p class="text-[11px] text-[#d1c2c2]">Recent system logs & updates.</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-center space-x-3">
                <a href="{{ route('admin.roles') }}" class="bg-[#a8caa4] text-[#2d402b] px-4 py-2 rounded-full text-xs font-semibold shadow hover:opacity-90 transition">
                    ✓ Quick Accept Roles
                </a>
                <a href="{{ route('admin.roles') }}" class="bg-[#4b4848] text-white px-4 py-2 rounded-full text-xs font-semibold shadow hover:bg-[#3b3838] transition">
                    + Add New Officer
                </a>
                <a href="{{ route('admin.facilities') }}" class="bg-[#4b4848] text-white px-4 py-2 rounded-full text-xs font-semibold shadow hover:bg-[#3b3838] transition">
                    + Add New Facility
                </a>
                <a href="{{ route('admin.summary') }}" class="bg-[#4b4848] text-white px-4 py-2 rounded-full text-xs font-semibold shadow hover:bg-[#3b3838] transition">
                    ↓ Download PDF
                </a>
            </div>
        </main>
    </div>

</body>
</html>