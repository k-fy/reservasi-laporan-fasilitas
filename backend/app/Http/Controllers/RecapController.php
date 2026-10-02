<?php

namespace App\Http\Controllers;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Facility;
use App\Models\Reservation;
use Carbon\Carbon;

class RecapController extends Controller
{
    public function exportRecap(Request $request)
    {
        $type = $request->get('type', 'csv'); // csv, pdf, excel
        $selectedMonth = $request->get('month', date('Y-m'));

        try {
            $parsedDate = Carbon::createFromFormat('Y-m', $selectedMonth);
        } catch (\Exception $e) {
            $parsedDate = Carbon::now();
            $selectedMonth = $parsedDate->format('Y-m');
        }

        $year  = $parsedDate->year;
        $month = $parsedDate->month;
        $monthName = $parsedDate->translatedFormat('F_Y');

        // 1. Data Okupansi
        $occupancyRows = \App\Models\Facility::withCount(['reservations' => function ($q) use ($year, $month) {
            $q->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', 'approved');
        }])->get()->map(function ($facility) {
            $usage = $facility->reservations_count ?? 0;
            $hours = $usage * 3;
            $rate  = min(100, round(($hours / 200) * 100));

            return [
                'location'   => $facility->name,
                'facilities' => 1,
                'usage'      => $usage,
                'hours'      => $hours,
                'rate'       => $rate . '%',
            ];
        });

        // 2. Data Kerusakan
        $damageReports = \App\Models\Report::with('facility')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        $damageRows = $damageReports->groupBy(function ($report) {
            return $report->facility ? $report->facility->name : 'Laporan Tanpa Fasilitas';
        })->map(function ($group, $locationName) {
            return [
                'location'    => $locationName,
                'total'       => $group->count(),
                'in_progress' => $group->whereIn('status', [\App\Models\Report::STATUS_NEW, \App\Models\Report::STATUS_PROGRESS])->count(),
                'resolved'    => $group->where('status', \App\Models\Report::STATUS_RESOLVED)->count(),
            ];
        });

        // --- EKSPOR CSV ---
        if ($type === 'csv') {
            $fileName = "recap_{$monthName}.csv";
            $headers = [
                "Content-type"        => "text/csv; charset=UTF-8",
                "Content-Disposition" => "attachment; filename={$fileName}",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $callback = function () use ($occupancyRows, $damageRows) {
                $file = fopen('php://output', 'w');
                // Add BOM for UTF-8 Excel support
                fputs($file, "\xEF\xBB\xBF");

                // Section 1: Okupansi
                fputcsv($file, ['REKAP OKUPANSI FASILITAS']);
                fputcsv($file, ['Lokasi / Alat', 'Total Fasilitas', 'Total Pemakaian', 'Total Jam Terpakai', 'Tingkat Okupansi']);
                foreach ($occupancyRows as $row) {
                    fputcsv($file, [$row['location'], $row['facilities'], $row['usage'], $row['hours'], $row['rate']]);
                }

                fputcsv($file, []); // Baris kosong pembatas

                // Section 2: Kerusakan
                fputcsv($file, ['REKAP FREKUENSI KERUSAKAN']);
                fputcsv($file, ['Lokasi / Alat', 'Total Laporan', 'Sedang Diperbaiki', 'Selesai Diperbaiki']);
                foreach ($damageRows as $row) {
                    fputcsv($file, [$row['location'], $row['total'], $row['in_progress'], $row['resolved']]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // --- EKSPOR EXCEL (.xls HTML format) ---
        if ($type === 'excel') {
            $fileName = "recap_{$monthName}.xls";
            $headers = [
                "Content-Type"        => "application/vnd.ms-excel",
                "Content-Disposition" => "attachment; filename={$fileName}",
                "Pragma"              => "no-cache",
                "Expires"             => "0"
            ];

            $content = view('admin.exports.recap-excel', compact('occupancyRows', 'damageRows', 'monthName'))->render();
            return response($content, 200, $headers);
        }

        // --- EKSPOR PDF ---
        if ($type === 'pdf') {
            // Jika menggunakan Dompdf (\Pdf::loadView) atau print HTML stream:
            if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.exports.recap-pdf', compact('occupancyRows', 'damageRows', 'monthName'));
                return $pdf->download("recap_{$monthName}.pdf");
            }

            // Fallback jika belum install package dompdf (Print-friendly HTML response)
            return response()->view('admin.exports.recap-pdf', compact('occupancyRows', 'damageRows', 'monthName'));
        }

        return redirect()->back();
    }
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