<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            // ===================== GEDUNG / AULA =====================
            [
                'name'           => 'Aula Utama "Beau"',
                'type'           => 'gedung',
                'location'       => 'Gedung Rektorat Lt. 2',
                'capacity'       => 750,
                'area'           => '40 x 25 Meter',
                'description'    => 'Aula serbaguna ber-AC untuk seminar besar, wisuda, atau acara formal skala besar.',
                'amenities'      => 'Sound System, Stage, AC Central, 750 Kursi, Proyektor & Layar Besar',
                'price_per_hour' => 150000,
                'contact_phone'  => '081234567890',
                'status'         => 'active',
            ],
            [
                'name'           => 'Auditorium Prof. Dr. Satrio',
                'type'           => 'gedung',
                'location'       => 'Gedung B Lt. 1',
                'capacity'       => 400,
                'area'           => '30 x 20 Meter',
                'description'    => 'Auditorium bertingkat dengan akustik baik untuk kuliah umum, seminar nasional, dan pentas seni.',
                'amenities'      => 'Kursi Bertingkat, Sound System, Proyektor Ganda, AC, Ruang Transit',
                'price_per_hour' => 120000,
                'contact_phone'  => '081234567896',
                'status'         => 'active',
            ],
            [
                'name'           => 'Ruang Teater Gedung B',
                'type'           => 'gedung',
                'location'       => 'Gedung B Lt. 3',
                'capacity'       => 150,
                'area'           => '20 x 15 Meter',
                'description'    => 'Ruang teater dengan panggung dan tata lampu untuk latihan atau pementasan UKM seni.',
                'amenities'      => 'Panggung, Lighting Panggung, Sound System, Ruang Ganti',
                'price_per_hour' => 75000,
                'contact_phone'  => '081234567897',
                'status'         => 'maintenance',
            ],

            // ===================== RUANGAN / KELAS =====================
            [
                'name'           => 'Ruang Sidang Lt. 3',
                'type'           => 'ruangan',
                'location'       => 'Gedung Dekanat Lt. 3',
                'capacity'       => 30,
                'area'           => '10 x 8 Meter',
                'description'    => 'Ruang rapat lengkap dengan meja konferensi dan proyektor untuk presentasi atau sidang.',
                'amenities'      => 'Smart TV, Smart Whiteboard, AC, Kabel HDMI, Meja Konferensi',
                'price_per_hour' => 25000,
                'contact_phone'  => '081234567892',
                'status'         => 'active',
            ],
            [
                'name'           => 'Ruang Kelas 201',
                'type'           => 'ruangan',
                'location'       => 'Gedung A Lt. 2',
                'capacity'       => 40,
                'area'           => '12 x 9 Meter',
                'description'    => 'Ruang kelas standar untuk perkuliahan, kelas tambahan, atau kegiatan organisasi.',
                'amenities'      => 'Proyektor, Whiteboard, AC, 40 Kursi Kuliah',
                'price_per_hour' => 0,
                'contact_phone'  => '081234567898',
                'status'         => 'active',
            ],
            [
                'name'           => 'Ruang Kelas 304',
                'type'           => 'ruangan',
                'location'       => 'Gedung A Lt. 3',
                'capacity'       => 60,
                'area'           => '14 x 10 Meter',
                'description'    => 'Ruang kelas besar dengan dua layar, cocok untuk kelas gabungan atau workshop.',
                'amenities'      => '2 Proyektor, Sound System Kelas, AC, 60 Kursi Kuliah',
                'price_per_hour' => 0,
                'contact_phone'  => '081234567898',
                'status'         => 'active',
            ],
            [
                'name'           => 'Ruang Diskusi Perpustakaan',
                'type'           => 'ruangan',
                'location'       => 'Gedung Perpustakaan Lt. 2',
                'capacity'       => 12,
                'area'           => '6 x 5 Meter',
                'description'    => 'Ruang diskusi tenang untuk kerja kelompok, bimbingan, atau rapat kecil.',
                'amenities'      => 'Meja Bundar, Smart TV, Whiteboard, Wifi, AC',
                'price_per_hour' => 0,
                'contact_phone'  => '081234567899',
                'status'         => 'active',
            ],

            // ===================== LABORATORIUM =====================
            [
                'name'           => 'Lab Komputer 1 (Gedung A)',
                'type'           => 'lab',
                'location'       => 'Gedung A Lt. 3',
                'capacity'       => 50,
                'area'           => '15 x 10 Meter',
                'description'    => 'Fasilitas komputer spesifikasi tinggi untuk praktikum, pelatihan, atau ujian online.',
                'amenities'      => '50 PC Core i7, Proyektor, High-speed LAN, AC',
                'price_per_hour' => 50000,
                'contact_phone'  => '081234567891',
                'status'         => 'active',
            ],
            [
                'name'           => 'Lab Jaringan & Keamanan Siber',
                'type'           => 'lab',
                'location'       => 'Gedung C Lt. 2',
                'capacity'       => 30,
                'area'           => '12 x 9 Meter',
                'description'    => 'Laboratorium dengan perangkat jaringan untuk praktikum jaringan komputer dan keamanan siber.',
                'amenities'      => '30 PC, Router & Switch Cisco, Rak Server, Proyektor, AC',
                'price_per_hour' => 60000,
                'contact_phone'  => '081234567900',
                'status'         => 'active',
            ],
            [
                'name'           => 'Lab Multimedia & Desain',
                'type'           => 'lab',
                'location'       => 'Gedung C Lt. 3',
                'capacity'       => 25,
                'area'           => '12 x 8 Meter',
                'description'    => 'Lab untuk desain grafis, editing video, dan produksi konten multimedia.',
                'amenities'      => '25 iMac, Pen Tablet, Green Screen, Lighting Studio, AC',
                'price_per_hour' => 60000,
                'contact_phone'  => '081234567900',
                'status'         => 'active',
            ],

            // ===================== AREA TERBUKA / LAPANGAN =====================
            [
                'name'           => 'Lapangan Outdoor Utama',
                'type'           => 'area terbuka',
                'location'       => 'Kawasan Lapangan Tengah',
                'capacity'       => 1000,
                'area'           => '100 x 60 Meter',
                'description'    => 'Area terbuka untuk kegiatan olahraga, konser, atau gathering luar ruangan.',
                'amenities'      => 'Lampu Sorot, Tribun, Akses Listrik Panggung',
                'price_per_hour' => 100000,
                'contact_phone'  => '081234567893',
                'status'         => 'active',
            ],
            [
                'name'           => 'Lapangan Basket Indoor',
                'type'           => 'area terbuka',
                'location'       => 'Gedung Olahraga',
                'capacity'       => 200,
                'area'           => '28 x 15 Meter',
                'description'    => 'Lapangan basket standar dengan tribun penonton, juga bisa untuk futsal dan voli.',
                'amenities'      => 'Ring Basket, Tribun, Scoreboard, Ruang Ganti',
                'price_per_hour' => 50000,
                'contact_phone'  => '081234567901',
                'status'         => 'inactive',
            ],

            // ===================== ALAT =====================
            [
                'name'           => 'Proyektor Portable Epson',
                'type'           => 'alat',
                'location'       => 'Ruang Inventaris Utama',
                'capacity'       => 10,
                'area'           => null,
                'description'    => 'Proyektor HD ringkas dengan konektivitas HDMI/VGA, cocok untuk presentasi mobile.',
                'amenities'      => 'Kabel HDMI 5m, Remote, Tas Proyektor',
                'price_per_hour' => 10000,
                'contact_phone'  => '081234567894',
                'status'         => 'active',
            ],
            [
                'name'           => 'Set Sound Portable Wireless',
                'type'           => 'alat',
                'location'       => 'Ruang Inventaris Utama',
                'capacity'       => 5,
                'area'           => null,
                'description'    => 'Sistem audio all-in-one lengkap dengan mikrofon nirkabel untuk kegiatan indoor maupun outdoor.',
                'amenities'      => '2 Wireless Mic, Bluetooth Speaker, Stand Mic',
                'price_per_hour' => 20000,
                'contact_phone'  => '081234567895',
                'status'         => 'active',
            ],
            [
                'name'           => 'Kamera DSLR Profesional',
                'type'           => 'alat',
                'location'       => 'Ruang Inventaris Utama',
                'capacity'       => 3,
                'area'           => null,
                'description'    => 'Kamera DSLR untuk dokumentasi acara, lengkap dengan lensa kit dan tripod.',
                'amenities'      => 'Lensa 18-55mm, Tripod, 2 Baterai, Memori 64GB',
                'price_per_hour' => 25000,
                'contact_phone'  => '081234567895',
                'status'         => 'active',
            ],
        ];

        foreach ($facilities as $facility) {
            // updateOrCreate (berdasarkan nama) agar aman dijalankan berulang
            // tanpa menghapus data reservasi/laporan yang terhubung
            Facility::updateOrCreate(
                ['name' => $facility['name']],
                $facility
            );
        }
    }
}