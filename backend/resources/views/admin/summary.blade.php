<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Summary Reports - Admin Chloe</title>
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
            <a href="{{ route('admin.roles') }}" class="px-8 py-3 hover:bg-[#ebd3d6] transition">Modify Roles</a>
            <a href="{{ route('admin.facilities') }}" class="px-8 py-3 hover:bg-[#ebd3d6] transition">Master Data</a>
            <a href="{{ route('admin.summary') }}" class="px-8 py-3 font-semibold bg-[#f4d1d5] border-l-4 border-[#4b3839]">Summary</a>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 p-8 flex flex-col">
            <!-- Heading & Export Action -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-serif italic font-bold text-[#fff5f5]">Activity Summary</h1>
                    <p class="text-xs text-[#d1c2c2] mt-1">Overview of bookings, facility usage, and reports statistics.</p>
                </div>
                <button class="bg-[#4b4848] text-white px-4 py-2 rounded-xl text-xs font-semibold shadow hover:bg-[#3b3838] transition flex items-center gap-1.5">
                    ↓ Download PDF Report
                </button>
            </div>

            <!-- Content Container -->
            <div class="flex-1 bg-white rounded-2xl p-8 shadow-lg text-[#4b3839]">
                
                <!-- Stats Grid Cards -->
                <div class="grid grid-cols-3 gap-6 mb-8">
                    <div class="bg-[#fdf2f2] border border-[#f3d8d5] rounded-xl p-5 shadow-sm">
                        <p class="text-xs text-gray-500 font-medium">Total Bookings</p>
                        <h3 class="text-2xl font-bold mt-1 text-[#4b3839]">128</h3>
                        <span class="text-[11px] text-green-600 font-medium">↑ +12% from last month</span>
                    </div>
                    <div class="bg-[#fdf2f2] border border-[#f3d8d5] rounded-xl p-5 shadow-sm">
                        <p class="text-xs text-gray-500 font-medium">Active Facilities</p>
                        <h3 class="text-2xl font-bold mt-1 text-[#4b3839]">14</h3>
                        <span class="text-[11px] text-gray-500 font-medium">All operational</span>
                    </div>
                    <div class="bg-[#fdf2f2] border border-[#f3d8d5] rounded-xl p-5 shadow-sm">
                        <p class="text-xs text-gray-500 font-medium">Pending Reports</p>
                        <h3 class="text-2xl font-bold mt-1 text-[#4b3839]">3</h3>
                        <span class="text-[11px] text-orange-600 font-medium">Requires attention</span>
                    </div>
                </div>

                <!-- Recent Summary Table -->
                <h2 class="text-xl font-bold mb-4">Recent Booking Activity</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 text-gray-500">
                                <th class="py-3 px-4 font-semibold">Booking ID</th>
                                <th class="py-3 px-4 font-semibold">User</th>
                                <th class="py-3 px-4 font-semibold">Facility</th>
                                <th class="py-3 px-4 font-semibold">Date & Time</th>
                                <th class="py-3 px-4 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3 px-4 font-medium">#BK-001</td>
                                <td class="py-3 px-4 text-gray-600">Zulfa Nabilah</td>
                                <td class="py-3 px-4 text-gray-600">Aula Utama FSM</td>
                                <td class="py-3 px-4 text-gray-600">18 Sep 2026, 08:00 - 12:00</td>
                                <td class="py-3 px-4">
                                    <span class="bg-green-100 text-green-700 text-xs px-2.5 py-1 rounded-full font-medium">Approved</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3 px-4 font-medium">#BK-002</td>
                                <td class="py-3 px-4 text-gray-600">Budi Santoso</td>
                                <td class="py-3 px-4 text-gray-600">Proyektor Epson V1</td>
                                <td class="py-3 px-4 text-gray-600">19 Sep 2026, 13:00 - 15:00</td>
                                <td class="py-3 px-4">
                                    <span class="bg-yellow-100 text-yellow-700 text-xs px-2.5 py-1 rounded-full font-medium">Pending</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </main>
    </div>

</body>
</html>