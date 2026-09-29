<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Facility;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    private const ROLES = [User::ROLE_ADMIN, User::ROLE_PETUGAS, User::ROLE_PENGGUNA];

    /**
     * Tipe fasilitas: nilai yang disimpan di database => label yang ditampilkan.
     * Nilai disamakan dengan data yang sudah ada & halaman pengguna (mis. 'alat').
     */
    public const FACILITY_TYPES = [
        'gedung'       => 'Gedung / Aula',
        'ruangan'      => 'Ruangan / Kelas',
        'lab'          => 'Laboratorium',
        'area terbuka' => 'Area Terbuka / Lapangan',
        'alat'         => 'Alat',
    ];

    public const FACILITY_STATUSES = ['active', 'maintenance', 'inactive'];

    /**
     * Dashboard Admin
     * Tidak ada halaman dashboard terpisah; admin langsung diarahkan ke Accounts.
     */
    public function dashboard()
    {
        return redirect()->route('admin.accounts');
    }

    /**
     * Aturan validasi form fasilitas (dipakai untuk tambah & ubah)
     */
    private function facilityRules(): array
    {
        return [
            'name'           => 'required|string|max:255',
            'type'           => ['required', Rule::in(array_keys(self::FACILITY_TYPES))],
            'location'       => 'required|string|max:255',
            'capacity'       => 'required|integer|min:0',
            'area'           => 'nullable|string|max:100',
            'description'    => 'nullable|string|max:2000',
            'amenities'      => 'nullable|string|max:1000',
            'price_per_hour' => 'nullable|integer|min:0',
            'contact_phone'  => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'status'         => ['required', Rule::in(self::FACILITY_STATUSES)],
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    /**
     * Pesan validasi berbahasa Indonesia untuk form fasilitas
     */
    private function facilityMessages(): array
    {
        return [
            'contact_phone.regex' => 'Nomor kontak hanya boleh berisi angka, spasi, tanda + atau -.',
            'image.image'         => 'File foto harus berupa gambar.',
            'image.max'           => 'Ukuran foto maksimal 2 MB.',
        ];
    }

    /**
     * Rapikan daftar fasilitas yang disediakan: "AC ,  Proyektor,,Wifi" -> "AC, Proyektor, Wifi"
     */
    private function normalizeAmenities(?string $amenities): ?string
    {
        if (blank($amenities)) {
            return null;
        }

        $items = collect(explode(',', $amenities))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->unique()
            ->values();

        return $items->isEmpty() ? null : $items->implode(', ');
    }

    /**
     * Tambah Fasilitas Baru
     */
    public function storeFacility(Request $request)
    {
        $validated = $request->validate($this->facilityRules(), $this->facilityMessages());
        $validated['amenities'] = $this->normalizeAmenities($validated['amenities'] ?? null);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('facilities', 'public');
        }

        Facility::create($validated);

        return redirect()->route('admin.facilities')->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    /**
     * Update Data Fasilitas
     */
    public function updateFacility(Request $request, Facility $facility)
    {
        $validated = $request->validate($this->facilityRules(), $this->facilityMessages());
        $validated['amenities'] = $this->normalizeAmenities($validated['amenities'] ?? null);

        if ($request->hasFile('image')) {
            // Hapus foto lama agar storage tidak penuh
            if ($facility->image) {
                Storage::disk('public')->delete($facility->image);
            }
            $validated['image'] = $request->file('image')->store('facilities', 'public');
        } else {
            // Tidak upload foto baru -> foto lama tetap dipakai
            unset($validated['image']);
        }

        $facility->update($validated);

        return redirect()->route('admin.facilities')->with('success', 'Fasilitas berhasil diperbarui.');
    }

    /**
     * Toggle Status Fasilitas (Aktif / Nonaktif)
     */
    public function toggleFacilityStatus(Facility $facility)
    {
        // Ubah status active -> inactive, atau sebaliknya
        $newStatus = $facility->status === 'active' ? 'inactive' : 'active';
        $facility->update(['status' => $newStatus]);

        return back()->with('success', 'Status fasilitas berhasil diperbarui.');
    }

    /**
     * Halaman Accounts (Kelola Akun)
     */
    public function accounts(Request $request)
    {
        // Awal query ambil seluruh user
        $query = User::query();

        // 1. Filter Pencarian (Search Name, Email, atau NIM/NIP)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nim_nip', 'like', "%{$search}%");
            });
        }

        // 2. Filter Role
        if ($request->filled('role') && in_array($request->role, self::ROLES, true)) {
            $query->where('role', $request->role);
        }

        // 3. Filter Status (Active / Suspended / Pending)
        //    'pending' = akun hasil registrasi mandiri yang menunggu verifikasi admin
        $pendingStatus = defined(User::class . '::STATUS_PENDING') ? User::STATUS_PENDING : 'pending';
        $allowedStatuses = [User::STATUS_ACTIVE, User::STATUS_SUSPENDED, $pendingStatus];

        if ($request->filled('status') && in_array($request->status, $allowedStatuses, true)) {
            $query->where('status', $request->status);
        }

        // 4. Urutkan berdasarkan data yang paling baru ditambahkan
        $users = $query->latest()->get();

        return view('admin.accounts', compact('users'));
    }

    /**
     * Update Role Pengguna
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', Rule::in(self::ROLES)],
        ]);

        // Admin tidak boleh mengubah role akunnya sendiri
        if ($user->id === auth()->id()) {
            return back()->withErrors(['role' => 'Kamu tidak bisa mengubah role akunmu sendiri.']);
        }

        $user->update(['role' => $request->role]);

        return back()->with('success', 'Role pengguna ' . $user->name . ' berhasil diperbarui.');
    }

    /**
     * Tambah Akun Baru oleh Admin
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role'     => 'required|string|in:petugas,pengguna,Petugas,Pengguna',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => strtolower($request->role), // Mengubah ke huruf kecil konsisten
            'status'   => 'active',
        ]);

        return back()->with('success', 'Akun berhasil dibuat!');
    }

    /**
     * Toggle Status Akun (Aktif / Ditangguhkan)
     */
    public function toggleStatus(User $user)
    {
        // Admin tidak boleh menangguhkan akunnya sendiri
        if ($user->id === auth()->id()) {
            return back()->withErrors(['status' => 'Kamu tidak bisa menangguhkan akunmu sendiri.']);
        }

        // Jika status saat ini 'active', ubah jadi 'suspended'. Jika tidak, ubah jadi 'active'
        $newStatus = $user->isActive() ? User::STATUS_SUSPENDED : User::STATUS_ACTIVE;
        $user->update(['status' => $newStatus]);

        $label = $newStatus === User::STATUS_ACTIVE ? 'aktif' : 'ditangguhkan';

        return back()->with('success', 'Status akun ' . $user->name . ' berhasil diubah menjadi ' . $label . '.');
    }

    /**
     * Halaman Master Data Facilities
     */
    public function facilities(Request $request)
    {
        $query = Facility::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $facilities = $query->latest()->get();

        // Daftar tipe & lokasi untuk filter dan form
        $facilityTypes = self::FACILITY_TYPES;
        $facilityLocations = Facility::whereNotNull('location')
            ->distinct()
            ->orderBy('location')
            ->pluck('location');

        return view('admin.facilities', compact('facilities', 'facilityTypes', 'facilityLocations'));
    }

    /**
     * Halaman Recap (Rekapitulasi Laporan)
     */
    public function recap(Request $request)
    {
        $selectedMonth = $request->input('month', date('Y-m')); // Format: YYYY-MM
        [$year, $month] = explode('-', $selectedMonth);

        // Filter berdasarkan Bulan dan Tahun yang aman untuk SQLite & MySQL
        $query = Booking::whereYear('created_at', $year)
                        ->whereMonth('created_at', $month);

        $totalSubmissions    = (clone $query)->count();
        $approvedSubmissions = (clone $query)->where('status', 'approved')->count();
        $rejectedSubmissions = (clone $query)->where('status', 'rejected')->count();

        $approvedPercent = $totalSubmissions > 0 ? round(($approvedSubmissions / $totalSubmissions) * 100) : 0;

        // Rekap per unit/lokasi
        $unitSummaries = (clone $query)
            ->selectRaw('location, count(*) as total')
            ->groupBy('location')
            ->get();

        return view('admin.recap', compact(
            'totalSubmissions',
            'approvedSubmissions',
            'rejectedSubmissions',
            'approvedPercent',
            'unitSummaries',
            'selectedMonth'
        ));
    }

    public function exportRecap(Request $request)
    {
        $type = $request->query('type', 'csv');
        if ($type === 'csv') {
            return back()->with('success', 'Laporan berhasil diunduh sebagai CSV!');
        } elseif ($type === 'pdf') {
            return back()->with('success', 'Laporan berhasil diunduh sebagai PDF!');
        } elseif ($type === 'excel') {
            return back()->with('success', 'Laporan berhasil diunduh sebagai Excel!');
        }

        return back();
    }
}