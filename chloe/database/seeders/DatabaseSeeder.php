<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Password yang sama untuk semua akun contoh
     * (dicantumkan di laporan bagian "Informasi login").
     */
    public const DEFAULT_PASSWORD = 'password123';

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = Hash::make(self::DEFAULT_PASSWORD);

        // Status akun "menunggu verifikasi" (hasil registrasi mandiri)
        $pending = defined(User::class . '::STATUS_PENDING') ? User::STATUS_PENDING : 'pending';

        $users = [
            // ===== Admin =====
            ['email' => 'admin@charm.ac.id',   'name' => 'Administrator',      'role' => 'admin',    'status' => 'active', 'nim_nip' => '198501012010011001'],

            // ===== Petugas =====
            ['email' => 'petugas@charm.ac.id',  'name' => 'Petugas Fasilitas',  'role' => 'petugas',  'status' => 'active', 'nim_nip' => '199003152015041002'],
            ['email' => 'petugas2@charm.ac.id', 'name' => 'Rina Kusuma',        'role' => 'petugas',  'status' => 'active', 'nim_nip' => '199207202018032003'],

            // ===== Pengguna aktif (mahasiswa, dosen, staf) =====
            ['email' => 'user@charm.ac.id',     'name' => 'Pengguna Biasa',     'role' => 'pengguna', 'status' => 'active', 'nim_nip' => '24060124130001'],
            ['email' => 'mahasiswa@charm.ac.id','name' => 'Dimas Pratama',      'role' => 'pengguna', 'status' => 'active', 'nim_nip' => '24060124130045'],
            ['email' => 'dosen@charm.ac.id',    'name' => 'Dr. Siti Rahmawati', 'role' => 'pengguna', 'status' => 'active', 'nim_nip' => '198203112008122001'],
            ['email' => 'staf@charm.ac.id',     'name' => 'Agus Setiawan',      'role' => 'pengguna', 'status' => 'active', 'nim_nip' => '199105052019031004'],

            // ===== Untuk demo fitur admin =====
            // Menunggu verifikasi (registrasi mandiri)
            ['email' => 'pending1@charm.ac.id', 'name' => 'Nadia Putri',        'role' => 'pengguna', 'status' => $pending, 'nim_nip' => '24060124130088'],
            ['email' => 'pending2@charm.ac.id', 'name' => 'Bagas Wicaksono',    'role' => 'pengguna', 'status' => $pending, 'nim_nip' => '24060124130092'],
            // Akun ditangguhkan
            ['email' => 'suspended@charm.ac.id','name' => 'Akun Ditangguhkan',  'role' => 'pengguna', 'status' => User::STATUS_SUSPENDED, 'nim_nip' => '24060124130099'],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                array_merge($data, ['password' => $password])
            );
        }

        // Data lain (dipanggil hanya jika seeder-nya sudah dibuat)
        $this->call(FacilitySeeder::class);

        foreach ([ReservationSeeder::class, ReportSeeder::class] as $seeder) {
            if (class_exists($seeder)) {
                $this->call($seeder);
            }
        }
    }
}