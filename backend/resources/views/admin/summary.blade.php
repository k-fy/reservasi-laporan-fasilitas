<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Summary Reports - Admin Chloe</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#5c4f50] font-['Poppins',sans-serif] m-0 p-0 text-white min-h-screen flex flex-col">

    @include('admin.components.header')

    <div class="flex flex-1">
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-[#e2b8bc] text-[#4b3839] flex flex-col p-0 m-0 space-y-0 shadow-md">
            <a href="{{ route('admin.dashboard') }}" class="px-6 py-4 hover:bg-[#ebd3d6] font-semibold transition text-center text-base">Dashboard</a>
            <a href="{{ route('admin.roles') }}" class="px-6 py-4 hover:bg-[#ebd3d6] font-semibold transition text-center text-base">Modify Roles</a>
            <a href="{{ route('admin.facilities') }}" class="px-6 py-4 hover:bg-[#ebd3d6] font-semibold transition text-center text-base">Facilities</a>
            <a href="{{ route('admin.summary') }}" class="px-6 py-4 font-bold bg-[#5c4f50] text-white rounded-l-2xl text-center text-base">Summary</a>
        </aside>

        <main class="flex-1 p-6 flex flex-col">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h1 class="text-2xl font-serif italic font-bold text-[#fff5f5]">Activity Summary</h1>
                    <p class="text-xs text-[#d1c2c2] mt-0.5">Overview of bookings, facility usage, and reports statistics.</p>
                </div>
                <button class="bg-[#4b4848] text-white px-4 py-2 rounded-xl text-xs font-semibold shadow hover:bg-[#3b3838] transition flex items-center gap-1.5">
                    ↓ Download PDF Report
                </button>
            </div>

            <div class="flex-1 bg-white rounded-2xl p-6 shadow-lg text-[#4b3839]">
             
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-[#fdf2f2] border border-[#f3d8d5] rounded-xl p-4 shadow-sm">
                        <p class="text-[11px] text-gray-500 font-medium">Total Bookings</p>
                        <h3 class="text-xl font-bold mt-0.5 text-[#4b3839]">128</h3>
                        <span class="text-[10px] text-green-600 font-medium">↑ +12% from last month</span>
                    </div>
                    <div class="bg-[#fdf2f2] border border-[#f3d8d5] rounded-xl p-4 shadow-sm">
                        <p class="text-[11px] text-gray-500 font-medium">Active Facilities</p>
                        <h3 class="text-xl font-bold mt-0.5 text-[#4b3839]">14</h3>
                        <span class="text-[10px] text-gray-500 font-medium">All operational</span>
                    </div>
                    <div class="bg-[#fdf2f2] border border-[#f3d8d5] rounded-xl p-4 shadow-sm">
                        <p class="text-[11px] text-gray-500 font-medium">Pending Reports</p>
                        <h3 class="text-xl font-bold mt-0.5 text-[#4b3839]">3</h3>
                        <span class="text-[10px] text-orange-600 font-medium">Requires attention</span>
                    </div>
                </div>

                <h2 class="text-lg font-bold mb-3">Recent Booking Activity</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-gray-200 text-gray-500">
                                <th class="py-2.5 px-3 font-semibold">Booking ID</th>
                                <th class="py-2.5 px-3 font-semibold">User</th>
                                <th class="py-2.5 px-3 font-semibold">Facility</th>
                                <th class="py-2.5 px-3 font-semibold">Date & Time</th>
                                <th class="py-2.5 px-3 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-2.5 px-3 font-medium">#BK-001</td>
                                <td class="py-2.5 px-3 text-gray-600">Zulfa Nabilah</td>
                                <td class="py-2.5 px-3 text-gray-600">Aula Utama FSM</td>
                                <td class="py-2.5 px-3 text-gray-600">18 Sep 2026, 08:00 - 12:00</td>
                                <td class="py-2.5 px-3">
                                    <span class="bg-green-100 text-green-700 text-[11px] px-2.5 py-0.5 rounded-full font-medium">Approved</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-2.5 px-3 font-medium">#BK-002</td>
                                <td class="py-2.5 px-3 text-gray-600">Budi Santoso</td>
                                <td class="py-2.5 px-3 text-gray-600">Proyektor Epson V1</td>
                                <td class="py-2.5 px-3 text-gray-600">19 Sep 2026, 13:00 - 15:00</td>
                                <td class="py-2.5 px-3">
                                    <span class="bg-yellow-100 text-yellow-700 text-[11px] px-2.5 py-0.5 rounded-full font-medium">Pending</span>
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