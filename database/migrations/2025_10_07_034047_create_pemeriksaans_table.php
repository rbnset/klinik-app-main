<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pemeriksaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans');
            $table->foreignId('pasien_id')->constrained('pasiens');
            $table->foreignId('dokter_id')->constrained('users'); // dokter yang memeriksa
            $table->dateTime('tanggal_periksa');
            $table->text('keluhan_utama');
            $table->decimal('tinggi_badan', 5, 2)->nullable(); // cm
            $table->decimal('berat_badan', 5, 2)->nullable();  // kg
            $table->string('tekanan_darah', 15)->nullable();   // "120/80"
            $table->decimal('suhu', 4, 1)->nullable();         // 36.7
            $table->unsignedSmallInteger('nadi')->nullable();  // bpm
            $table->unsignedSmallInteger('respirasi')->nullable(); // per menit
            $table->enum('status', ['proses', 'selesai', 'dirujuk'])->default('proses');
            $table->timestamps();
             $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaans');
    }
};
