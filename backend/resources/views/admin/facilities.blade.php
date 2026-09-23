<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Data Facilities - Admin Chloe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@1,600&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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

        <!-- Main Content Area -->
        <main class="flex-1 p-6 flex flex-col gap-6" x-data="facilityManager()">
            
            <!-- Header Section -->
            <div>
                <h1 class="text-3xl font-['Playfair_Display',serif] italic font-semibold text-[#fff5f5]">Master Data Facilities</h1>
                <p class="text-xs text-[#d1c2c2] mt-0.5">Admins can manage campus halls, rooms, and equipment inventory.</p>
            </div>

            <!-- Flash Message & Error -->
            @if (session('success'))
                <div class="bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs px-4 py-2 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-rose-100 border border-rose-300 text-rose-800 text-xs px-4 py-2 rounded-xl">
                    <ul class="list-disc pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Search, Filters, and Add Button Bar -->
            <form method="GET" action="{{ route('admin.facilities') }}" class="flex items-center justify-between gap-3">
                <div class="flex-1 max-w-md">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search facility name..." class="w-full bg-white rounded-full px-4 py-1.5 text-xs text-[#4b3839] focus:outline-none shadow-sm">
                </div>
                <div class="flex items-center gap-2">
                    <select name="type" onchange="this.form.submit()" class="bg-white text-[#4b3839] text-xs px-3 py-1.5 rounded-full font-medium border-0 focus:outline-none shadow-sm cursor-pointer">
                        <option value="">Type</option>
                        <option value="Classroom" {{ request('type') == 'Classroom' ? 'selected' : '' }}>Classroom</option>
                        <option value="Laboratory" {{ request('type') == 'Laboratory' ? 'selected' : '' }}>Laboratory</option>
                        <option value="Auditorium" {{ request('type') == 'Auditorium' ? 'selected' : '' }}>Auditorium</option>
                    </select>

                    <select name="location" onchange="this.form.submit()" class="bg-white text-[#4b3839] text-xs px-3 py-1.5 rounded-full font-medium border-0 focus:outline-none shadow-sm cursor-pointer">
                        <option value="">Location</option>
                        <option value="Gedung A - Lt.3" {{ request('location') == 'Gedung A - Lt.3' ? 'selected' : '' }}>Gedung A - Lt.3</option>
                        <option value="Gedung C - Lt.2" {{ request('location') == 'Gedung C - Lt.2' ? 'selected' : '' }}>Gedung C - Lt.2</option>
                        <option value="Gedung Rektorat" {{ request('location') == 'Gedung Rektorat' ? 'selected' : '' }}>Gedung Rektorat</option>
                    </select>

                    <select name="status" onchange="this.form.submit()" class="bg-white text-[#4b3839] text-xs px-3 py-1.5 rounded-full font-medium border-0 focus:outline-none shadow-sm cursor-pointer">
                        <option value="">Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>

                    <button type="button" @click="openAddModal()" class="bg-[#fcebeb] hover:bg-[#f5d0d0] text-[#4b3839] px-4 py-1.5 rounded-full text-xs font-semibold shadow-sm transition flex items-center gap-1 ml-2">
                        + Add Facility
                    </button>
                </div>
            </form>

            <!-- Table Container -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg text-[#4b3839]">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="bg-[#ebd3d6] text-[#4b3839] font-semibold">
                        <tr>
                            <th class="py-3 px-4 w-8"></th>
                            <th class="py-3 px-4">Nama</th>
                            <th class="py-3 px-4">Lokasi</th>
                            <th class="py-3 px-4">Kapasitas</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Deskripsi</th>
                            <th class="py-3 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @if (isset($facilities) && count($facilities) > 0)
                            @foreach ($facilities as $facility)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-3 px-4">
                                        <input type="checkbox" class="rounded border-gray-300 text-[#4b3839] focus:ring-0 accent-[#5c4f50] cursor-pointer">
                                    </td>
                                    <td class="py-3 px-4 font-medium text-gray-700">{{ $facility->name }}</td>
                                    <td class="py-3 px-4 text-gray-600">{{ $facility->location ?? '-' }}</td>
                                    <td class="py-3 px-4 text-gray-600">{{ $facility->capacity }}</td>
                                    <td class="py-3 px-4">
                                        @if ($facility->status === 'active')
                                            <span class="bg-[#e2f0d9] text-[#2e6b27] px-2.5 py-0.5 rounded-md text-[10px] font-medium">Aktif</span>
                                        @elseif ($facility->status === 'maintenance')
                                            <span class="bg-[#fff2cc] text-[#8a6d3b] px-2.5 py-0.5 rounded-md text-[10px] font-medium">Dalam perbaikan</span>
                                        @else
                                            <span class="bg-[#fcebeb] text-[#d9534f] px-2.5 py-0.5 rounded-md text-[10px] font-medium">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-gray-600">{{ $facility->description ?? '—' }}</td>
                                    <td class="py-3 px-4">
                                        <button type="button" @click='openEditModal(@json($facility))' class="text-rose-500 hover:underline font-medium inline-flex items-center gap-1">
                                            ✏ Ubah
                                        </button>
                                        <span class="text-gray-300 mx-1">|</span>
                                        <button type="button" @click='openConfirmModal(@json($facility))' class="{{ $facility->status === "active" ? "text-gray-700" : "text-emerald-600" }} hover:underline font-medium">
                                            {{ $facility->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="py-6 text-center text-gray-400">No facilities found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Nonactivation Confirmation Section -->
            <div class="bg-[#ebd3d6] rounded-2xl p-6 text-[#4b3839] shadow-md" x-show="confirmModal" style="display: none;" x-transition>
                <h3 class="font-bold text-sm mb-3">Nonactivation Confirmation</h3>
                
                <div class="bg-[#f8eeee] border border-dashed border-[#b89b9e] rounded-xl p-4 mb-4 text-xs">
                    <p class="font-semibold" x-text="`Are you sure you want to change status for: ${selectedFacility?.name}?`"></p>
                    <p class="text-gray-500 text-[11px] mt-1" x-text="`Current status: ${selectedFacility?.status}`"></p>
                </div>

                <div class="flex items-center gap-2 mb-3">
                    <template x-if="selectedFacility">
                        <form :action="`/admin/facilities/${selectedFacility.id}/toggle-status`" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-[#fcebeb] hover:bg-rose-100 text-rose-700 border border-rose-200 px-4 py-1.5 rounded-lg text-xs font-semibold transition">
                                Toggle Status
                            </button>
                        </form>
                    </template>
                    <button @click="confirmModal = false" type="button" class="bg-[#6b5859] hover:bg-[#5a494a] text-white px-4 py-1.5 rounded-lg text-xs font-medium transition">
                        Batal
                    </button>
                </div>

                <p class="text-[11px] text-[#6b5859] italic">Tidak ada penghapusan permanen, hanya soft delete.</p>
            </div>

            <!-- Modal Form (Tambah & Ubah Fasilitas) -->
            <div x-show="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" style="display: none;" x-transition>
                <div class="bg-white text-[#4b3839] rounded-2xl p-6 w-full max-w-lg shadow-xl" @click.away="showModal = false">
                    <h2 class="text-lg font-bold mb-4" x-text="editMode ? 'Ubah Fasilitas' : 'Tambah Fasilitas Baru'"></h2>
                    
                    <form :action="formAction" method="POST" class="space-y-4 text-xs">
                        @csrf
                        <template x-if="editMode">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div>
                            <label class="block font-semibold mb-1">Nama Fasilitas</label>
                            <input type="text" name="name" x-model="form.name" required class="w-full border rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-pink-300">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold mb-1">Tipe</label>
                                <select name="type" x-model="form.type" required class="w-full border rounded-xl px-3 py-2 text-xs focus:outline-none">
                                    <option value="Classroom">Classroom</option>
                                    <option value="Laboratory">Laboratory</option>
                                    <option value="Auditorium">Auditorium</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold mb-1">Kapasitas</label>
                                <input type="number" name="capacity" x-model="form.capacity" required class="w-full border rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-pink-300">
                            </div>
                        </div>

                        <div>
                            <label class="block font-semibold mb-1">Lokasi</label>
                            <select name="location" x-model="form.location" required class="w-full border rounded-xl px-3 py-2 text-xs focus:outline-none">
                                <option value="Gedung A - Lt.3">Gedung A - Lt.3</option>
                                <option value="Gedung C - Lt.2">Gedung C - Lt.2</option>
                                <option value="Gedung Rektorat">Gedung Rektorat</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold mb-1">Deskripsi</label>
                            <textarea name="description" x-model="form.description" rows="2" class="w-full border rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-pink-300 resize-none"></textarea>
                        </div>

                        <div>
                            <label class="block font-semibold mb-1">Status</label>
                            <select name="status" x-model="form.status" required class="w-full border rounded-xl px-3 py-2 text-xs focus:outline-none">
                                <option value="active">Aktif</option>
                                <option value="maintenance">Dalam Perbaikan</option>
                                <option value="inactive">Nonaktif</option>
                            </select>
                        </div>

                        <div class="flex justify-end gap-2 pt-3 border-t">
                            <button type="button" @click="showModal = false" class="px-5 py-2 bg-gray-100 hover:bg-gray-200 rounded-xl font-medium transition">Batal</button>
                            <button type="submit" class="px-5 py-2 bg-[#785b5d] hover:bg-[#5c4f50] text-white rounded-xl font-medium transition" x-text="editMode ? 'Simpan Perubahan' : 'Tambahkan'"></button>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>

    <script>
        function facilityManager() {
            return {
                selectedFacility: null,
                confirmModal: false,
                showModal: false,
                editMode: false,
                formAction: '',
                form: { name: '', type: 'Classroom', location: 'Gedung A - Lt.3', capacity: 0, description: '', status: 'active' },
                openAddModal() {
                    this.editMode = false;
                    this.formAction = '{{ route("admin.facilities.store") }}';
                    this.form = { name: '', type: 'Classroom', location: 'Gedung A - Lt.3', capacity: 0, description: '', status: 'active' };
                    this.showModal = true;
                },
                openEditModal(facility) {
                    this.editMode = true;
                    this.formAction = `/admin/facilities/${facility.id}`;
                    this.form = {
                        name: facility.name || '',
                        type: facility.type || 'Classroom',
                        location: facility.location || 'Gedung A - Lt.3',
                        capacity: facility.capacity || 0,
                        description: facility.description || '',
                        status: facility.status || 'active'
                    };
                    this.showModal = true;
                },
                openConfirmModal(facility) {
                    this.selectedFacility = facility;
                    this.confirmModal = true;
                }
            }
        }
    </script>
</body>
</html>