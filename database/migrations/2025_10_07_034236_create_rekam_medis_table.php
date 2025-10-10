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
        Schema::create('rekam_medis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pasien_id')->constrained('pasiens');
            $table->foreignId('pemeriksaan_id')->constrained('pemeriksaans');
            $table->foreignId('diagnosa_id')->constrained('diagnosas'); // diagnosa utama
             $table->dateTime('tanggal');
            $table->text('riwayat_alergi')->nullable();
            $table->text('riwayat_penyakit')->nullable();
            $table->text('rencana_terapi')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekam_medis');
    }
};
