<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekapitulasi {{ $monthName }}</title>
</head>
<body>
    <h2>REKAP OKUPANSI FASILITAS ({{ $monthName }})</h2>
    <table border="1" style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr style="background-color: #f2f2f2;">
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
                <td>{{ $row['facilities'] }}</td>
                <td>{{ $row['usage'] }}</td>
                <td>{{ $row['hours'] }}</td>
                <td>{{ $row['rate'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <br><br>

    <h2>REKAP FREKUENSI KERUSAKAN ({{ $monthName }})</h2>
    <table border="1" style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr style="background-color: #f2f2f2;">
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
                <td>{{ $row['total'] }}</td>
                <td>{{ $row['in_progress'] }}</td>
                <td>{{ $row['resolved'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>