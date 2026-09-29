<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Melengkapi kolom detail fasilitas yang ditampilkan di halaman pengguna.
 * Setiap kolom hanya ditambahkan jika belum ada, jadi aman dijalankan
 * walaupun sebagian kolom sudah dibuat di migration sebelumnya.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            if (! Schema::hasColumn('facilities', 'area')) {
                $table->string('area', 100)->nullable()->after('capacity');
            }
            if (! Schema::hasColumn('facilities', 'amenities')) {
                $table->text('amenities')->nullable()->after('description');
            }
            if (! Schema::hasColumn('facilities', 'price_per_hour')) {
                $table->unsignedInteger('price_per_hour')->nullable()->after('amenities');
            }
            if (! Schema::hasColumn('facilities', 'contact_phone')) {
                $table->string('contact_phone', 30)->nullable()->after('price_per_hour');
            }
            if (! Schema::hasColumn('facilities', 'image')) {
                $table->string('image')->nullable()->after('contact_phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            // Hanya kolom 'area' yang pasti dibuat oleh migration ini
            if (Schema::hasColumn('facilities', 'area')) {
                $table->dropColumn('area');
            }
        });
    }
};