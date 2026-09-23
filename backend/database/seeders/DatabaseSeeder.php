<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@chloe.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        // 2. Akun Petugas
        User::updateOrCreate(
            ['email' => 'petugas@chloe.com'],
            [
                'name' => 'Petugas Fasilitas',
                'password' => Hash::make('password123'),
                'role' => 'petugas',
                'status' => 'active',
            ]
        );

        // 3. Akun Pengguna Biasa
        User::updateOrCreate(
            ['email' => 'user@chloe.com'],
            [
                'name' => 'Pengguna Biasa',
                'password' => Hash::make('password123'),
                'role' => 'pengguna',
                'status' => 'active',
            ]
        );

        // 4. Data fasilitas
        $this->call(FacilitySeeder::class);
    }
}