<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts - Admin Chloe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@1,600&display=swap" rel="stylesheet">
</head>
<body class="bg-[#5c4f50] font-['Poppins',sans-serif] m-0 p-0 text-white min-h-screen flex flex-col">

    @include('admin.components.header')

    @php
        use App\Models\User;

        $roleLabels = [
            User::ROLE_ADMIN    => 'Admin',
            User::ROLE_PETUGAS  => 'Officer',
            User::ROLE_PENGGUNA => 'User',
        ];
    @endphp

    <div class="flex flex-1">
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-[#e2b8bc] text-[#4b3839] flex flex-col p-0 m-0 space-y-0 shadow-md">
            <a href="{{ route('admin.roles') }}" class="px-6 py-4 font-bold bg-[#5c4f50] text-white rounded-l-2xl text-center text-base">Accounts</a>
            <a href="{{ route('admin.facilities') }}" class="px-6 py-4 hover:bg-[#ebd3d6] font-semibold transition text-center text-base">Facilities</a>
            <a href="{{ route('admin.summary') }}" class="px-6 py-4 hover:bg-[#ebd3d6] font-semibold transition text-center text-base">Recap</a>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 p-6 flex flex-col">
            <div class="mb-4">
                <h1 class="text-3xl font-['Playfair_Display',serif] italic font-semibold text-[#fff5f5]">Accounts</h1>
                <p class="text-xs text-[#d1c2c2] mt-0.5">Manage accounts and register new officers.</p>
            </div>

            <!-- Flash Alert -->
            @if(session('success'))
                <div class="mb-4 bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs px-4 py-2 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Alert -->
            @if($errors->any())
                <div class="mb-4 bg-rose-100 border border-rose-300 text-rose-800 text-xs px-4 py-2 rounded-xl">
                    <ul class="list-disc pl-4">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Tab Buttons -->
            <div class="flex space-x-2 items-end mb-[-1px] z-10">
                <button onclick="switchTab('manage')" id="tab-manage" class="px-6 py-2.5 rounded-t-2xl bg-white text-[#4b3839] font-bold text-xs shadow-sm transition">
                    Manage Accounts
                </button>
                <button onclick="switchTab('add')" id="tab-add" class="px-6 py-2.5 rounded-t-2xl bg-[#ebd3d6] text-[#4b3839] font-medium text-xs shadow-sm transition hover:bg-[#e0c4c7]">
                    Add Account
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
                            @foreach($roleLabels as $value => $label)
                                <option value="{{ $value }}" {{ request('role') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <select name="status" onchange="this.form.submit()" class="px-3 py-1.5 text-xs border border-gray-200 rounded-xl bg-white text-gray-600 focus:outline-none cursor-pointer">
                            <option value="">All statuses</option>
                            <option value="{{ User::STATUS_ACTIVE }}" {{ request('status') === User::STATUS_ACTIVE ? 'selected' : '' }}>Active</option>
                            <option value="{{ User::STATUS_SUSPENDED }}" {{ request('status') === User::STATUS_SUSPENDED ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </form>

                    <!-- Table Container -->
                    <div class="overflow-x-auto rounded-2xl border border-pink-100 mb-4">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-[#f2e6e6] text-[#4b3839]">
                                <tr>
                                    <th class="p-3 text-left">Name</th>
                                    <th class="p-3 text-left">Campus email</th>
                                    <th class="p-3 text-left">Role</th>
                                    <th class="p-3 text-left">Unit</th>
                                    <th class="p-3 text-left">Status</th>
                                    <th class="p-3 text-left">Last active</th>
                                    <th class="p-3 text-left">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse($users as $user)
                                    <tr class="hover:bg-gray-50 transition">
                                        <!-- 1. Name -->
                                        <td class="p-3 font-semibold text-gray-800">{{ $user->name }}</td>

                                        <!-- 2. Campus email -->
                                        <td class="p-3 text-gray-600">{{ $user->email }}</td>

                                        <!-- 3. Role (dropdown ganti role, kecuali akun sendiri) -->
                                        <td class="p-3">
                                            @if(Auth::id() === $user->id)
                                                <span class="bg-gray-100 px-2.5 py-1 rounded-lg text-[11px] font-medium text-gray-700">{{ $roleLabels[$user->role] ?? $user->role }}</span>
                                            @else
                                                <form action="{{ route('admin.users.update-role', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="role" onchange="this.form.submit()" class="bg-gray-100 border border-gray-200 rounded-lg px-2 py-1 text-[11px] font-medium text-gray-700 cursor-pointer">
                                                        @foreach($roleLabels as $value => $label)
                                                            <option value="{{ $value }}" {{ $user->role === $value ? 'selected' : '' }}>{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </form>
                                            @endif
                                        </td>

                                        <!-- 4. Unit -->
                                        <td class="p-3 text-gray-600">{{ $user->unit ?? '-' }}</td>

                                        <!-- 5. Status -->
                                        <td class="p-3">
                                            @if($user->isActive())
                                                <span class="bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full text-[10px] font-semibold">Active</span>
                                            @else
                                                <span class="bg-rose-100 text-rose-800 px-2.5 py-0.5 rounded-full text-[10px] font-semibold">Suspended</span>
                                            @endif
                                        </td>

                                        <!-- 6. Last active -->
                                        <td class="p-3 text-gray-500 text-xs">{{ $user->updated_at ? $user->updated_at->diffForHumans() : 'Just now' }}</td>

                                        <!-- 7. Action -->
                                        <td class="p-3">
                                            @if(Auth::id() === $user->id)
                                                <span class="text-xs text-gray-400 italic">Your account</span>
                                            @else
                                                <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="text-xs font-semibold hover:underline {{ $user->isActive() ? 'text-rose-600' : 'text-emerald-600' }}">
                                                        {{ $user->isActive() ? 'Suspend' : 'Activate' }}
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
                    <p class="text-[11px] text-gray-500 italic">Role changes take effect on the user's next page load.</p>
                </div>

                <!-- TAB 2: ADD ACCOUNTS -->
                <div id="content-add" class="tab-content hidden">
                    <h2 class="font-bold text-sm text-[#3b2b2c]">Add officer, admin, or user account</h2>
                    <p class="text-xs text-gray-400 mb-6">Create account credentials. The account is active immediately and can be used to log in.</p>

                    <form class="space-y-4 max-w-3xl" action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">Full name</label>
                                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Bagus Tri Prakoso" class="w-full bg-[#f8f4f4] border-0 rounded-xl p-2.5 text-xs text-gray-700 focus:ring-1 focus:ring-pink-300 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">Student ID / Employee ID</label>
                                <input type="text" name="nim_nip" value="{{ old('nim_nip') }}" placeholder="19870412 201004 1 002" class="w-full bg-[#f8f4f4] border-0 rounded-xl p-2.5 text-xs text-gray-700 focus:ring-1 focus:ring-pink-300 focus:outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">Campus email</label>
                                <input type="email" name="email" value="{{ old('email') }}" required placeholder="name@kampus.ac.id" class="w-full bg-[#f8f4f4] border-0 rounded-xl p-2.5 text-xs text-gray-700 focus:ring-1 focus:ring-pink-300 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">Temporary Password</label>
                                <div class="relative">
                                    <input type="password" id="temporary_password" name="password" required minlength="8" placeholder="Min. 8 characters" class="w-full bg-[#f8f4f4] border-0 rounded-xl p-2.5 pr-10 text-xs text-gray-700 focus:ring-1 focus:ring-pink-300 focus:outline-none">

                                    <!-- Tombol Ikon Mata -->
                                    <button type="button" onclick="togglePasswordVisibility()" aria-label="Show or hide password" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 focus:outline-none">
                                        <!-- Ikon Mata Terbuka (Default) -->
                                        <svg id="eye-icon-show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <!-- Ikon Mata Tertutup (Hidden) -->
                                        <svg id="eye-icon-hide" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                                <span class="text-[10px] text-gray-400 mt-0.5 block">Used for initial login.</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Placement Unit -->
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">Placement unit</label>
                                <select name="unit" class="w-full bg-[#f8f4f4] border-0 rounded-xl p-2.5 text-xs text-gray-700 focus:ring-1 focus:ring-pink-300 focus:outline-none cursor-pointer">
                                    <option value="Gedung Rektorat">Gedung Rektorat</option>
                                    <option value="BAUK">BAUK</option>
                                    <option value="Gedung A">Gedung A</option>
                                    <option value="Gedung C">Gedung C</option>
                                </select>
                            </div>

                            <!-- Account Role -->
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">Role</label>
                                <select name="role" required class="w-full bg-[#f8f4f4] border-0 rounded-xl p-2.5 text-xs text-gray-700 focus:ring-1 focus:ring-pink-300 focus:outline-none cursor-pointer">
                                    <option value="{{ User::ROLE_PETUGAS }}" {{ old('role') === User::ROLE_PETUGAS ? 'selected' : '' }}>Officer — manages schedules and approves bookings</option>
                                    <option value="{{ User::ROLE_PENGGUNA }}" {{ old('role') === User::ROLE_PENGGUNA ? 'selected' : '' }}>User — books and utilizes campus facilities</option>
                                    <option value="{{ User::ROLE_ADMIN }}" {{ old('role') === User::ROLE_ADMIN ? 'selected' : '' }}>Admin — has full system access</option>
                                </select>
                            </div>
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

        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('temporary_password');
            const eyeShow = document.getElementById('eye-icon-show');
            const eyeHide = document.getElementById('eye-icon-hide');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeShow.classList.add('hidden');
                eyeHide.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeShow.classList.remove('hidden');
                eyeHide.classList.add('hidden');
            }
        }

        // Kalau ada error validasi dari form tambah akun, langsung buka tab "Add Account"
        @if($errors->hasAny(['name', 'email', 'password', 'nim_nip', 'unit', 'role']))
            switchTab('add');
        @endif
    </script>

</body>
</html>