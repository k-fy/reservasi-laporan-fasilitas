<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Data Facilities - Admin Chloe</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}?v=2">
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Alpine.js sudah dimuat oleh admin.components.header, jadi tidak dimuat ulang di sini --}}
</head>
<body class="bg-[#5c4f50] m-0 p-0 text-white min-h-screen flex flex-col overflow-x-hidden">

    @include('admin.components.header')

    @php
        // Dikirim dari AdminController@facilities
        $facilityTypes     = $facilityTypes ?? [];
        $facilityLocations = $facilityLocations ?? collect();
    @endphp

    <div class="flex flex-1">
        @include('admin.components.sidebar')

        <!-- Main Content Area -->
        <main class="flex-1 min-w-0 px-8 py-8" x-data="facilityManager()">
            <div class="max-w-7xl mx-auto flex flex-col gap-6">

                <!-- Judul Halaman + Tombol Tambah -->
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <h1 class="use-radley italic text-4xl text-[#fff5f5]">Master Data Facilities</h1>
                        <p class="text-sm text-[#d1c2c2] mt-1">Manage campus halls, rooms, and equipment inventory.</p>
                    </div>
                    <button type="button" @click="openAddModal()"
                            class="inline-flex items-center gap-2 bg-[#e2b8bc] hover:bg-[#ffdcdc] text-[#42393a] px-6 py-3 rounded-full text-sm font-semibold shadow transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Add Facility
                    </button>
                </div>

                <!-- Flash Message & Error -->
                @if (session('success'))
                    <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-xl">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="bg-rose-50 border border-rose-200 text-rose-800 text-sm px-4 py-3 rounded-xl">
                        <ul class="list-disc pl-5 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Kartu Utama -->
                <div class="bg-white rounded-2xl p-8 shadow-xl text-[#4b3839]">

                    <!-- Search & Filter Bar -->
                    <form method="GET" action="{{ route('admin.facilities') }}" class="flex flex-wrap gap-3 mb-6">
                        <div class="relative flex-1 min-w-[240px]">
                            <svg class="w-5 h-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search facility name..."
                                   class="w-full pl-11 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-[#fcf7f7] focus:outline-none focus:ring-2 focus:ring-[#e2b8bc] focus:border-transparent">
                        </div>

                        <select name="type" onchange="this.form.submit()" class="px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-[#fcf7f7] text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#e2b8bc] cursor-pointer">
                            <option value="">All types</option>
                            @foreach ($facilityTypes as $value => $label)
                                <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>

                        <select name="location" onchange="this.form.submit()" class="px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-[#fcf7f7] text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#e2b8bc] cursor-pointer">
                            <option value="">All locations</option>
                            @foreach ($facilityLocations as $location)
                                <option value="{{ $location }}" {{ request('location') == $location ? 'selected' : '' }}>{{ $location }}</option>
                            @endforeach
                        </select>

                        <select name="status" onchange="this.form.submit()" class="px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-[#fcf7f7] text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#e2b8bc] cursor-pointer">
                            <option value="">All statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>

                        @if (request()->hasAny(['search', 'type', 'location', 'status']))
                            <a href="{{ route('admin.facilities') }}" class="px-4 py-2.5 text-sm font-medium text-[#86545e] hover:underline self-center">Reset</a>
                        @endif
                    </form>

                    <!-- Jumlah data -->
                    <p class="text-sm text-gray-500 mb-3">
                        Showing <span class="font-semibold text-[#4b3839]">{{ isset($facilities) ? count($facilities) : 0 }}</span> facility(ies)
                    </p>

                    <!-- Table -->
                    <div class="overflow-x-auto rounded-2xl border border-[#f2e6e6]">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-[#f7eced] text-[#86545e]">
                                <tr>
                                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Nama</th>
                                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Tipe</th>
                                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Lokasi</th>
                                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Kapasitas</th>
                                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Status</th>
                                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Deskripsi</th>
                                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#f5eeee] bg-white">
                                @if (isset($facilities) && count($facilities) > 0)
                                    @foreach ($facilities as $facility)
                                        <tr class="hover:bg-[#fdf8f8] transition">
                                            <td class="px-5 py-4">
                                                <div class="flex items-center gap-3">
                                                    @if ($facility->image)
                                                        <img src="{{ asset('storage/'.$facility->image) }}" alt="{{ $facility->name }}" class="w-12 h-12 rounded-xl object-cover shrink-0">
                                                    @else
                                                        <span class="w-12 h-12 rounded-xl bg-[#f2e6e6] text-[#b89b9e] flex items-center justify-center shrink-0">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                        </span>
                                                    @endif
                                                    <div class="min-w-0">
                                                        <p class="font-semibold text-gray-800">{{ $facility->name }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 text-gray-600">{{ $facilityTypes[$facility->type] ?? ($facility->type ?? '-') }}</td>
                                            <td class="px-5 py-4 text-gray-600">{{ $facility->location ?? '-' }}</td>
                                            <td class="px-5 py-4 text-gray-600 whitespace-nowrap">{{ $facility->capacity }} <span class="text-gray-400">{{ $facility->type === 'alat' ? 'pcs' : 'orang' }}</span></td>
                                            <td class="px-5 py-4">
                                                @if ($facility->status === 'active')
                                                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                                    </span>
                                                @elseif ($facility->status === 'maintenance')
                                                    <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Dalam perbaikan
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Nonaktif
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-5 py-4 text-gray-600 max-w-xs">
                                                <p class="line-clamp-2" title="{{ $facility->description }}">{{ $facility->description ?: '—' }}</p>
                                            </td>
                                            <td class="px-5 py-4">
                                                <div class="flex justify-end gap-2">
                                                    <button type="button" @click='openEditModal(@json($facility))'
                                                            class="inline-flex items-center gap-1 px-4 py-1.5 rounded-full text-xs font-semibold border border-[#e2b8bc] text-[#86545e] hover:bg-[#fcf1f2] transition">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                        Ubah
                                                    </button>
                                                    <button type="button" @click='openConfirmModal(@json($facility))'
                                                            class="px-4 py-1.5 rounded-full text-xs font-semibold border transition whitespace-nowrap
                                                                   {{ $facility->status === 'active'
                                                                        ? 'border-rose-200 text-rose-600 hover:bg-rose-50'
                                                                        : 'border-emerald-200 text-emerald-600 hover:bg-emerald-50' }}">
                                                        {{ $facility->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                                            <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                            No facilities found.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <p class="text-xs text-gray-500 italic mt-3">Tidak ada penghapusan permanen — fasilitas hanya dinonaktifkan (soft delete).</p>
                </div>
            </div>

            <!-- Modal Konfirmasi Aktifkan / Nonaktifkan -->
            <div x-show="confirmModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" style="display: none;" x-transition.opacity>
                <div class="bg-white text-[#4b3839] rounded-2xl p-7 w-full max-w-md shadow-xl" @click.away="confirmModal = false">
                    <div class="w-12 h-12 rounded-full mb-4 flex items-center justify-center"
                         :class="selectedFacility?.status === 'active' ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600'">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                    </div>

                    <h3 class="font-bold text-lg mb-1" x-text="selectedFacility?.status === 'active' ? 'Nonaktifkan fasilitas?' : 'Aktifkan fasilitas?'"></h3>
                    <p class="text-sm text-gray-600 mb-1">
                        Status fasilitas <span class="font-semibold text-[#4b3839]" x-text="selectedFacility?.name"></span> akan diubah.
                    </p>
                    <p class="text-sm text-gray-400 mb-6">
                        Status saat ini: <span class="font-medium" x-text="statusLabel(selectedFacility?.status)"></span>
                    </p>

                    <div class="flex justify-end gap-3">
                        <button @click="confirmModal = false" type="button" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full text-sm font-medium transition">
                            Batal
                        </button>
                        <template x-if="selectedFacility">
                            <form :action="`/admin/facilities/${selectedFacility.id}/toggle-status`" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        :class="selectedFacility.status === 'active'
                                                ? 'bg-rose-600 hover:bg-rose-700'
                                                : 'bg-emerald-600 hover:bg-emerald-700'"
                                        class="px-6 py-2.5 text-white rounded-full text-sm font-semibold transition"
                                        x-text="selectedFacility.status === 'active' ? 'Nonaktifkan' : 'Aktifkan'">
                                </button>
                            </form>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Modal Form (Tambah & Ubah Fasilitas) -->
            <div x-show="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" style="display: none;" x-transition.opacity>
                <div class="bg-white text-[#4b3839] rounded-2xl w-full max-w-2xl shadow-xl max-h-[92vh] flex flex-col" @click.away="showModal = false">

                    <!-- Judul modal -->
                    <div class="flex items-start justify-between px-8 pt-7 pb-4 border-b border-gray-100">
                        <div>
                            <h2 class="text-xl font-bold" x-text="editMode ? 'Ubah Fasilitas' : 'Tambah Fasilitas Baru'"></h2>
                            <p class="text-sm text-gray-500 mt-0.5">Data ini tampil di halaman detail fasilitas untuk pengguna.</p>
                        </div>
                        <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600" aria-label="Tutup">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form :action="formAction" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto px-8 py-6 space-y-6 text-sm">
                        @csrf
                        <template x-if="editMode">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        <!-- Menandai fasilitas yang sedang diubah, agar modal bisa dibuka ulang jika validasi gagal -->
                        <input type="hidden" name="_facility_id" :value="editMode ? editingId : ''">

                        <!-- ===== Bagian 1: Foto ===== -->
                        <section>
                            <p class="text-xs font-semibold uppercase tracking-wider text-[#86545e] mb-3">Foto</p>
                            <div class="flex items-center gap-5">
                                <div class="w-32 h-24 rounded-xl bg-[#f2e6e6] overflow-hidden flex items-center justify-center shrink-0">
                                    <template x-if="imagePreview">
                                        <img :src="imagePreview" class="w-full h-full object-cover" alt="Pratinjau foto">
                                    </template>
                                    <template x-if="!imagePreview">
                                        <svg class="w-8 h-8 text-[#b89b9e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </template>
                                </div>
                                <div>
                                    <label class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-[#e2b8bc] text-[#86545e] font-semibold cursor-pointer hover:bg-[#fcf1f2] transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        <span x-text="imagePreview ? 'Ganti Foto' : 'Unggah Foto'"></span>
                                        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="hidden" x-ref="imageInput" @change="previewImage($event)">
                                    </label>
                                    <p class="text-xs text-gray-400 mt-2">JPG, PNG, atau WEBP. Maksimal 2 MB.</p>
                                    <p class="text-xs text-rose-600 mt-1" x-show="imageError" x-text="imageError"></p>
                                </div>
                            </div>
                        </section>

                        <!-- ===== Bagian 2: Informasi Utama ===== -->
                        <section class="space-y-4">
                            <p class="text-xs font-semibold uppercase tracking-wider text-[#86545e]">Informasi Utama</p>

                            <div>
                                <label class="block font-semibold mb-1.5 text-gray-700">Nama Fasilitas <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" x-model="form.name" required maxlength="255" placeholder="mis. Aula Utama &quot;Beau&quot;"
                                       class="w-full bg-[#f8f4f4] border border-transparent rounded-xl px-4 py-3 text-sm text-gray-700 focus:bg-white focus:border-[#e2b8bc] focus:ring-2 focus:ring-[#e2b8bc]/50 focus:outline-none transition">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-semibold mb-1.5 text-gray-700">Tipe <span class="text-rose-500">*</span></label>
                                    <select name="type" x-model="form.type" required
                                            class="w-full bg-[#f8f4f4] border border-transparent rounded-xl px-4 py-3 text-sm text-gray-700 focus:bg-white focus:border-[#e2b8bc] focus:ring-2 focus:ring-[#e2b8bc]/50 focus:outline-none cursor-pointer">
                                        @foreach ($facilityTypes as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-semibold mb-1.5 text-gray-700">Lokasi <span class="text-rose-500">*</span></label>
                                    <input type="text" name="location" x-model="form.location" list="location-options" required maxlength="255" placeholder="Pilih atau ketik lokasi baru"
                                           class="w-full bg-[#f8f4f4] border border-transparent rounded-xl px-4 py-3 text-sm text-gray-700 focus:bg-white focus:border-[#e2b8bc] focus:ring-2 focus:ring-[#e2b8bc]/50 focus:outline-none transition">
                                    <datalist id="location-options">
                                        @foreach ($facilityLocations as $location)
                                            <option value="{{ $location }}"></option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-semibold mb-1.5 text-gray-700">
                                        Kapasitas <span class="text-rose-500">*</span>
                                        <span class="font-normal text-gray-400" x-text="form.type === 'alat' ? '(pcs)' : '(orang)'"></span>
                                    </label>
                                    <input type="number" name="capacity" x-model="form.capacity" min="0" step="1" required
                                           class="w-full bg-[#f8f4f4] border border-transparent rounded-xl px-4 py-3 text-sm text-gray-700 focus:bg-white focus:border-[#e2b8bc] focus:ring-2 focus:ring-[#e2b8bc]/50 focus:outline-none transition">
                                </div>
                                <div>
                                    <label class="block font-semibold mb-1.5 text-gray-700">Luas Area</label>
                                    <input type="text" name="area" x-model="form.area" maxlength="100" placeholder="mis. 25 x 15 Meter"
                                           class="w-full bg-[#f8f4f4] border border-transparent rounded-xl px-4 py-3 text-sm text-gray-700 focus:bg-white focus:border-[#e2b8bc] focus:ring-2 focus:ring-[#e2b8bc]/50 focus:outline-none transition">
                                </div>
                            </div>

                            <div>
                                <label class="block font-semibold mb-1.5 text-gray-700">Status <span class="text-rose-500">*</span></label>
                                <select name="status" x-model="form.status" required
                                        class="w-full bg-[#f8f4f4] border border-transparent rounded-xl px-4 py-3 text-sm text-gray-700 focus:bg-white focus:border-[#e2b8bc] focus:ring-2 focus:ring-[#e2b8bc]/50 focus:outline-none cursor-pointer">
                                    <option value="active">Aktif</option>
                                    <option value="maintenance">Dalam Perbaikan</option>
                                    <option value="inactive">Nonaktif</option>
                                </select>
                            </div>
                        </section>

                        <!-- ===== Bagian 3: Kontak ===== -->
                        <section class="space-y-4">
                            <p class="text-xs font-semibold uppercase tracking-wider text-[#86545e]">Kontak</p>

                            <div>
                                <label class="block font-semibold mb-1.5 text-gray-700">Nomor Kontak Pengelola</label>
                                <input type="tel" name="contact_phone" x-model="form.contact_phone" maxlength="20" pattern="[0-9+\-\s]+" placeholder="0812-1001-1002"
                                       title="Hanya angka, spasi, tanda + atau -"
                                       class="w-full sm:w-1/2 bg-[#f8f4f4] border border-transparent rounded-xl px-4 py-3 text-sm text-gray-700 focus:bg-white focus:border-[#e2b8bc] focus:ring-2 focus:ring-[#e2b8bc]/50 focus:outline-none transition">
                                <p class="text-xs text-gray-400 mt-1">Peminjaman fasilitas tidak dipungut biaya.</p>
                            </div>
                        </section>

                        <!-- ===== Bagian 4: Detail ===== -->
                        <section class="space-y-4">
                            <p class="text-xs font-semibold uppercase tracking-wider text-[#86545e]">Detail</p>

                            <div>
                                <label class="block font-semibold mb-1.5 text-gray-700">Fasilitas yang Disediakan</label>
                                <input type="text" name="amenities" x-model="form.amenities" maxlength="1000" placeholder="mis. AC, Proyektor, Sound System, Wifi"
                                       class="w-full bg-[#f8f4f4] border border-transparent rounded-xl px-4 py-3 text-sm text-gray-700 focus:bg-white focus:border-[#e2b8bc] focus:ring-2 focus:ring-[#e2b8bc]/50 focus:outline-none transition">
                                <p class="text-xs text-gray-400 mt-1">Pisahkan dengan koma. Tampil sebagai daftar "Facilities Provided".</p>
                                <!-- Pratinjau daftar fasilitas -->
                                <div class="flex flex-wrap gap-1.5 mt-2" x-show="amenityList().length">
                                    <template x-for="item in amenityList()" :key="item">
                                        <span class="bg-[#f7eced] text-[#86545e] px-3 py-1 rounded-full text-xs font-medium" x-text="item"></span>
                                    </template>
                                </div>
                            </div>

                            <div>
                                <label class="block font-semibold mb-1.5 text-gray-700">Deskripsi</label>
                                <textarea name="description" x-model="form.description" rows="4" maxlength="2000" placeholder="Ceritakan singkat tentang fasilitas ini..."
                                          class="w-full bg-[#f8f4f4] border border-transparent rounded-xl px-4 py-3 text-sm text-gray-700 focus:bg-white focus:border-[#e2b8bc] focus:ring-2 focus:ring-[#e2b8bc]/50 focus:outline-none transition resize-none"></textarea>
                                <p class="text-xs text-gray-400 mt-1 text-right"><span x-text="(form.description || '').length"></span>/2000</p>
                            </div>
                        </section>

                        <!-- Tombol -->
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <button type="button" @click="showModal = false" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full font-medium transition">Batal</button>
                            <button type="submit" :disabled="!!imageError"
                                    class="px-6 py-2.5 bg-[#86545e] hover:bg-[#6f4550] disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-full font-semibold shadow transition"
                                    x-text="editMode ? 'Simpan Perubahan' : 'Tambahkan'"></button>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>

    <script>
        function facilityManager() {
            const storageUrl = @json(asset('storage'));
            const firstType  = @json(array_key_first($facilityTypes) ?? '');
            const maxImageSize = 2 * 1024 * 1024; // 2 MB

            const defaultForm = () => ({
                name: '',
                type: firstType,
                location: '',
                capacity: 0,
                area: '',
                contact_phone: '',
                amenities: '',
                description: '',
                status: 'active',
            });

            return {
                selectedFacility: null,
                confirmModal: false,
                showModal: false,
                editMode: false,
                editingId: null,
                formAction: '',
                form: defaultForm(),
                imagePreview: null,
                imageError: '',

                // Jika validasi server gagal, buka lagi modal dengan isian sebelumnya
                init() {
                    @if ($errors->any() && old('name') !== null)
                        const oldId = @json(old('_facility_id'));
                        this.editMode   = !!oldId;
                        this.editingId  = oldId || null;
                        this.formAction = oldId ? `/admin/facilities/${oldId}` : @json(route('admin.facilities.store'));
                        this.form = {
                            name:           @json(old('name', '')),
                            type:           @json(old('type', '')) || firstType,
                            location:       @json(old('location', '')),
                            capacity:       @json(old('capacity', 0)),
                            area:           @json(old('area', '')),
                            contact_phone:  @json(old('contact_phone', '')),
                            amenities:      @json(old('amenities', '')),
                            description:    @json(old('description', '')),
                            status:         @json(old('status', 'active')),
                        };
                        this.showModal = true;
                    @endif
                },

                statusLabel(status) {
                    return { active: 'Aktif', maintenance: 'Dalam perbaikan', inactive: 'Nonaktif' }[status] ?? status;
                },

                amenityList() {
                    return [...new Set((this.form.amenities || '').split(',').map(i => i.trim()).filter(Boolean))];
                },

                resetImage() {
                    this.imagePreview = null;
                    this.imageError = '';
                    if (this.$refs.imageInput) this.$refs.imageInput.value = '';
                },

                // Validasi sisi client untuk foto + tampilkan pratinjau
                previewImage(event) {
                    const file = event.target.files[0];
                    this.imageError = '';
                    if (!file) return;

                    if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
                        this.imageError = 'Format foto harus JPG, PNG, atau WEBP.';
                        event.target.value = '';
                        return;
                    }
                    if (file.size > maxImageSize) {
                        this.imageError = 'Ukuran foto maksimal 2 MB.';
                        event.target.value = '';
                        return;
                    }
                    this.imagePreview = URL.createObjectURL(file);
                },

                openAddModal() {
                    this.editMode = false;
                    this.editingId = null;
                    this.formAction = @json(route('admin.facilities.store'));
                    this.form = defaultForm();
                    this.resetImage();
                    this.showModal = true;
                },

                openEditModal(facility) {
                    this.editMode = true;
                    this.editingId = facility.id;
                    this.formAction = `/admin/facilities/${facility.id}`;
                    this.form = {
                        name:           facility.name || '',
                        type:           facility.type || firstType,
                        location:       facility.location || '',
                        capacity:       facility.capacity ?? 0,
                        area:           facility.area || '',
                        contact_phone:  facility.contact_phone || '',
                        amenities:      facility.amenities || '',
                        description:    facility.description || '',
                        status:         facility.status || 'active',
                    };
                    this.resetImage();
                    this.imagePreview = facility.image ? `${storageUrl}/${facility.image}` : null;
                    this.showModal = true;
                },

                openConfirmModal(facility) {
                    this.selectedFacility = facility;
                    this.confirmModal = true;
                },
            };
        }
    </script>
</body>
</html>