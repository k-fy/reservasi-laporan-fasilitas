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
                'name'           => 'Scanning Electron Microscope (SEM)',
                'type'           => 'alat',
                'location'       => 'Laboratorium Biologi Sentral Lt. 1',
                'capacity'       => 1,
                'area'           => null,
                'description'    => 'Mikroskop elektron resolusi tinggi untuk pencitraan tiga dimensi dan analisis struktur mikro permukaan sel biologis pada skala nanometer.',
                'amenities'      => 'Sputter Coater, PC Control Unit, Software Analisis Citra 3D',
                'contact_phone'  => '081234567894',
                'status'         => 'active',
            ],
            [
                'name'           => 'Illumina NovaSeq 6000',
                'type'           => 'alat',
                'location'       => 'Laboratorium Genomik Terpadu',
                'capacity'       => 1,
                'area'           => null,
                'description'    => 'Sistem sekuensing DNA berkapasitas ultra-tinggi untuk analisis genomik berskala besar, transkriptomik, dan penelitian molekuler tingkat lanjut.',
                'amenities'      => 'Flow Cell Kit, UPS Backup, Workstation Data Processing',
                'contact_phone'  => '081234567895',
                'status'         => 'active',
            ],
            [
                'name'           => 'Flow Cytometer (FACS Cell Sorter)',
                'type'           => 'alat',
                'location'       => 'Laboratorium Imunologi',
                'capacity'       => 2,
                'area'           => null,
                'description'    => 'Alat analisis seluler multiparameter berbasis laser untuk mengidentifikasi, memisahkan, dan menghitung karakteristik sel biologis hidup secara presisi.',
                'amenities'      => 'Laser 4-warna, Fluidics Cart, Tabung Pengumpul Sampel',
                'contact_phone'  => '081234567896',
                'status'         => 'active',
            ],
            [
                'name'           => 'Liquid Chromatography–Mass Spectrometry (LC-MS/MS)',
                'type'           => 'alat',
                'location'       => 'Laboratorium Farmakologi Khusus',
                'capacity'       => 1,
                'area'           => null,
                'description'    => 'Instrumen analitik tingkat tinggi untuk identifikasi dan kuantifikasi protein, farmakokinetik, serta senyawa metabolit kompleks dalam spesimen biologis.',
                'amenities'      => 'Kolom LC Standar, Nitrogen Generator, Tabung Pelarut',
                'contact_phone'  => '081234567897',
                'status'         => 'active',
            ],
            [
                'name'           => 'Real-Time PCR (qPCR) 384-Well Block',
                'type'           => 'alat',
                'location'       => 'Laboratorium Biologi Molekuler',
                'capacity'       => 40,
                'area'           => null,
                'description'    => 'Mesin amplifikasi asam nukleat high-throughput untuk deteksi ekspresi genetik, kuantifikasi patogen, dan diagnosis molekuler secara real-time.',
                'amenities'      => '384-Well Plate, PC dengan Software Analisis qPCR, UPS',
                'contact_phone'  => '081234567898',
                'status'         => 'active',
            ],
            [
                'name'           => 'Ultracentrifuge Optima XPN',
                'type'           => 'alat',
                'location'       => 'Laboratorium Biofisika',
                'capacity'       => 2,
                'area'           => null,
                'description'    => 'Sentrifus berkinerja tinggi dengan kecepatan hingga 100.000 rpm untuk isolasi dan purifikasi virus, protein, DNA, dan nanopartikel biologis.',
                'amenities'      => 'Rotor Titanium Berbagai Ukuran, Tabung Ultrasentrifus, Sistem Vakum',
                'contact_phone'  => '081234567899',
                'status'         => 'active',
            ],
            [
                'name'           => 'Cutera Excel V Laser System',
                'type'           => 'alat',
                'location'       => 'Laboratorium Dermatologi Estetika',
                'capacity'       => 1,
                'area'           => null,
                'description'    => 'Sistem laser medis presisi tinggi dengan panjang gelombang ganda untuk perawatan lesi vaskular, pigmentasi, dan peremajaan kulit tingkat lanjut.',
                'amenities'      => 'CoolView Handpiece, Genesis V Handpiece, Kacamata Pelindung Laser',
                'contact_phone'  => '081234567900',
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
            'Aula Utama "Beau"'                                     => 'aula-utama.jpg',
            'Auditorium Prof. Dr. Satrio'                           => 'auditorium.jpg',
            'Ruang Teater Gedung B'                                 => 'ruang-teater.jpg',
            'Ruang Sidang Lt. 3'                                    => 'ruang-sidang.jpg',
            'Ruang Kelas 201'                                       => 'ruang-kelas-201.jpg',
            'Ruang Kelas 304'                                       => 'ruang-kelas-304.jpg',
            'Ruang Diskusi Perpustakaan'                            => 'ruang-diskusi.jpg',
            'Lab Komputer 1 (Gedung A)'                             => 'lab-komputer.jpg',
            'Lab Jaringan & Keamanan Siber'                         => 'lab-jaringan.jpg',
            'Lab Multimedia & Desain'                               => 'lab-multimedia.jpg',
            'Lapangan Outdoor Utama'                                => 'lapangan-outdoor.jpg',
            'Lapangan Basket Indoor'                                => 'lapangan-basket.jpg',
            'Scanning Electron Microscope (SEM)'                    => 'SEM.jpg',
            'Illumina NovaSeq 6000'                                 => 'NovaSeq6000.jpg',
            'Flow Cytometer (FACS Cell Sorter)'                     => 'FACS.png',
            'Liquid Chromatography–Mass Spectrometry (LC-MS/MS)'    => 'Spectrometry.jpg',
            'Real-Time PCR (qPCR) 384-Well Block'                   => 'PCR.jpg',
            'Ultracentrifuge Optima XPN'                            => 'Ultracentrifuge.jpg',
            'Cutera Excel V Laser System'                           => 'cutera.jpg',
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