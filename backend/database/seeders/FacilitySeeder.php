<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Facility::truncate();
        Schema::enableForeignKeyConstraints();

        $facilities = [
            [
                'name' => 'Aula Utama "Beau"',
                'type' => 'gedung',
                'location' => 'Gedung Rektorat Lt. 2',
                'capacity' => 750,
                'description' => 'Aula serbaguna ber-AC untuk seminar besar, wisuda, atau acara formal skala besar.',
                'amenities' => 'Sound System, Stage, AC Central, 500 Kursi',
                'price_per_hour' => 150000.00,
                'contact_phone' => '081234567890',
                'image' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Lab Komputer 1 (Gedung A)',
                'type' => 'lab',
                'location' => 'Gedung A1 Lt. 3',
                'capacity' => 50,
                'description' => 'Fasilitas komputer spesifikasi tinggi untuk praktikum, pelatihan, atau ujian online.',
                'amenities' => '50 PC Core i7, Projector, High-speed LAN, AC',
                'price_per_hour' => 50000.00,
                'contact_phone' => '081234567891',
                'image' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Ruang Sidang Lt. 3',
                'type' => 'ruangan',
                'location' => 'Gedung Dekanat Lt. 3',
                'capacity' => 30,
                'description' => 'Ruang rapat lengkap dengan meja konferensi dan proyektor untuk presentasi atau sidang.',
                'amenities' => 'Smart TV, Smart Whiteboard, AC, Cable HDMI',
                'price_per_hour' => 25000.00,
                'contact_phone' => '081234567892',
                'image' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Lapangan Outdoor Utama',
                'type' => 'area terbuka',
                'location' => 'Kawasan Lapangan Tengah',
                'capacity' => 1000,
                'description' => 'Area terbuka untuk kegiatan olahraga, konser / gathering luar ruangan.',
                'amenities' => 'Lampu Sorot, Tribun, Akses Listrik Panggung',
                'price_per_hour' => 100000.00,
                'contact_phone' => '081234567893',
                'image' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Proyektor Portable Epson',
                'type' => 'alat',
                'location' => 'Ruang Inventaris Utama',
                'capacity' => 10,
                'description' => 'Proyektor HD ringkas dengan konektivitas HDMI/VGA, cocok untuk presentasi mobile.',
                'amenities' => 'Kabel HDMI 5m, Remote, Tas Proyektor',
                'price_per_hour' => 10000.00,
                'contact_phone' => '081234567894',
                'image' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Set Sound Portable Wireless',
                'type' => 'alat',
                'location' => 'Ruang Inventaris Utama',
                'capacity' => 5,
                'description' => 'Sistem audio all-in-one lengkap dengan mikrofon nirkabel untuk kegiatan indoor maupun outdoor.',
                'amenities' => '2 Wireless Mic, Bluetooth Speaker, Stand Mic',
                'price_per_hour' => 20000.00,
                'contact_phone' => '081234567895',
                'image' => null,
                'status' => 'active',
            ],
        ];

        foreach ($facilities as $facility) {
            Facility::create($facility);
        }
    }
}