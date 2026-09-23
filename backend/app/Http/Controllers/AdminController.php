<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Facility;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
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
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'location' => 'nullable|string',
            'capacity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,maintenance,inactive',
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
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'location' => 'nullable|string',
            'capacity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,maintenance,inactive',
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
        $newStatus = ($facility->status === 'active') ? 'inactive' : 'active';
        $facility->update(['status' => $newStatus]);

        return back()->with('success', 'Status fasilitas berhasil diperbarui.');
    }
    /**
     * Halaman Modify Roles (Kelola Akun & Verifikasi)
     */
    public function roles(Request $request)
    {
        $query = User::query();

        // Filter Pencarian (Nama, Email, NIM/NIP)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nim_nip', 'like', "%{$search}%");
            });
        }

        // Filter Role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter Status Akun
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->get();

        // Data pendaftar yang menunggu verifikasi (misal status 'pending_verification')
        $pendingUsers = User::where('status', 'pending_verification')->latest()->get();

        return view('admin.roles', compact('users', 'pendingUsers'));
    }

    /**
     * Update Role Pengguna
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:Admin,Petugas,Pengguna',
        ]);

        $user->update(['role' => $request->role]);

        return back()->with('success', 'Role pengguna ' . $user->name . ' berhasil diperbarui.');
    }

    // app/Http/Controllers/AdminController.php

    public function storeUser(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'role'     => 'required|in:Admin,Petugas,Pengguna',
    ]);

    User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => $request->password, // Cukup berikan plain password, casts 'hashed' di User.php yang akan meng-hash secara otomatis
        'nim_nip'  => $request->nim_nip,
        'unit'     => $request->unit,
        'role'     => $request->role,
        'status'   => 'Aktif',
    ]);

    return back()->with('success', 'Akun berhasil dibuat dan langsung bisa digunakan untuk login.');
}
    /**
     * Toggle Status Akun (Aktif / Ditangguhkan)
     */
    public function toggleStatus(User $user)
    {
        // Jika status saat ini 'Aktif', ubah jadi 'Ditangguhkan'. Jika tidak, ubah jadi 'Aktif'
        $newStatus = ($user->status === 'Aktif') ? 'Ditangguhkan' : 'Aktif';
        $user->update(['status' => $newStatus]);

        return back()->with('success', 'Status akun ' . $user->name . ' berhasil diubah menjadi ' . $newStatus);
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

        $totalSubmissions = (clone $query)->count();
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
}