<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Facility::updateOrCreate(
            [
                'name' => 'Ruang Seminar Gedung A'
            ],
            [
                'type' => 'Ruang Seminar',
                'location' => 'Gedung A Lantai 2',
                'capacity' => 100,
                'description' => 'Ruang seminar untuk kegiatan akademik dan organisasi.',
                'status' => 'active',
                'image' => null,
            ]
        );
    }
}