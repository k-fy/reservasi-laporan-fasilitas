<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekapitulasi Fasilitas {{ $monthLabel }}</title>
    <style>
        body, table, td, th { font-family: "Times New Roman", Times, serif; font-size: 12pt; }
        th { background-color: #e6e6e6; font-weight: bold; text-align: center; }
        td.angka { text-align: center; }
    </style>
</head>
<body>
    <table>
        <tr><td colspan="6"><b>BARBIE CHARM UNIVERSITY</b></td></tr>
        <tr><td colspan="6">Rekapitulasi Penggunaan dan Kerusakan Fasilitas</td></tr>
        <tr><td colspan="6">Periode {{ $monthLabel }}</td></tr>
        <tr><td colspan="6"></td></tr>
    </table>

    <table border="1" style="border-collapse: collapse;">
        <tr><td colspan="4"><b>A. Ringkasan Pengajuan Reservasi</b></td></tr>
        <tr>
            <th>Total Pengajuan</th>
            <th>Disetujui</th>
            <th>Ditolak</th>
            <th>Tingkat Persetujuan</th>
        </tr>
        <tr>
            <td class="angka">{{ $summary['total'] }}</td>
            <td class="angka">{{ $summary['approved'] }}</td>
            <td class="angka">{{ $summary['rejected'] }}</td>
            <td class="angka">{{ $summary['percent'] }}</td>
        </tr>
    </table>
    <br>

    <table border="1" style="border-collapse: collapse;">
        <tr><td colspan="6"><b>B. Okupansi Fasilitas</b></td></tr>
        <tr>
            <th>No.</th>
            <th>Lokasi / Alat</th>
            <th>Jumlah Fasilitas</th>
            <th>Jumlah Pemakaian</th>
            <th>Jam Terpakai</th>
            <th>Tingkat Okupansi</th>
        </tr>
        @forelse ($occupancyRows as $row)
        <tr>
            <td class="angka">{{ $loop->iteration }}</td>
            <td>{{ $row['location'] }}</td>
            <td class="angka">{{ $row['facilities'] }}</td>
            <td class="angka">{{ $row['usage'] }}</td>
            <td class="angka">{{ $row['hours'] }}</td>
            <td class="angka">{{ $row['rate'] }}</td>
        </tr>
        @empty
        <tr><td colspan="6">Tidak ada data pada periode ini.</td></tr>
        @endforelse
    </table>
    <br>

    <table border="1" style="border-collapse: collapse;">
        <tr><td colspan="5"><b>C. Frekuensi Kerusakan Fasilitas</b></td></tr>
        <tr>
            <th>No.</th>
            <th>Lokasi / Alat</th>
            <th>Jumlah Laporan</th>
            <th>Belum Selesai</th>
            <th>Selesai</th>
        </tr>
        @forelse ($damageRows as $row)
        <tr>
            <td class="angka">{{ $loop->iteration }}</td>
            <td>{{ $row['location'] }}</td>
            <td class="angka">{{ $row['total'] }}</td>
            <td class="angka">{{ $row['in_progress'] }}</td>
            <td class="angka">{{ $row['resolved'] }}</td>
        </tr>
        @empty
        <tr><td colspan="5">Tidak ada laporan kerusakan pada periode ini.</td></tr>
        @endforelse
    </table>
    <br>

    <table>
        <tr><td colspan="6">Dicetak pada {{ $printedAt }}</td></tr>
    </table>
</body>
</html>