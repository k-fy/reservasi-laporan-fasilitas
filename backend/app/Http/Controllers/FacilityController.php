<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facilities - Admin Chloe</title>
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#5c4f50] font-['Poppins',sans-serif] m-0 p-0 text-white min-h-screen flex flex-col">

    <!-- Top Header -->
    <header class="flex justify-between items-center px-8 py-4 border-b border-[#726364]">
        <div class="flex items-center space-x-3">
            <!-- Pengecualian 1: "Chloe" menggunakan font-serif -->
            <div class="text-2xl font-serif italic font-bold tracking-wider text-[#ffdcdc]">Chloe</div>
            <span class="text-xs text-[#d1c2c2] tracking-wide">Campus Hall & Location Online E-booking</span>
        </div>
        <div class="flex items-center space-x-3 text-sm">
            <span class="text-[#d1c2c2]">Logged in as <strong class="underline italic text-white">{{ Auth::user()->name ?? 'Admin Chloe' }}</strong></span>
            <div class="w-9 h-9 rounded-full bg-[#a86b6b] flex items-center justify-center font-bold text-white shadow">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
            </div>
        </div>
    </header>

    <div class="flex flex-1">
        <!-- Sidebar Navigation (Poppins) -->
        <aside class="w-64 bg-[#e2b8bc] text-[#4b3839] flex flex-col py-8 space-y-2 shadow-md">
            <a href="{{ route('admin.dashboard') }}" class="px-8 py-3 hover:bg-[#ebd3d6] transition">Dashboard</a>
            <a href="{{ route('admin.roles') }}" class="px-8 py-3 hover:bg-[#ebd3d6] transition">Modify Roles</a>
            <a href="{{ route('admin.facilities') }}" class="px-8 py-3 font-semibold bg-[#f4d1d5] border-l-4 border-[#4b3839]">Facilities</a>
            <a href="{{ route('admin.summary') }}" class="px-8 py-3 hover:bg-[#ebd3d6] transition">Summary</a>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 p-8 flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <!-- Pengecualian 2: "Facilities" menggunakan font-serif -->
                    <h1 class="text-3xl font-serif italic font-bold text-[#fff5f5]">Facilities</h1>
                    <p class="text-xs text-[#d1c2c2] mt-1">Manage campus halls, rooms, and equipment inventory.</p>
                </div>
                <button class="bg-[#a8caa4] text-[#2d402b] px-4 py-2 rounded-xl text-xs font-semibold shadow hover:opacity-90 transition flex items-center gap-1.5">
                    + Add New Facility
                </button>
            </div>

            <!-- Content Container (Poppins) -->
            <div class="flex-1 bg-white rounded-2xl p-8 shadow-lg text-[#4b3839]">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Facility List</h2>
                    <input type="text" placeholder="Search facility..." class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:border-[#4b3839]">
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 text-gray-500">
                                <th class="py-3 px-4 font-semibold">No</th>
                                <th class="py-3 px-4 font-semibold">Facility Name</th>
                                <th class="py-3 px-4 font-semibold">Category</th>
                                <th class="py-3 px-4 font-semibold">Capacity / Details</th>
                                <th class="py-3 px-4 font-semibold">Status</th>
                                <th class="py-3 px-4 font-semibold text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3 px-4">1</td>
                                <td class="py-3 px-4 font-medium">Aula Utama FSM</td>
                                <td class="py-3 px-4 text-gray-600">Room / Hall</td>
                                <td class="py-3 px-4 text-gray-600">200 People</td>
                                <td class="py-3 px-4">
                                    <span class="bg-green-100 text-green-700 text-xs px-2.5 py-1 rounded-full font-medium">Available</span>
                                </td>
                                <td class="py-3 px-4 text-center space-x-2">
                                    <button class="text-blue-600 hover:underline text-xs font-medium">Edit</button>
                                    <button class="text-red-600 hover:underline text-xs font-medium">Delete</button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3 px-4">2</td>
                                <td class="py-3 px-4 font-medium">Proyektor Epson V1</td>
                                <td class="py-3 px-4 text-gray-600">Equipment</td>
                                <td class="py-3 px-4 text-gray-600">Portable Unit</td>
                                <td class="py-3 px-4">
                                    <span class="bg-yellow-100 text-yellow-700 text-xs px-2.5 py-1 rounded-full font-medium">In Use</span>
                                </td>
                                <td class="py-3 px-4 text-center space-x-2">
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