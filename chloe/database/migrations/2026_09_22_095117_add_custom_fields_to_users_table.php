<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'nim_nip')) {
                $table->string('nim_nip')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'unit')) {
                $table->string('unit')->nullable()->after('nim_nip');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('Pengguna')->after('unit');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('Aktif')->after('role');
            }
            if (!Schema::hasColumn('users', 'document_path')) {
                $table->string('document_path')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nim_nip', 'unit', 'role', 'status', 'document_path']);
        });
    }
};