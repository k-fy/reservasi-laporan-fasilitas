<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recap - Admin Chloe</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#5c4f50] m-0 p-0 text-white min-h-screen flex flex-col">

    @include('admin.components.header')

    @php
        // ------------------------------------------------------------------
        // Data tabel rekap.
        // Jika controller sudah mengirim $occupancyRows / $damageRows, data asli yang dipakai.
        // Jika belum, tabel memakai data contoh di bawah ini (SEMENTARA).
        // ------------------------------------------------------------------
        $occupancyRows = $occupancyRows ?? [
            ['location' => 'Gedung Rektorat',           'facilities' => 12, 'usage' => 62, 'hours' => 186, 'rate' => 81],
            ['location' => 'Gedung A - Fakultas Sains', 'facilities' => 18, 'usage' => 88, 'hours' => 220, 'rate' => 74],
            ['location' => 'Gedung C - Laboratorium',   'facilities' => 10, 'usage' => 54, 'hours' => 125, 'rate' => 48],
            ['location' => 'Kawasan Lapangan Luar',     'facilities' => 5,  'usage' => 24, 'hours' => 60,  'rate' => 31],
        ];

        $damageRows = $damageRows ?? [
            ['location' => 'Gedung A - Fakultas Sains', 'total' => 14, 'in_progress' => 3, 'resolved' => 11],
            ['location' => 'Gedung C - Laboratorium',   'total' => 8,  'in_progress' => 1, 'resolved' => 7],
            ['location' => 'Gedung Rektorat',           'total' => 2,  'in_progress' => 0, 'resolved' => 2],
        ];

        // Label & warna tingkat okupansi
        $occupancyLevel = function (int $rate) {
            return match (true) {
                $rate >= 80 => ['Sangat Tinggi', 'bg-emerald-50 text-emerald-700', 'bg-emerald-500'],
                $rate >= 60 => ['Tinggi',        'bg-emerald-50 text-emerald-700', 'bg-emerald-400'],
                $rate >= 40 => ['Sedang',        'bg-amber-50 text-amber-700',     'bg-amber-400'],
                default     => ['Rendah',        'bg-rose-50 text-rose-700',       'bg-rose-400'],
            };
        };

        // Label & warna tingkat kerusakan
        $damageLevel = function (int $total) {
            return match (true) {
                $total >= 10 => ['Tinggi', 'bg-rose-50 text-rose-700',       'bg-rose-500'],
                $total >= 5  => ['Sedang', 'bg-amber-50 text-amber-700',     'bg-amber-500'],
                default      => ['Rendah', 'bg-emerald-50 text-emerald-700', 'bg-emerald-500'],
            };
        };

        $selectedMonth = $selectedMonth ?? date('Y-m');
        $monthLabel = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->translatedFormat('F Y');
    @endphp

    <div class="flex flex-1">
        @include('admin.components.sidebar')

        <!-- Main Content Area -->
        <main class="flex-1 px-8 py-8">
            <div class="max-w-7xl mx-auto flex flex-col gap-6">

                <!-- Judul + Filter Bulan -->
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <h1 class="use-radley italic text-4xl text-[#fff5f5]">Recap</h1>
                        <p class="text-sm text-[#d1c2c2] mt-1">Ringkasan pemakaian dan riwayat kerusakan fasilitas per lokasi.</p>
                    </div>

                    <form method="GET" action="{{ route('admin.recap') }}" class="flex items-center gap-2">
                        <label for="month" class="text-sm text-[#d1c2c2]">Periode</label>
                        <input type="month" id="month" name="month" value="{{ $selectedMonth }}" onchange="this.form.submit()"
                               class="bg-white text-[#4b3839] text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#e2b8bc] cursor-pointer">
                    </form>
                </div>

                <!-- Kartu Statistik Reservasi (data asli dari controller) -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white rounded-2xl p-5 shadow-lg text-[#4b3839]">
                        <p class="text-sm text-gray-500">Total Pengajuan</p>
                        <p class="text-3xl font-bold mt-1">{{ $totalSubmissions ?? 0 }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $monthLabel }}</p>
                    </div>
                    <div class="bg-white rounded-2xl p-5 shadow-lg text-[#4b3839]">
                        <p class="text-sm text-gray-500">Disetujui</p>
                        <p class="text-3xl font-bold mt-1 text-emerald-600">{{ $approvedSubmissions ?? 0 }}</p>
                        <p class="text-xs text-gray-400 mt-1">reservasi</p>
                    </div>
                    <div class="bg-white rounded-2xl p-5 shadow-lg text-[#4b3839]">
                        <p class="text-sm text-gray-500">Ditolak</p>
                        <p class="text-3xl font-bold mt-1 text-rose-600">{{ $rejectedSubmissions ?? 0 }}</p>
                        <p class="text-xs text-gray-400 mt-1">reservasi</p>
                    </div>
                    <div class="bg-[#e2b8bc] rounded-2xl p-5 shadow-lg text-[#42393a]">
                        <p class="text-sm font-medium">Tingkat Persetujuan</p>
                        <p class="text-3xl font-bold mt-1">{{ $approvedPercent ?? 0 }}%</p>
                        <div class="h-2 bg-white/60 rounded-full mt-3 overflow-hidden">
                            <div class="h-full bg-[#86545e] rounded-full" style="width: {{ $approvedPercent ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>

                <div>
                    <!-- Tab Buttons -->
                    <div class="flex gap-2 items-end relative z-10">
                        <button onclick="switchTab('occupancy')" id="tab-occupancy" class="px-7 py-3 rounded-t-2xl bg-white text-[#4b3839] font-bold text-sm transition">
                            Okupansi per Lokasi
                        </button>
                        <button onclick="switchTab('damage')" id="tab-damage" class="px-7 py-3 rounded-t-2xl bg-[#ebd3d6] text-[#4b3839] font-medium text-sm transition hover:bg-[#f3e1e3]">
                            Frekuensi Kerusakan per Lokasi
                        </button>
                    </div>

                    <!-- Kartu Putih Utama -->
                    <div class="bg-white rounded-b-2xl rounded-tr-2xl p-8 shadow-xl text-[#4b3839]">

                        <!-- Pencarian lokasi (menyaring baris tabel secara langsung) -->
                        <div class="relative mb-6 max-w-md">
                            <svg class="w-5 h-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" id="location-search" oninput="filterRows(this.value)" placeholder="Cari nama lokasi..."
                                   class="w-full pl-11 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-[#fcf7f7] focus:outline-none focus:ring-2 focus:ring-[#e2b8bc] focus:border-transparent">
                        </div>

                        <!-- TAB 1: OKUPANSI -->
                        <div id="content-occupancy" class="tab-content">
                            <div class="overflow-x-auto rounded-2xl border border-[#f2e6e6]">
                                <table class="w-full text-left text-sm">
                                    <thead class="bg-[#f7eced] text-[#86545e]">
                                        <tr>
                                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Lokasi Gedung</th>
                                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Total Fasilitas</th>
                                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Total Pemakaian</th>
                                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Total Jam Terpakai</th>
                                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Tingkat Okupansi</th>
                                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#f5eeee] bg-white">
                                        @forelse ($occupancyRows as $row)
                                            @php [$label, $badge, $bar] = $occupancyLevel($row['rate']); @endphp
                                            <tr class="recap-row hover:bg-[#fdf8f8] transition" data-location="{{ strtolower($row['location']) }}">
                                                <td class="px-5 py-4 font-semibold text-gray-800">{{ $row['location'] }}</td>
                                                <td class="px-5 py-4 text-gray-600">{{ $row['facilities'] }} fasilitas</td>
                                                <td class="px-5 py-4 text-gray-600">{{ $row['usage'] }} kali</td>
                                                <td class="px-5 py-4 text-gray-600">{{ $row['hours'] }} jam</td>
                                                <td class="px-5 py-4 min-w-[160px]">
                                                    <div class="flex items-center gap-3">
                                                        <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                                            <div class="h-full {{ $bar }} rounded-full" style="width: {{ $row['rate'] }}%"></div>
                                                        </div>
                                                        <span class="font-semibold text-gray-700 w-10 text-right">{{ $row['rate'] }}%</span>
                                                    </div>
                                                </td>
                                                <td class="px-5 py-4">
                                                    <span class="{{ $badge }} px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">{{ $label }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">Belum ada data okupansi untuk periode ini.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- TAB 2: KERUSAKAN -->
                        <div id="content-damage" class="tab-content hidden">
                            <div class="overflow-x-auto rounded-2xl border border-[#f2e6e6]">
                                <table class="w-full text-left text-sm">
                                    <thead class="bg-[#f7eced] text-[#86545e]">
                                        <tr>
                                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Lokasi Gedung</th>
                                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Total Laporan</th>
                                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Sedang Diperbaiki</th>
                                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Selesai Diperbaiki</th>
                                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider">Tingkat Kerusakan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#f5eeee] bg-white">
                                        @forelse ($damageRows as $row)
                                            @php [$label, $badge, $dot] = $damageLevel($row['total']); @endphp
                                            <tr class="recap-row hover:bg-[#fdf8f8] transition" data-location="{{ strtolower($row['location']) }}">
                                                <td class="px-5 py-4 font-semibold text-gray-800">{{ $row['location'] }}</td>
                                                <td class="px-5 py-4 font-semibold text-gray-700">{{ $row['total'] }} laporan</td>
                                                <td class="px-5 py-4">
                                                    <span class="inline-flex items-center gap-1.5 text-amber-700 font-medium">
                                                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>{{ $row['in_progress'] }}
                                                    </span>
                                                </td>
                                                <td class="px-5 py-4">
                                                    <span class="inline-flex items-center gap-1.5 text-emerald-700 font-medium">
                                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>{{ $row['resolved'] }}
                                                    </span>
                                                </td>
                                                <td class="px-5 py-4">
                                                    <span class="inline-flex items-center gap-1.5 {{ $badge }} px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>{{ $label }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="px-5 py-12 text-center text-gray-400">Belum ada laporan kerusakan untuk periode ini.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <p id="no-match" class="hidden text-sm text-gray-400 text-center py-6">Tidak ada lokasi yang cocok dengan pencarian.</p>

                        <!-- Opsi Unduh -->
                        <div class="mt-8 pt-6 border-t border-gray-100 flex flex-wrap items-center justify-between gap-4">
                            <p class="text-sm text-gray-500">Unduh rekap periode <span class="font-semibold text-[#4b3839]">{{ $monthLabel }}</span></p>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.recap.export', ['type' => 'csv', 'month' => $selectedMonth]) }}"
                                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-gray-200 text-[#4b3839] text-sm font-medium hover:bg-gray-50 transition">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    CSV
                                </a>
                                <a href="{{ route('admin.recap.export', ['type' => 'pdf', 'month' => $selectedMonth]) }}"
                                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-gray-200 text-[#4b3839] text-sm font-medium hover:bg-gray-50 transition">
                                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    PDF
                                </a>
                                <a href="{{ route('admin.recap.export', ['type' => 'excel', 'month' => $selectedMonth]) }}"
                                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#86545e] text-white text-sm font-semibold hover:bg-[#6f4550] shadow transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Excel
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </div>

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

            // Terapkan ulang pencarian pada tab yang baru dibuka
            filterRows(document.getElementById('location-search').value);
        }

        // Menyaring baris tabel pada tab yang sedang terbuka berdasarkan nama lokasi
        function filterRows(keyword) {
            const term = keyword.trim().toLowerCase();
            const activeContent = document.querySelector('.tab-content:not(.hidden)');
            let visible = 0;

            activeContent.querySelectorAll('.recap-row').forEach(row => {
                const match = row.dataset.location.includes(term);
                row.classList.toggle('hidden', !match);
                if (match) visible++;
            });

            const hasRows = activeContent.querySelectorAll('.recap-row').length > 0;
            document.getElementById('no-match').classList.toggle('hidden', !(hasRows && visible === 0));
        }
    </script>

</body>
</html>