<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('facility_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('location')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('purpose')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};