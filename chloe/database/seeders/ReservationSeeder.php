<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil id akun & fasilitas berdasarkan email / nama (dibuat oleh seeder sebelumnya)
        $user = fn (string $email) => User::where('email', $email)->value('id');
        $facility = fn (string $name) => Facility::where('name', $name)->value('id');

        $petugas = $user('petugas@charm.ac.id');
        $today   = Carbon::today();

        $reservations = [
            // ================= APPROVED (mengisi slot) =================
            [
                'user'     => 'user@charm.ac.id',
                'facility' => 'Aula Utama "Beau"',
                'date'     => $today->copy()->addDays(3),
                'start'    => '09:00', 'end' => '12:00',
                'purpose'  => 'Seminar Nasional Teknologi Informasi 2026 yang diselenggarakan HIMA.',
                'status'   => 'approved',
                'processed_by' => $petugas,
                'created'  => $today->copy()->subDays(4),
            ],
            [
                // Dipakai hari ini -> Availability Check hari ini menampilkan Not Available
                'user'     => 'dosen@charm.ac.id',
                'facility' => 'Lab Multimedia & Desain',
                'date'     => $today->copy(),
                'start'    => '13:00', 'end' => '15:00',
                'purpose'  => 'Kelas pengganti mata kuliah Desain Antarmuka.',
                'status'   => 'approved',
                'processed_by' => $petugas,
                'created'  => $today->copy()->subDays(5),
            ],
            [
                'user'     => 'staf@charm.ac.id',
                'facility' => 'Proyektor Portable Epson',
                'date'     => $today->copy()->addDays(4),
                'start'    => '08:00', 'end' => '10:00',
                'purpose'  => 'Presentasi rapat koordinasi bagian akademik.',
                'status'   => 'approved',
                'processed_by' => $petugas,
                'created'  => $today->copy()->subDays(2),
            ],
            // Riwayat yang sudah lewat (untuk rekap okupansi)
            [
                'user'     => 'mahasiswa@charm.ac.id',
                'facility' => 'Ruang Kelas 201',
                'date'     => $today->copy()->subDays(7),
                'start'    => '10:00', 'end' => '12:00',
                'purpose'  => 'Rapat pleno BEM Fakultas.',
                'status'   => 'approved',
                'processed_by' => $petugas,
                'created'  => $today->copy()->subDays(12),
            ],
            [
                'user'     => 'user@charm.ac.id',
                'facility' => 'Lapangan Outdoor Utama',
                'date'     => $today->copy()->subDays(10),
                'start'    => '15:00', 'end' => '18:00',
                'purpose'  => 'Turnamen futsal antar angkatan.',
                'status'   => 'approved',
                'processed_by' => $petugas,
                'created'  => $today->copy()->subDays(16),
            ],

            // ================= PENDING (antrian petugas) =================
            [
                // SENGAJA BENTROK dengan reservasi approved Aula di atas
                // -> demo: sistem mencegah petugas menyetujui reservasi yang bentrok
                'user'     => 'mahasiswa@charm.ac.id',
                'facility' => 'Aula Utama "Beau"',
                'date'     => $today->copy()->addDays(3),
                'start'    => '10:00', 'end' => '11:00',
                'purpose'  => 'Latihan paduan suara untuk dies natalis.',
                'status'   => 'pending',
                'processed_by' => null,
                'created'  => $today->copy()->subDay(),
            ],
            [
                'user'     => 'dosen@charm.ac.id',
                'facility' => 'Lab Komputer 1 (Gedung A)',
                'date'     => $today->copy()->addDays(4),
                'start'    => '13:00', 'end' => '15:00',
                'purpose'  => 'Ujian praktikum Pemrograman Web.',
                'status'   => 'pending',
                'processed_by' => null,
                'created'  => $today->copy()->subDay(),
            ],
            [
                'user'     => 'staf@charm.ac.id',
                'facility' => 'Ruang Sidang Lt. 3',
                'date'     => $today->copy()->addDays(5),
                'start'    => '08:00', 'end' => '09:30',
                'purpose'  => 'Sidang tugas akhir mahasiswa semester 8.',
                'status'   => 'pending',
                'processed_by' => null,
                'created'  => $today->copy(),
            ],
            [
                'user'     => 'user@charm.ac.id',
                'facility' => 'Set Sound Portable Wireless',
                'date'     => $today->copy()->addDays(6),
                'start'    => '16:00', 'end' => '19:00',
                'purpose'  => 'Acara malam keakraban UKM Musik.',
                'status'   => 'pending',
                'processed_by' => null,
                'created'  => $today->copy(),
            ],

            // ================= REJECTED =================
            [
                'user'     => 'mahasiswa@charm.ac.id',
                'facility' => 'Lapangan Outdoor Utama',
                'date'     => $today->copy()->addDays(7),
                'start'    => '18:00', 'end' => '20:00',
                'purpose'  => 'Nonton bareng final sepak bola.',
                'status'   => 'rejected',
                'cancel_reason' => 'Kegiatan tidak sesuai ketentuan penggunaan fasilitas kampus.',
                'processed_by' => $petugas,
                'created'  => $today->copy()->subDays(2),
            ],

            // ================= CANCELLED =================
            [
                // Dibatalkan petugas karena kondisi mendesak
                'user'     => 'dosen@charm.ac.id',
                'facility' => 'Ruang Teater Gedung B',
                'date'     => $today->copy()->addDays(2),
                'start'    => '09:00', 'end' => '11:00',
                'purpose'  => 'Gladi bersih pentas teater mahasiswa.',
                'status'   => 'cancelled',
                'cancel_reason' => 'Fasilitas mendadak tidak bisa digunakan karena kerusakan lighting panggung.',
                'processed_by' => $petugas,
                'created'  => $today->copy()->subDays(6),
            ],
            [
                // Dibatalkan sendiri oleh pengguna
                'user'     => 'user@charm.ac.id',
                'facility' => 'Kamera DSLR Profesional',
                'date'     => $today->copy()->addDays(5),
                'start'    => '10:00', 'end' => '12:00',
                'purpose'  => 'Dokumentasi kegiatan bakti sosial.',
                'status'   => 'cancelled',
                'cancel_reason' => 'Kegiatan diundur oleh panitia.',
                'processed_by' => null,
                'created'  => $today->copy()->subDays(3),
            ],
        ];

        foreach ($reservations as $r) {
            $userId     = $user($r['user']);
            $facilityId = $facility($r['facility']);

            // Lewati jika akun / fasilitas belum ada
            if (! $userId || ! $facilityId) {
                continue;
            }

            // updateOrInsert agar seeder aman dijalankan berulang (tidak dobel)
            DB::table('reservations')->updateOrInsert(
                [
                    'user_id'          => $userId,
                    'facility_id'      => $facilityId,
                    'reservation_date' => $r['date']->toDateString(),
                    'start_time'       => $r['start'] . ':00',
                ],
                [
                    'end_time'      => $r['end'] . ':00',
                    'purpose'       => $r['purpose'],
                    'status'        => $r['status'],
                    'cancel_reason' => $r['cancel_reason'] ?? null,
                    'processed_by'  => $r['processed_by'],
                    'created_at'    => $r['created'],
                    'updated_at'    => $r['created'],
                ]
            );
        }
    }
}