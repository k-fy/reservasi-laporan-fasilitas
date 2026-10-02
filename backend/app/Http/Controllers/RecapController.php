<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Facility;
use App\Models\Reservation;
use Carbon\Carbon;

class RecapController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil filter bulan dari request (Format: YYYY-MM, contoh: "2026-10")
        $selectedMonth = $request->get('month', date('Y-m'));
        
        try {
            $parsedDate = Carbon::createFromFormat('Y-m', $selectedMonth);
        } catch (\Exception $e) {
            $parsedDate = Carbon::now();
            $selectedMonth = $parsedDate->format('Y-m');
        }

        $year  = $parsedDate->year;
        $month = $parsedDate->month;

        // 2. Data Kerusakan Riil dari Model Report
        $damageReports = Report::with('facility')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        $damageRows = $damageReports->groupBy(function ($report) {
            return $report->facility ? $report->facility->name : 'Laporan Tanpa Fasilitas';
        })->map(function ($group, $locationName) {
            return [
                'location'    => $locationName,
                'total'       => $group->count(),
                // Hitung status 'New' & 'Progress' sebagai Sedang Diperbaiki
                'in_progress' => $group->whereIn('status', [Report::STATUS_NEW, Report::STATUS_PROGRESS])->count(),
                // Hitung status 'Resolved' sebagai Selesai Diperbaiki
                'resolved'    => $group->where('status', Report::STATUS_RESOLVED)->count(),
            ];
        })->values()->toArray();

        // 3. Data Okupansi Riil (Mengambil data dari Facility & Reservation)
        $occupancyRows = Facility::withCount(['reservations' => function ($q) use ($year, $month) {
            $q->whereYear('created_at', $year)
              ->whereMonth('created_at', $month)
              ->where('status', 'approved');
        }])->get()->map(function ($facility) {
            $usage = $facility->reservations_count ?? 0;
            $hours = $usage * 3; // Rata-rata perkiraan 3 jam per reservasi
            $rate  = min(100, round(($hours / 200) * 100)); // Persentase okupansi

            return [
                'location'   => $facility->name,
                'facilities' => 1,
                'usage'      => $usage,
                'hours'      => $hours,
                'rate'       => $rate,
            ];
        })->toArray();

        // 4. Hitung Statistik Card Bagian Atas
        $totalSubmissions = Reservation::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        $approvedSubmissions = Reservation::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', 'approved')
            ->count();

        $rejectedSubmissions = Reservation::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', 'rejected')
            ->count();

        $approvedPercent = $totalSubmissions > 0 ? round(($approvedSubmissions / $totalSubmissions) * 100) : 0;
        // 5. Return View dengan Variabel Lengkap
        return view('admin.recap', compact(
            'selectedMonth',
            'occupancyRows',
            'damageRows',
            'totalSubmissions',
            'approvedSubmissions',
            'rejectedSubmissions',
            'approvedPercent'
        ));
    }

    // Alias jika route kamu memanggil method recap()
    public function recap(Request $request)
    {
        return $this->index($request);
    }
}