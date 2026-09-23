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
                <p class="text-xs text-[#d1c2c2] mt-0.5">Manage who can book, approve, and manage data.</p>
            </div>

            <!-- Flash Alert -->
            @if(session('success'))
                <div class="mb-4 bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs px-4 py-2 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Tab Buttons (Tinggal 2 Tab) -->
            <div class="flex space-x-2 items-end mb-[-1px] z-10">
                <button onclick="switchTab('manage')" id="tab-manage" class="px-6 py-2.5 rounded-t-2xl bg-white text-[#4b3839] font-bold text-xs shadow-sm transition">
                    Manage Accounts
                </button>
                <button onclick="switchTab('add')" id="tab-add" class="px-6 py-2.5 rounded-t-2xl bg-[#ebd3d6] text-[#4b3839] font-medium text-xs shadow-sm transition hover:bg-[#e0c4c7]">
                    Add Accounts
                </button>
            </div>

            <!-- Container Utama -->
            <div class="flex-1 bg-white rounded-b-2xl rounded-tr-2xl p-6 shadow-lg text-[#4b3839] min-h-[450px]">
                
                <!-- TAB 1: MANAGE ACCOUNTS -->
                <div id="content-manage" class="tab-content">
                    <!-- Search & Filter Bar -->
                    <form method="GET" action="{{ route('admin.roles') }}" class="flex gap-3 mb-5">
                        <div class="relative flex-1">
                            <svg class="w-4 h-4 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, or Student ID/NIP" class="w-full pl-9 pr-4 py-1.5 text-xs border border-gray-200 rounded-xl bg-white focus:outline-none focus:border-[#d98b92]">
                        </div>
                        <select name="role" onchange="this.form.submit()" class="px-3 py-1.5 text-xs border border-gray-200 rounded-xl bg-white text-gray-600 focus:outline-none cursor-pointer">
                            <option value="">All roles</option>
                            <option value="Admin" {{ request('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="Petugas" {{ request('role') == 'Petugas' ? 'selected' : '' }}>Officer</option>
                            <option value="Pengguna" {{ request('role') == 'Pengguna' ? 'selected' : '' }}>User</option>
                        </select>
                        <select name="status" onchange="this.form.submit()" class="px-3 py-1.5 text-xs border border-gray-200 rounded-xl bg-white text-gray-600 focus:outline-none cursor-pointer">
                            <option value="">All statuses</option>
                            <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Active</option>
                            <option value="Ditangguhkan" {{ request('status') == 'Ditangguhkan' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </form>

                    <!-- Table Container -->
                    <div class="overflow-x-auto rounded-2xl border border-pink-100 mb-4">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-[#ebd3d6] text-[#4b3839] font-semibold">
                                <tr>
                                    <th class="p-3 pl-4">Name</th>
                                    <th class="p-3">Campus email</th>
                                    <th class="p-3">Unit</th>
                                    <th class="p-3">Role</th>
                                    <th class="p-3">Status</th>
                                    <th class="p-3">Last active</th>
                                    <th class="p-3">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse($users as $user)
                                    <tr>
                                        <td class="p-3 pl-4 font-medium">{{ $user->name }}</td>
                                        <td class="p-3 text-gray-500">{{ $user->email }}</td>
                                        <td class="p-3">{{ $user->unit ?? '-' }}</td>
                                        <td class="p-3">
                                            @if(Auth::id() === $user->id)
                                                <span class="bg-gray-100 px-2.5 py-1 rounded-lg text-[11px] font-medium text-gray-700">{{ $user->role ?? 'Admin' }}</span>
                                            @else
                                                <form action="{{ route('admin.users.update-role', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="role" onchange="this.form.submit()" class="bg-gray-100 border border-gray-200 rounded-lg px-2 py-1 text-[11px] font-medium text-gray-700 cursor-pointer">
                                                        <option value="Admin" {{ ($user->role ?? '') == 'Admin' ? 'selected' : '' }}>Admin</option>
                                                        <option value="Petugas" {{ ($user->role ?? '') == 'Petugas' ? 'selected' : '' }}>Officer</option>
                                                        <option value="Pengguna" {{ ($user->role ?? '') == 'Pengguna' ? 'selected' : '' }}>User</option>
                                                    </select>
                                                </form>
                                            @endif
                                        </td>
                                        <td class="p-3">
                                            @if(($user->status ?? 'Aktif') === 'Aktif')
                                                <span class="bg-[#e2f0d9] text-[#2e6b27] px-2.5 py-0.5 rounded-full text-[10px] font-medium">Active</span>
                                            @else
                                                <span class="bg-pink-100 text-pink-500 px-2.5 py-0.5 rounded-full text-[10px] font-medium">Suspended</span>
                                            @endif
                                        </td>
                                        <td class="p-3 text-gray-500">
                                            {{ $user->updated_at ? $user->updated_at->diffForHumans() : 'Just now' }}
                                        </td>
                                        <td class="p-3">
                                            @if(Auth::id() === $user->id)
                                                <span class="text-gray-400 italic">Your account</span>
                                            @else
                                                <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="{{ ($user->status ?? 'Aktif') === 'Aktif' ? 'text-gray-500 hover:underline' : 'text-rose-600 font-semibold hover:underline' }}">
                                                        {{ ($user->status ?? 'Aktif') === 'Aktif' ? 'Suspend' : 'Activate' }}
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="p-4 text-center text-gray-400">No users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <p class="text-[11px] text-gray-500 italic">Role changes take effect upon the user's next login and are recorded in the activity log.</p>
                </div>

                <!-- TAB 2: ADD ACCOUNTS -->
                
                <div id="content-add" class="tab-content hidden">
                    <h2 class="font-bold text-sm text-[#3b2b2c]">Add officer or admin account</h2>
                    <p class="text-xs text-gray-400 mb-6">Create account credentials for officers or admins to access their dashboard.</p>

                    <form class="space-y-4 max-w-3xl" action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">Full name</label>
                                <input type="text" name="name" required placeholder="e.g. Bagus Tri Prakoso" class="w-full bg-[#f8f4f4] border-0 rounded-xl p-2.5 text-xs text-gray-700 focus:ring-1 focus:ring-pink-300 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">Student ID / Employee ID</label>
                                <input type="text" name="nim_nip" placeholder="19870412 201004 1 002" class="w-full bg-[#f8f4f4] border-0 rounded-xl p-2.5 text-xs text-gray-700 focus:ring-1 focus:ring-pink-300 focus:outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">Campus email</label>
                                <input type="email" name="email" required placeholder="name@kampus.ac.id" class="w-full bg-[#f8f4f4] border-0 rounded-xl p-2.5 text-xs text-gray-700 focus:ring-1 focus:ring-pink-300 focus:outline-none">
                                <span class="text-[10px] text-gray-400 mt-0.5 block">Only kampus.ac.id domains are accepted.</span>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">Temporary Password</label>
                                <input type="password" name="password" required placeholder="Set default password" class="w-full bg-[#f8f4f4] border-0 rounded-xl p-2.5 text-xs text-gray-700 focus:ring-1 focus:ring-pink-300 focus:outline-none">
                                <span class="text-[10px] text-gray-400 mt-0.5 block">Used for initial login.</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">Placement unit</label>
                                <select name="unit" class="w-full bg-[#f8f4f4] border-0 rounded-xl p-2.5 text-xs text-gray-700 focus:ring-1 focus:ring-pink-300 focus:outline-none">
                                    <option value="Gedung Rektorat">Rectorate Building</option>
                                    <option value="BAUK">BAUK</option>
                                    <option value="Gedung A">Building A</option>
                                    <option value="Gedung C">Building C</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">Role</label>
                                <select name="role" class="w-full bg-[#f8f4f4] border-0 rounded-xl p-2.5 text-xs text-gray-700 focus:ring-1 focus:ring-pink-300 focus:outline-none">
                                    <option value="Petugas">Officer — manages schedules and approves bookings in their unit</option>
                                    <option value="Pengguna">User — books and utilizes campus facilities</option>
                                    <option value="Admin">Admin — has full system access</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1 text-gray-700">Note for recipient <span class="text-gray-400 font-normal">(optional)</span></label>
                            <textarea name="notes" rows="3" placeholder="e.g. In charge of Main Hall schedule starting October 1st." class="w-full bg-[#f8f4f4] border-0 rounded-xl p-2.5 text-xs text-gray-700 focus:ring-1 focus:ring-pink-300 focus:outline-none resize-none"></textarea>
                        </div>

                        <div class="flex space-x-2 pt-2">
                            <button type="submit" class="bg-[#785b5d] text-white px-5 py-2 rounded-xl text-xs font-medium hover:bg-[#5c4f50] transition">Create Account</button>
                            <button type="reset" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-xl text-xs font-medium hover:bg-gray-200 transition">Clear form</button>
                        </div>
                    </form>
                </div>

            </div>
        </main>
    </div>

    <!-- Script Tab Switching -->
    <script>
        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            
            document.querySelectorAll('[id^="tab-"]').forEach(el => {
                el.classList.remove('bg-white', 'font-bold');
                el.classList.add('bg-[#ebd3d6]', 'font-medium');
            });
            
            document.getElementById('content-' + tabName).classList.remove('hidden');
            
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('bg-[#ebd3d6]', 'font-medium');
            activeTab.classList.add('bg-white', 'font-bold');
        }
    </script>

</body>
</html>