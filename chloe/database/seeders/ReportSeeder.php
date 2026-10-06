<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $user = fn (string $email) => User::where('email', $email)->value('id');
        $facility = fn (string $name) => Facility::where('name', $name)->value('id');

        $petugas = $user('petugas@charm.ac.id');
        $now     = Carbon::now();

        $reports = [
            // ================= NEW (belum diproses petugas) =================
            [
                'user'     => 'mahasiswa@charm.ac.id',
                'facility' => 'Lab Komputer 1 (Gedung A)',
                'category' => 'Peralatan',
                'description' => 'Tiga PC di baris belakang (nomor 41–43) tidak bisa menyala walaupun kabel daya sudah terpasang.',
                'status'   => Report::STATUS_NEW,
                'created'  => $now->copy()->subHours(5),
            ],
            [
                'user'     => 'staf@charm.ac.id',
                'facility' => 'Ruang Kelas 304',
                'category' => 'Kelistrikan',
                'description' => 'Dua lampu di sisi kanan ruangan berkedip terus dan stop kontak di dekat meja dosen tidak berfungsi.',
                'status'   => Report::STATUS_NEW,
                'created'  => $now->copy()->subDay(),
            ],
            [
                'user'     => 'user@charm.ac.id',
                'facility' => 'Ruang Diskusi Perpustakaan',
                'category' => 'Kebersihan',
                'description' => 'Karpet ruangan basah dan berbau, kemungkinan ada rembesan air dari plafon.',
                'status'   => Report::STATUS_NEW,
                'created'  => $now->copy()->subDays(2),
            ],

            // ================= PROGRESS (sedang ditangani) =================
            [
                // Terkait fasilitas berstatus "maintenance" di FacilitySeeder
                'user'     => 'dosen@charm.ac.id',
                'facility' => 'Ruang Teater Gedung B',
                'category' => 'Kelistrikan',
                'description' => 'Sistem lighting panggung mati total saat gladi bersih, sebagian kabel terlihat terbakar.',
                'status'   => Report::STATUS_PROGRESS,
                'resolution_notes' => 'Teknisi sedang mengganti panel dimmer dan kabel lighting. Fasilitas ditandai dalam perbaikan.',
                'handled'  => true,
                'created'  => $now->copy()->subDays(6),
            ],
            [
                'user'     => 'mahasiswa@charm.ac.id',
                'facility' => 'Aula Utama "Beau"',
                'category' => 'AC / Pendingin',
                'description' => 'AC central di sisi panggung tidak dingin dan mengeluarkan suara berisik.',
                'status'   => Report::STATUS_PROGRESS,
                'resolution_notes' => 'Sudah dijadwalkan servis AC oleh vendor minggu ini.',
                'handled'  => true,
                'created'  => $now->copy()->subDays(3),
            ],

            // ================= RESOLVED (selesai) =================
            [
                'user'     => 'user@charm.ac.id',
                'facility' => 'Proyektor Portable Epson',
                'category' => 'Peralatan',
                'description' => 'Gambar proyektor buram dan warnanya kekuningan.',
                'status'   => Report::STATUS_RESOLVED,
                'resolution_notes' => 'Lensa dibersihkan dan lampu proyektor diganti. Sudah diuji dan berfungsi normal.',
                'handled'  => true,
                'created'  => $now->copy()->subDays(12),
            ],
            [
                'user'     => 'staf@charm.ac.id',
                'facility' => 'Lab Jaringan & Keamanan Siber',
                'category' => 'Jaringan / Internet',
                'description' => 'Koneksi internet di seluruh PC lab terputus sejak pagi.',
                'status'   => Report::STATUS_RESOLVED,
                'resolution_notes' => 'Switch utama di rak server di-restart dan satu kabel uplink diganti.',
                'handled'  => true,
                'created'  => $now->copy()->subDays(20),
            ],
            [
                'user'     => 'mahasiswa@charm.ac.id',
                'facility' => 'Lapangan Outdoor Utama',
                'category' => 'Fasilitas Umum',
                'description' => 'Dua lampu sorot di sisi utara lapangan mati sehingga lapangan gelap saat malam.',
                'status'   => Report::STATUS_RESOLVED,
                'resolution_notes' => 'Lampu sorot diganti dengan unit LED baru.',
                'handled'  => true,
                'created'  => $now->copy()->subDays(35),
            ],
            [
                'user'     => 'dosen@charm.ac.id',
                'facility' => 'Lab Komputer 1 (Gedung A)',
                'category' => 'Peralatan',
                'description' => 'Proyektor lab tidak mendeteksi input HDMI dari laptop.',
                'status'   => Report::STATUS_RESOLVED,
                'resolution_notes' => 'Kabel HDMI yang rusak diganti dengan kabel baru.',
                'handled'  => true,
                'created'  => $now->copy()->subDays(40),
            ],

            // ================= REJECTED =================
            [
                'user'     => 'user@charm.ac.id',
                'facility' => 'Ruang Kelas 201',
                'category' => 'Lainnya',
                'description' => 'Kursi di ruang kelas kurang empuk.',
                'status'   => Report::STATUS_REJECTED,
                'resolution_notes' => 'Bukan kerusakan; kursi dalam kondisi baik dan sesuai standar ruang kelas.',
                'handled'  => true,
                'created'  => $now->copy()->subDays(8),
            ],
        ];

        foreach ($reports as $r) {
            $userId     = $user($r['user']);
            $facilityId = $facility($r['facility']);

            if (! $userId || ! $facilityId) {
                continue;
            }

            // Waktu terakhir diperbarui: laporan yang sudah ditangani dianggap diperbarui 1 hari setelah dibuat
            $updated = ! empty($r['handled']) ? $r['created']->copy()->addDay()->min($now) : $r['created'];

            // updateOrInsert agar seeder aman dijalankan berulang (tidak dobel)
            DB::table('reports')->updateOrInsert(
                [
                    'user_id'     => $userId,
                    'facility_id' => $facilityId,
                    'description' => $r['description'],
                ],
                [
                    'category'         => $r['category'],
                    'photo'            => null,
                    'status'           => $r['status'],
                    'resolution_notes' => $r['resolution_notes'] ?? null,
                    'handled_by'       => ! empty($r['handled']) ? $petugas : null,
                    'created_at'       => $r['created'],
                    'updated_at'       => $updated,
                ]
            );
        }
    }
}