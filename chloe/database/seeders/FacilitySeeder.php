<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
                'contact_phone'  => '081234567897',
                // Contoh fasilitas yang sedang diperbaiki
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
                'contact_phone'  => '081234567901',
                // Contoh fasilitas yang dinonaktifkan admin (tidak tampil ke publik)
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
                'contact_phone'  => '081234567895',
                'status'         => 'active',
            ],
        ];

        /*
         * Foto fasilitas: simpan file di database/seeders/images/facilities/
         * dengan nama sesuai daftar di bawah. Saat seeder dijalankan, foto disalin ke
         * storage/app/public/facilities dan dipasang ke fasilitasnya.
         * Jika file tidak ditemukan, fasilitas tetap dibuat tanpa foto.
         */
        $photos = [
            'Aula Utama "Beau"'             => 'aula-utama.jpg',
            'Auditorium Prof. Dr. Satrio'   => 'auditorium.jpg',
            'Ruang Teater Gedung B'         => 'ruang-teater.jpg',
            'Ruang Sidang Lt. 3'            => 'ruang-sidang.jpg',
            'Ruang Kelas 201'               => 'ruang-kelas-201.jpg',
            'Ruang Kelas 304'               => 'ruang-kelas-304.jpg',
            'Ruang Diskusi Perpustakaan'    => 'ruang-diskusi.jpg',
            'Lab Komputer 1 (Gedung A)'     => 'lab-komputer.jpg',
            'Lab Jaringan & Keamanan Siber' => 'lab-jaringan.jpg',
            'Lab Multimedia & Desain'       => 'lab-multimedia.jpg',
            'Lapangan Outdoor Utama'        => 'lapangan-outdoor.jpg',
            'Lapangan Basket Indoor'        => 'lapangan-basket.jpg',
            'Proyektor Portable Epson'      => 'proyektor.jpg',
            'Set Sound Portable Wireless'   => 'sound-system.jpg',
            'Kamera DSLR Profesional'       => 'kamera-dslr.jpg',
        ];
        $photoDir = database_path('seeders/images/facilities');

        foreach ($facilities as $facility) {
            // Pasang foto jika file-nya tersedia
            $file = $photos[$facility['name']] ?? null;
            if ($file && File::exists("{$photoDir}/{$file}")) {
                Storage::disk('public')->put("facilities/{$file}", File::get("{$photoDir}/{$file}"));
                $facility['image'] = "facilities/{$file}";
            }

            // updateOrCreate (berdasarkan nama) agar aman dijalankan berulang
            // tanpa menghapus data reservasi/laporan yang terhubung
            Facility::updateOrCreate(
                ['name' => $facility['name']],
                $facility
            );
        }
    }
}