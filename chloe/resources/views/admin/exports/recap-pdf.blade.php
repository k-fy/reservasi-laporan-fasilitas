<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekapitulasi Fasilitas {{ $monthLabel }}</title>
    <style>
        @page { margin: 2cm 2cm 2cm 2.5cm; }
        body { font-family: "Times New Roman", Times, serif; font-size: 12pt; color: #000; }
        .kop { text-align: center; border-bottom: 2px solid #000; padding-bottom: 6px; margin-bottom: 16px; }
        .kop .instansi { font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .kop .unit { font-size: 12pt; }
        .judul { text-align: center; font-size: 13pt; font-weight: bold; text-transform: uppercase; margin: 0; }
        .periode { text-align: center; margin: 2px 0 18px; }
        h3 { font-size: 12pt; margin: 18px 0 6px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px 6px; vertical-align: top; }
        th { background-color: #e6e6e6; text-align: center; font-weight: bold; }
        td.angka, td.no { text-align: center; }
        .keterangan { font-size: 10pt; margin-top: 4px; }
        .ttd { margin-top: 36px; width: 100%; }
        .ttd td { border: none; padding: 0; }
    </style>
</head>
<body>
    <div class="kop">
        <div class="instansi">Barbie Charm University</div>
        <div class="unit">Bagian Sarana dan Prasarana Kampus</div>
    </div>

    <p class="judul">Rekapitulasi Penggunaan dan Kerusakan Fasilitas</p>
    <p class="periode">Periode {{ $monthLabel }}</p>

    <h3>A. Ringkasan Pengajuan Reservasi</h3>
    <table>
        <thead>
            <tr>
                <th>Total Pengajuan</th>
                <th>Disetujui</th>
                <th>Ditolak</th>
                <th>Tingkat Persetujuan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="angka">{{ $summary['total'] }}</td>
                <td class="angka">{{ $summary['approved'] }}</td>
                <td class="angka">{{ $summary['rejected'] }}</td>
                <td class="angka">{{ $summary['percent'] }}</td>
            </tr>
        </tbody>
    </table>

    <h3>B. Okupansi Fasilitas</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 6%">No.</th>
                <th>Lokasi / Alat</th>
                <th style="width: 13%">Jumlah Fasilitas</th>
                <th style="width: 13%">Jumlah Pemakaian</th>
                <th style="width: 14%">Jam Terpakai</th>
                <th style="width: 14%">Tingkat Okupansi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($occupancyRows as $row)
            <tr>
                <td class="no">{{ $loop->iteration }}</td>
                <td>{{ $row['location'] }}</td>
                <td class="angka">{{ $row['facilities'] }}</td>
                <td class="angka">{{ $row['usage'] }}</td>
                <td class="angka">{{ $row['hours'] }}</td>
                <td class="angka">{{ $row['rate'] }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="angka">Tidak ada data pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>
    <p class="keterangan">Keterangan: tingkat okupansi dihitung dari jumlah jam terpakai berdasarkan reservasi yang disetujui, dibandingkan dengan total jam operasional (07.00–20.00) selama satu bulan.</p>

    <h3>C. Frekuensi Kerusakan Fasilitas</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 6%">No.</th>
                <th>Lokasi / Alat</th>
                <th style="width: 15%">Jumlah Laporan</th>
                <th style="width: 15%">Belum Selesai</th>
                <th style="width: 15%">Selesai</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($damageRows as $row)
            <tr>
                <td class="no">{{ $loop->iteration }}</td>
                <td>{{ $row['location'] }}</td>
                <td class="angka">{{ $row['total'] }}</td>
                <td class="angka">{{ $row['in_progress'] }}</td>
                <td class="angka">{{ $row['resolved'] }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="angka">Tidak ada laporan kerusakan pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <table class="ttd">
        <tr>
            <td style="width: 60%"></td>
            <td>
                Dicetak pada {{ $printedAt }}<br>
                Admin Sistem Reservasi Fasilitas
            </td>
        </tr>
    </table>
</body>
</html>