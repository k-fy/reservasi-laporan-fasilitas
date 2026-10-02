<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Report;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Halaman Recap admin + export CSV / Excel / PDF.
 * Data halaman dan file export dihitung dari SATU fungsi (recapData) agar selalu sama.
 */
class RecapController extends Controller
{
    /**
     * Halaman Recap
     */
    public function index(Request $request)
    {
        return view('admin.recap', $this->recapData($this->selectedMonth($request)));
    }

    // Alias jika route memanggil method recap()
    public function recap(Request $request)
    {
        return $this->index($request);
    }

    /**
     * Export rekap bulan terpilih: ?type=csv|excel|pdf&month=YYYY-MM
     */
    public function exportRecap(Request $request)
    {
        $type          = $request->get('type', 'csv');
        $selectedMonth = $this->selectedMonth($request);
        $data          = $this->recapData($selectedMonth);

        // Label periode berbahasa Indonesia untuk dokumen, mis. "Oktober 2026"
        $monthLabel = Carbon::createFromFormat('Y-m', $selectedMonth)->locale('id')->translatedFormat('F Y');
        $monthName  = str_replace(' ', '_', $monthLabel); // dipakai untuk nama file
        $printedAt  = now()->locale('id')->translatedFormat('d F Y, H:i');

        // Baris untuk file export: angka okupansi diformat "1,5%" & jam "3 jam"
        $occupancyRows = collect($data['occupancyRows'])->map(fn ($row) => [
            'location'   => $row['location'],
            'facilities' => $row['facilities'],
            'usage'      => $row['usage'],
            'hours'      => rtrim(rtrim(number_format($row['hours'], 1, ',', '.'), '0'), ','),
            'rate'       => number_format($row['rate'], 1, ',', '.') . '%',
        ]);
        $damageRows = collect($data['damageRows']);

        $summary = [
            'total'    => $data['totalSubmissions'],
            'approved' => $data['approvedSubmissions'],
            'rejected' => $data['rejectedSubmissions'],
            'percent'  => $data['approvedPercent'] . '%',
        ];

        // --- EKSPOR CSV ---
        if ($type === 'csv') {
            $fileName = "recap_{$monthName}.csv";
            $headers = [
                "Content-type"        => "text/csv; charset=UTF-8",
                "Content-Disposition" => "attachment; filename={$fileName}",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];

            $callback = function () use ($occupancyRows, $damageRows, $summary, $monthLabel) {
                $file = fopen('php://output', 'w');
                // BOM agar huruf/simbol terbaca benar saat dibuka di Excel
                fputs($file, "\xEF\xBB\xBF");

                fputcsv($file, ['REKAPITULASI PENGGUNAAN DAN KERUSAKAN FASILITAS KAMPUS']);
                fputcsv($file, ['Periode', $monthLabel]);
                fputcsv($file, []);

                // Ringkasan pengajuan
                fputcsv($file, ['RINGKASAN PENGAJUAN RESERVASI']);
                fputcsv($file, ['Total Pengajuan', 'Disetujui', 'Ditolak', 'Tingkat Persetujuan']);
                fputcsv($file, [$summary['total'], $summary['approved'], $summary['rejected'], $summary['percent']]);
                fputcsv($file, []);

                // Section 1: Okupansi
                fputcsv($file, ['REKAP OKUPANSI FASILITAS']);
                fputcsv($file, ['Lokasi / Alat', 'Total Fasilitas', 'Total Pemakaian', 'Total Jam Terpakai', 'Tingkat Okupansi']);
                foreach ($occupancyRows as $row) {
                    fputcsv($file, [$row['location'], $row['facilities'], $row['usage'], $row['hours'], $row['rate']]);
                }
                fputcsv($file, []);

                // Section 2: Kerusakan
                fputcsv($file, ['REKAP FREKUENSI KERUSAKAN']);
                fputcsv($file, ['Lokasi / Alat', 'Total Laporan', 'Belum Selesai', 'Selesai Diperbaiki']);
                foreach ($damageRows as $row) {
                    fputcsv($file, [$row['location'], $row['total'], $row['in_progress'], $row['resolved']]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        $viewData = compact('occupancyRows', 'damageRows', 'monthName', 'monthLabel', 'summary', 'printedAt');

        // --- EKSPOR EXCEL (.xls format HTML, tanpa paket tambahan) ---
        if ($type === 'excel') {
            $fileName = "recap_{$monthName}.xls";
            $headers = [
                "Content-Type"        => "application/vnd.ms-excel",
                "Content-Disposition" => "attachment; filename={$fileName}",
                "Pragma"              => "no-cache",
                "Expires"             => "0",
            ];

            $content = view('admin.exports.recap-excel', $viewData)->render();
            return response($content, 200, $headers);
        }

        // --- EKSPOR PDF ---
        if ($type === 'pdf') {
            // Jika paket dompdf terpasang -> unduh file PDF
            if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.exports.recap-pdf', $viewData);
                return $pdf->download("recap_{$monthName}.pdf");
            }

            // Fallback jika dompdf belum terpasang: tampilan siap cetak (Ctrl+P -> Save as PDF)
            return response()->view('admin.exports.recap-pdf', $viewData);
        }

        return redirect()->back();
    }

    /**
     * Ambil bulan dari request (format YYYY-MM); jika tidak valid pakai bulan ini.
     */
    private function selectedMonth(Request $request): string
    {
        $month = (string) $request->get('month', now()->format('Y-m'));

        try {
            return Carbon::createFromFormat('Y-m', $month)->format('Y-m');
        } catch (\Exception $e) {
            return now()->format('Y-m');
        }
    }

    /**
     * Kunci pengelompokan rekap ("Lokasi atau Alat"):
     * - alat      -> nama alatnya, mis. "Proyektor Portable Epson"
     * - lainnya   -> nama gedung, mis. "Gedung A Lt. 3" -> "Gedung A"
     */
    private function recapGroupOf(?Facility $facility): string
    {
        if (! $facility) {
            return 'Tanpa Lokasi';
        }

        return $facility->type === 'alat'
            ? $facility->name
            : $this->buildingOf($facility->location);
    }

    /**
     * Ambil nama gedung dari lokasi, mis. "Gedung A Lt. 3" -> "Gedung A",
     * agar rekap dikelompokkan per gedung, bukan per lantai.
     */
    private function buildingOf(?string $location): string
    {
        $location = trim((string) $location);
        if ($location === '') {
            return 'Tanpa Lokasi';
        }

        return trim(preg_replace('/\s*-?\s*Lt\.?\s*\d+\s*$/i', '', $location)) ?: $location;
    }

    /**
     * Hitung data rekap untuk satu bulan (dipakai halaman Recap & export).
     */
    public function recapData(string $selectedMonth): array
    {
        $monthStart = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        $monthEnd   = $monthStart->copy()->endOfMonth();

        // ---------- 1. Statistik pengajuan reservasi ----------
        // Berdasarkan TANGGAL PEMAKAIAN (reservation_date), sama dengan tabel okupansi,
        // sehingga kartu statistik dan tabel di bawahnya konsisten.
        $submissions = Reservation::whereBetween('reservation_date', [$monthStart->toDateString(), $monthEnd->toDateString()]);

        $totalSubmissions    = (clone $submissions)->count();
        $approvedSubmissions = (clone $submissions)->where('status', 'approved')->count();
        $rejectedSubmissions = (clone $submissions)->where('status', 'rejected')->count();
        $approvedPercent     = $totalSubmissions > 0 ? round($approvedSubmissions / $totalSubmissions * 100) : 0;

        // ---------- 2. Okupansi per gedung / alat (berdasarkan tanggal pemakaian) ----------
        // Jam tersedia per fasilitas per hari = jam operasional 07.00–20.00 = 13 jam
        $hoursPerDay = 13;
        $daysInMonth = $monthStart->daysInMonth;

        $facilities = Facility::where('status', '!=', 'inactive')->get(['id', 'name', 'type', 'location']);

        $approvedReservations = Reservation::whereIn('facility_id', $facilities->pluck('id'))
            ->where('status', 'approved')
            ->whereBetween('reservation_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->get(['facility_id', 'start_time', 'end_time']);

        $facilityGroup = $facilities->mapWithKeys(fn ($f) => [$f->id => $this->recapGroupOf($f)]);

        $occupancyRows = $facilities
            ->groupBy(fn ($f) => $facilityGroup[$f->id])
            ->map(function ($group, $building) use ($approvedReservations, $hoursPerDay, $daysInMonth) {
                $ids  = $group->pluck('id');
                $used = $approvedReservations->whereIn('facility_id', $ids);

                $hours = $used->sum(fn ($r) =>
                    Carbon::parse($r->start_time)->diffInMinutes(Carbon::parse($r->end_time)) / 60
                );

                $availableHours = $group->count() * $hoursPerDay * $daysInMonth;

                return [
                    'location'   => $building,
                    'facilities' => $group->count(),
                    'usage'      => $used->count(),
                    'hours'      => round($hours, 1),
                    'rate'       => $availableHours > 0 ? round($hours / $availableHours * 100, 1) : 0,
                ];
            })
            ->sortByDesc('hours')
            ->values()
            ->all();

        // ---------- 3. Frekuensi kerusakan per gedung / alat (berdasarkan tanggal laporan) ----------
        $reports = Report::with('facility:id,name,type,location')
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->get(['id', 'facility_id', 'status']);

        $damageRows = $reports
            ->groupBy(fn ($r) => $this->recapGroupOf($r->facility))
            ->map(fn ($group, $building) => [
                'location'    => $building,
                'total'       => $group->count(),
                'in_progress' => $group->whereIn('status', [Report::STATUS_NEW, Report::STATUS_PROGRESS])->count(),
                'resolved'    => $group->where('status', Report::STATUS_RESOLVED)->count(),
            ])
            ->sortByDesc('total')
            ->values()
            ->all();

        return compact(
            'totalSubmissions',
            'approvedSubmissions',
            'rejectedSubmissions',
            'approvedPercent',
            'occupancyRows',
            'damageRows',
            'selectedMonth'
        );
    }
}