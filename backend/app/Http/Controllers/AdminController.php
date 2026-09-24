<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Facility;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    private const ROLES = [User::ROLE_ADMIN, User::ROLE_PETUGAS, User::ROLE_PENGGUNA];

    /**
     * Halaman Dashboard Admin
     */
    public function dashboard()
    {
        // 1. Data Statistik Atas
        $pendingBookingsCount = Booking::where('status', 'pending')->count();
        $activeBookingsCount = Booking::where('status', 'approved')
            ->whereDate('start_time', '<=', now())
            ->whereDate('end_time', '>=', now())
            ->count();
        $totalFacilities = Facility::count();
        $totalBookingsThisWeek = Booking::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        // Persentase pemakaian fasilitas minggu ini (Contoh kalkulasi sederhana)
        $facilityUsagePercent = $totalFacilities > 0
            ? round(($activeBookingsCount / $totalFacilities) * 100)
            : 0;

        // 2. Permintaan Terbaru (Pending & Recent)
        $recentBookings = Booking::with(['user', 'facility'])
            ->latest()
            ->take(5)
            ->get();

        // 3. Jadwal Hari Ini
        $todaySchedules = Booking::with('facility')
            ->whereDate('start_time', today())
            ->orderBy('start_time', 'asc')
            ->get();

        return view('admin.dashboard', compact(
            'pendingBookingsCount',
            'activeBookingsCount',
            'totalFacilities',
            'facilityUsagePercent',
            'recentBookings',
            'todaySchedules'
        ));
    }

    /**
     * Tambah Fasilitas Baru
     */
    public function storeFacility(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'type'        => 'required|string',
            'location'    => 'nullable|string',
            'capacity'    => 'required|integer|min:0',
            'description' => 'nullable|string',
            'status'      => 'required|in:active,maintenance,inactive',
        ]);

        Facility::create($validated);

        return redirect()->route('admin.facilities')->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    /**
     * Update Data Fasilitas
     */
    public function updateFacility(Request $request, Facility $facility)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'type'        => 'required|string',
            'location'    => 'nullable|string',
            'capacity'    => 'required|integer|min:0',
            'description' => 'nullable|string',
            'status'      => 'required|in:active,maintenance,inactive',
        ]);

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
     * Halaman Modify Roles (Kelola Akun)
     */
    public function roles(Request $request)
    {
        // Awal query ambil seluruh user
        $query = User::query();

        // 1. Filter Pencarian (Search Name, Email, atau NIM/NIP)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('nim_nip', 'like', "%{$search}%");
            });
        }

        // 2. Filter Status (Active / Suspended)
        if ($request->filled('status')) {
            if ($request->status === 'Active') {
                $query->where('status', User::STATUS_ACTIVE);
            } elseif ($request->status === 'Suspended') {
                $query->where('status', User::STATUS_SUSPENDED);
            }
        }

        // 3. Urutkan berdasarkan data yang paling baru ditambahkan
        $users = $query->latest()->get(); 
        // Atau jika pakai pagination: $users = $query->latest()->paginate(10);

        return view('admin.roles', compact('users'));
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

        return view('admin.facilities', compact('facilities'));
    }

    /**
     * Halaman Recapitulation / Summary Reports
     */
    public function summary(Request $request)
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

        return view('admin.summary', compact(
            'totalSubmissions',
            'approvedSubmissions',
            'rejectedSubmissions',
            'approvedPercent',
            'unitSummaries',
            'selectedMonth'
        ));
    }

    public function exportSummary(Request $request)
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