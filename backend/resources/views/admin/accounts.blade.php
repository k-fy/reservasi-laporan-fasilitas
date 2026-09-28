<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts - Admin Chloe</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#5c4f50] m-0 p-0 text-white min-h-screen flex flex-col">

    @include('admin.components.header')

    @php
        use App\Models\User;

        $roleLabels = [
            User::ROLE_ADMIN    => 'Admin',
            User::ROLE_PETUGAS  => 'Officer',
            User::ROLE_PENGGUNA => 'User',
        ];

        // Warna badge per role
        $roleColors = [
            User::ROLE_ADMIN    => 'bg-[#5c4f50] text-white',
            User::ROLE_PETUGAS  => 'bg-[#e2b8bc] text-[#4b3839]',
            User::ROLE_PENGGUNA => 'bg-[#f2e6e6] text-[#4b3839]',
        ];
    @endphp

    <div class="flex flex-1">
        @include('admin.components.sidebar')

        <!-- Main Content Area -->
        <main class="flex-1 px-8 py-8">
            <div class="max-w-7xl mx-auto">

                <!-- Judul Halaman -->
                <div class="mb-6">
                    <h1 class="use-radley italic text-4xl text-[#fff5f5]">Accounts</h1>
                    <p class="text-sm text-[#d1c2c2] mt-1">Manage accounts and register new officers.</p>
                </div>

                <!-- Flash Alert -->
                @if(session('success'))
                    <div class="mb-5 flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-xl">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Error Alert -->
                @if($errors->any())
                    <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-800 text-sm px-4 py-3 rounded-xl">
                        <ul class="list-disc pl-5 space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Tab Buttons -->
                <div class="flex gap-2 items-end relative z-10">
                    <button onclick="switchTab('manage')" id="tab-manage" class="px-7 py-3 rounded-t-2xl bg-white text-[#4b3839] font-bold text-sm transition">
                        Manage Accounts
                    </button>
                    <button onclick="switchTab('add')" id="tab-add" class="px-7 py-3 rounded-t-2xl bg-[#ebd3d6] text-[#4b3839] font-medium text-sm transition hover:bg-[#f3e1e3]">
                        Add Account
                    </button>
                </div>

                <!-- Container Utama -->
                <div class="bg-white rounded-b-2xl rounded-tr-2xl p-8 shadow-xl text-[#4b3839] min-h-[480px]">

                    <!-- TAB 1: MANAGE ACCOUNTS -->
                    <div id="content-manage" class="tab-content">
                        <!-- Search & Filter Bar -->
                        <form method="GET" action="{{ route('admin.accounts') }}" class="flex flex-wrap gap-3 mb-6">
                            <div class="relative flex-1 min-w-[240px]">
                                <svg class="w-5 h-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, or Student ID/NIP"
                                       class="w-full pl-11 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-[#fcf7f7] focus:outline-none focus:ring-2 focus:ring-[#e2b8bc] focus:border-transparent">
                            </div>
                            <select name="role" onchange="this.form.submit()" class="px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-[#fcf7f7] text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#e2b8bc] cursor-pointer">
                                <option value="">All roles</option>
                                @foreach($roleLabels as $value => $label)
                                    <option value="{{ $value }}" {{ request('role') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <select name="status" onchange="this.form.submit()" class="px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-[#fcf7f7] text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#e2b8bc] cursor-pointer">
                                <option value="">All statuses</option>
                                <option value="{{ User::STATUS_ACTIVE }}" {{ request('status') === User::STATUS_ACTIVE ? 'selected' : '' }}>Active</option>
                                <option value="{{ User::STATUS_SUSPENDED }}" {{ request('status') === User::STATUS_SUSPENDED ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </form>

                        <!-- Jumlah data -->
                        <p class="text-sm text-gray-500 mb-3">
                            Showing <span class="font-semibold text-[#4b3839]">{{ $users->count() }}</span> account(s)
                        </p>

                        <!-- Table Container -->
                        <div class="overflow-x-auto rounded-2xl border border-[#f2e6e6] mb-4">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-[#f7eced] text-[#86545e]">
                                    <tr>
                                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Name</th>
                                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Campus email</th>
                                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Role</th>
                                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Unit</th>
                                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Status</th>
                                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Last active</th>
                                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#f5eeee] bg-white">
                                    @forelse($users as $user)
                                        <tr class="hover:bg-[#fdf8f8] transition">
                                            <!-- 1. Name (dengan foto / inisial) -->
                                            <td class="px-5 py-4">
                                                <div class="flex items-center gap-3">
                                                    @if($user->photo)
                                                        <img src="{{ asset('storage/'.$user->photo) }}" alt="{{ $user->name }}" class="w-9 h-9 rounded-full object-cover shrink-0">
                                                    @else
                                                        <span class="w-9 h-9 rounded-full bg-[#e2b8bc] text-[#4b3839] flex items-center justify-center text-sm font-semibold shrink-0">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </span>
                                                    @endif
                                                    <div class="min-w-0">
                                                        <p class="font-semibold text-gray-800 truncate">{{ $user->name }}</p>
                                                        @if($user->nim_nip)
                                                            <p class="text-xs text-gray-400">{{ $user->nim_nip }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- 2. Campus email -->
                                            <td class="px-5 py-4 text-gray-600">{{ $user->email }}</td>

                                            <!-- 3. Role (dropdown ganti role, kecuali akun sendiri) -->
                                            <td class="px-5 py-4">
                                                @if(Auth::id() === $user->id)
                                                    <span class="{{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-700' }} px-3 py-1 rounded-full text-xs font-semibold">
                                                        {{ $roleLabels[$user->role] ?? $user->role }}
                                                    </span>
                                                @else
                                                    <form action="{{ route('admin.users.update-role', $user->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <select name="role" onchange="this.form.submit()"
                                                                class="{{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-700' }} border-0 rounded-full pl-3 pr-8 py-1 text-xs font-semibold cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#e2b8bc]">
                                                            @foreach($roleLabels as $value => $label)
                                                                <option value="{{ $value }}" class="bg-white text-gray-700" {{ $user->role === $value ? 'selected' : '' }}>{{ $label }}</option>
                                                            @endforeach
                                                        </select>
                                                    </form>
                                                @endif
                                            </td>

                                            <!-- 4. Unit -->
                                            <td class="px-5 py-4 text-gray-600">{{ $user->unit ?? '-' }}</td>

                                            <!-- 5. Status -->
                                            <td class="px-5 py-4">
                                                @if($user->isActive())
                                                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Suspended
                                                    </span>
                                                @endif
                                            </td>

                                            <!-- 6. Last active -->
                                            <td class="px-5 py-4 text-gray-500">{{ $user->updated_at ? $user->updated_at->diffForHumans() : 'Just now' }}</td>

                                            <!-- 7. Action -->
                                            <td class="px-5 py-4 text-right">
                                                @if(Auth::id() === $user->id)
                                                    <span class="text-sm text-gray-400 italic">Your account</span>
                                                @else
                                                    <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                                class="px-4 py-1.5 rounded-full text-xs font-semibold border transition
                                                                       {{ $user->isActive()
                                                                            ? 'border-rose-200 text-rose-600 hover:bg-rose-50'
                                                                            : 'border-emerald-200 text-emerald-600 hover:bg-emerald-50' }}">
                                                            {{ $user->isActive() ? 'Suspend' : 'Activate' }}
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                                                <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                No users found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <p class="text-xs text-gray-500 italic">Role changes take effect on the user's next page load.</p>
                    </div>

                    <!-- TAB 2: ADD ACCOUNTS -->
                    <div id="content-add" class="tab-content hidden">
                        <h2 class="font-bold text-lg text-[#3b2b2c]">Add officer, admin, or user account</h2>
                        <p class="text-sm text-gray-500 mt-1 mb-7">Create account credentials. The account is active immediately and can be used to log in.</p>

                        <form class="space-y-5 max-w-3xl" action="{{ route('admin.users.store') }}" method="POST">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-semibold mb-1.5 text-gray-700">Full name</label>
                                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Bagus Tri Prakoso"
                                           class="w-full bg-[#f8f4f4] border border-transparent rounded-xl px-4 py-3 text-sm text-gray-700 focus:bg-white focus:border-[#e2b8bc] focus:ring-2 focus:ring-[#e2b8bc]/50 focus:outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-1.5 text-gray-700">Student ID / Employee ID</label>
                                    <input type="text" name="nim_nip" value="{{ old('nim_nip') }}" placeholder="19870412 201004 1 002"
                                           class="w-full bg-[#f8f4f4] border border-transparent rounded-xl px-4 py-3 text-sm text-gray-700 focus:bg-white focus:border-[#e2b8bc] focus:ring-2 focus:ring-[#e2b8bc]/50 focus:outline-none transition">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-semibold mb-1.5 text-gray-700">Campus email</label>
                                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="name@kampus.ac.id"
                                           class="w-full bg-[#f8f4f4] border border-transparent rounded-xl px-4 py-3 text-sm text-gray-700 focus:bg-white focus:border-[#e2b8bc] focus:ring-2 focus:ring-[#e2b8bc]/50 focus:outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-1.5 text-gray-700">Temporary Password</label>
                                    <div class="relative">
                                        <input type="password" id="temporary_password" name="password" required minlength="8" placeholder="Min. 8 characters"
                                               class="w-full bg-[#f8f4f4] border border-transparent rounded-xl px-4 py-3 pr-11 text-sm text-gray-700 focus:bg-white focus:border-[#e2b8bc] focus:ring-2 focus:ring-[#e2b8bc]/50 focus:outline-none transition">

                                        <!-- Tombol Ikon Mata -->
                                        <button type="button" onclick="togglePasswordVisibility()" aria-label="Show or hide password" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                            <svg id="eye-icon-show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg id="eye-icon-hide" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                            </svg>
                                        </button>
                                    </div>
                                    <span class="text-xs text-gray-400 mt-1 block">Used for initial login.</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <!-- Placement Unit -->
                                <div>
                                    <label class="block text-sm font-semibold mb-1.5 text-gray-700">Placement unit</label>
                                    <select name="unit" class="w-full bg-[#f8f4f4] border border-transparent rounded-xl px-4 py-3 text-sm text-gray-700 focus:bg-white focus:border-[#e2b8bc] focus:ring-2 focus:ring-[#e2b8bc]/50 focus:outline-none cursor-pointer">
                                        <option value="Gedung Rektorat">Gedung Rektorat</option>
                                        <option value="BAUK">BAUK</option>
                                        <option value="Gedung A">Gedung A</option>
                                        <option value="Gedung C">Gedung C</option>
                                    </select>
                                </div>

                                <!-- Account Role -->
                                <div>
                                    <label class="block text-sm font-semibold mb-1.5 text-gray-700">Role</label>
                                    <select name="role" required class="w-full bg-[#f8f4f4] border border-transparent rounded-xl px-4 py-3 text-sm text-gray-700 focus:bg-white focus:border-[#e2b8bc] focus:ring-2 focus:ring-[#e2b8bc]/50 focus:outline-none cursor-pointer">
                                        <option value="{{ User::ROLE_PETUGAS }}" {{ old('role') === User::ROLE_PETUGAS ? 'selected' : '' }}>Officer — manages schedules and approves bookings</option>
                                        <option value="{{ User::ROLE_PENGGUNA }}" {{ old('role') === User::ROLE_PENGGUNA ? 'selected' : '' }}>User — books and utilizes campus facilities</option>
                                        <option value="{{ User::ROLE_ADMIN }}" {{ old('role') === User::ROLE_ADMIN ? 'selected' : '' }}>Admin — has full system access</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex gap-3 pt-3">
                                <button type="submit" class="bg-[#86545e] text-white px-7 py-3 rounded-full text-sm font-semibold hover:bg-[#6f4550] shadow transition">Create Account</button>
                                <button type="reset" class="bg-gray-100 text-gray-600 px-6 py-3 rounded-full text-sm font-medium hover:bg-gray-200 transition">Clear form</button>
                            </div>
                        </form>
                    </div>

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