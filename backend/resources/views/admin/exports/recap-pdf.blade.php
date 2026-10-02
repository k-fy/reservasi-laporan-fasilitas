<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekapitulasi {{ $monthName }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h2 { color: #86545e; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f7eced; color: #86545e; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 15px;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #86545e; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Cetak / Simpan PDF</button>
    </div>

    <h2>REKAP OKUPANSI FASILITAS</h2>
    <table>
        <thead>
            <tr>
                <th>Lokasi / Alat</th>
                <th>Total Fasilitas</th>
                <th>Total Pemakaian</th>
                <th>Total Jam Terpakai</th>
                <th>Tingkat Okupansi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($occupancyRows as $row)
            <tr>
                <td>{{ $row['location'] }}</td>
                <td>{{ $row['facilities'] }} fasilitas</td>
                <td>{{ $row['usage'] }} kali</td>
                <td>{{ $row['hours'] }} jam</td>
                <td>{{ $row['rate'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2>REKAP FREKUENSI KERUSAKAN</h2>
    <table>
        <thead>
            <tr>
                <th>Lokasi / Alat</th>
                <th>Total Laporan</th>
                <th>Sedang Diperbaiki</th>
                <th>Selesai Diperbaiki</th>
            </tr>
        </thead>
        <tbody>
            @foreach($damageRows as $row)
            <tr>
                <td>{{ $row['location'] }}</td>
                <td>{{ $row['total'] }} laporan</td>
                <td>{{ $row['in_progress'] }}</td>
                <td>{{ $row['resolved'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>