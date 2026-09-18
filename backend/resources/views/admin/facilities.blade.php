<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Data Facility - Admin Chloe</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#5c4f50] font-['Poppins',sans-serif] m-0 p-0 text-white min-h-screen flex flex-col">

    @include('admin.components.header')

    <div class="flex flex-1">
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-[#e2b8bc] text-[#4b3839] flex flex-col p-0 m-0 space-y-0 shadow-md">
            <a href="{{ route('admin.dashboard') }}" class="px-6 py-4 hover:bg-[#ebd3d6] font-semibold transition text-center text-base">Dashboard</a>
            <a href="{{ route('admin.roles') }}" class="px-6 py-4 hover:bg-[#ebd3d6] font-semibold transition text-center text-base">Modify Roles</a>
            <a href="{{ route('admin.facilities') }}" class="px-6 py-4 font-bold bg-[#5c4f50] text-white rounded-l-2xl text-center text-base">Facilities</a>
            <a href="{{ route('admin.summary') }}" class="px-6 py-4 hover:bg-[#ebd3d6] font-semibold transition text-center text-base">Summary</a>
        </aside>

        <main class="flex-1 p-6 flex flex-col">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h1 class="text-2xl font-serif italic font-bold text-[#fff5f5]">Master Data Facilities</h1>
                    <p class="text-xs text-[#d1c2c2] mt-0.5">Manage campus halls, rooms, and equipment inventory.</p>
                </div>
                <button class="bg-[#a8caa4] text-[#2d402b] px-4 py-2 rounded-xl text-xs font-semibold shadow hover:opacity-90 transition flex items-center gap-1.5">
                    + Add New Facility
                </button>
            </div>

            <div class="flex-1 bg-white rounded-2xl p-6 shadow-lg text-[#4b3839]">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold">Facility List</h2>
                    <input type="text" placeholder="Search facility..." class="border border-gray-300 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:border-[#4b3839]">
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-gray-200 text-gray-500">
                                <th class="py-2.5 px-3 font-semibold">No</th>
                                <th class="py-2.5 px-3 font-semibold">Facility Name</th>
                                <th class="py-2.5 px-3 font-semibold">Category</th>
                                <th class="py-2.5 px-3 font-semibold">Capacity / Details</th>
                                <th class="py-2.5 px-3 font-semibold">Status</th>
                                <th class="py-2.5 px-3 font-semibold text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-2.5 px-3">1</td>
                                <td class="py-2.5 px-3 font-medium">Aula Utama FSM</td>
                                <td class="py-2.5 px-3 text-gray-600">Room / Hall</td>
                                <td class="py-2.5 px-3 text-gray-600">200 People</td>
                                <td class="py-2.5 px-3">
                                    <span class="bg-green-100 text-green-700 text-[11px] px-2.5 py-0.5 rounded-full font-medium">Available</span>
                                </td>
                                <td class="py-2.5 px-3 text-center space-x-2">
                                    <button class="text-blue-600 hover:underline text-xs font-medium">Edit</button>
                                    <button class="text-red-600 hover:underline text-xs font-medium">Delete</button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-2.5 px-3">2</td>
                                <td class="py-2.5 px-3 font-medium">Proyektor Epson V1</td>
                                <td class="py-2.5 px-3 text-gray-600">Equipment</td>
                                <td class="py-2.5 px-3 text-gray-600">Portable Unit</td>
                                <td class="py-2.5 px-3">
                                    <span class="bg-yellow-100 text-yellow-700 text-[11px] px-2.5 py-0.5 rounded-full font-medium">In Use</span>
                                </td>
                                <td class="py-2.5 px-3 text-center space-x-2">
                                    <button class="text-blue-600 hover:underline text-xs font-medium">Edit</button>
                                    <button class="text-red-600 hover:underline text-xs font-medium">Delete</button>
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