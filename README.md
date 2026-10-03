# CHLOE

**Campus Hall & Location Online E-booking**

Chloe adalah aplikasi web untuk mengelola penggunaan fasilitas kampus seperti ruang kelas, aula, laboratorium, lapangan, dan alat. Pengguna dapat mengecek ketersediaan, mengajukan reservasi, dan melaporkan kerusakan fasilitas. Petugas dan admin memproses semuanya dalam satu sistem.

Proyek ini dibuat untuk tugas UTS mata kuliah Pengembangan Platform Khusus 2026.

---

## Anggota Kelompok

| Nama | NIM |
|---|---|
| Silvani Salsabilla | 24060124130066 |
| Sarifa Nuha Ardanti J. | 24060124130082|
| Zulfa Nabilah | 24060124130095 |
| Puti Shasta Khafiyani | 24060124140132 |

---

## Teknologi

Laravel 13, PHP 8.5, MySQL, Tailwind CSS, Alpine.js, dan Vite.

---

## Cara Menjalankan

Pastikan PHP 8.5, Composer, Node.js, dan MySQL sudah terpasang. Kami menggunakan Laragon selama pengembangan.

**1. Masuk ke folder backend**

```
cd backend
```

**2. Install dependensi**

```
composer install
npm install
npm run build
```

**3. Siapkan file .env**

```
copy .env.example .env
php artisan key:generate
```

**4. Buat database**

Buat database kosong bernama `chloe_db`, lalu pastikan pengaturan di file `.env` sudah sesuai:

```
DB_DATABASE=chloe_db
DB_USERNAME=root
DB_PASSWORD=
```

**5. Buat tabel dan isi data awal**

```
php artisan migrate --seed
```

**6. Hubungkan folder storage** agar foto fasilitas dan foto profil bisa tampil

```
php artisan storage:link
```

**7. Jalankan aplikasi**

```
php artisan serve
```

Buka **http://127.0.0.1:8000** di browser.

---

## Akun Login

Semua akun menggunakan password `password123`.

| Role | Email |
|---|---|
| Admin | admin@charm.ac.id |
| Petugas | petugas@charm.ac.id |
| Pengguna | user@charm.ac.id |

Akun tambahan untuk mencoba fitur verifikasi:

- `pending1@charm.ac.id` dan `pending2@charm.ac.id` belum diverifikasi admin, jadi belum bisa login.
- `suspended@charm.ac.id` sedang ditangguhkan dan tidak bisa login.

Pengunjung dapat melihat daftar fasilitas dan mengecek ketersediaan tanpa login.

---

## Fitur Utama

**Pengunjung** dapat melihat daftar fasilitas dan mengecek ketersediaan berdasarkan tanggal dan jam.

**Pengguna** dapat mendaftar akun, mengajukan dan membatalkan reservasi, melihat riwayat reservasi, serta melaporkan kerusakan fasilitas.

**Petugas** memproses reservasi (setuju, tolak, batalkan), menangani laporan kerusakan, dan menandai fasilitas yang sedang diperbaiki.

**Admin** mengelola akun dan data fasilitas, memverifikasi akun baru, serta melihat dan mengunduh rekap okupansi dan kerusakan dalam format CSV, Excel, dan PDF.

---

## Ketentuan Sistem

- Jam operasional 07.00 - 20.00 dengan slot 30 menit.
- Reservasi diajukan paling lambat H-2 sebelum tanggal pemakaian.
- Pengguna dapat membatalkan reservasi paling lambat 24 jam sebelum jadwal.
- Peminjaman fasilitas tidak dipungut biaya.
- Akun hasil registrasi harus diverifikasi admin sebelum dapat digunakan.
- Reservasi dan laporan kerusakan hanya dapat dibuat oleh pengguna.