<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pasien_id')->constrained('pasiens');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('jadwal_id')->constrained('jadwals');
            $table->date('tanggal_kunjungan');

            $table->enum('poli_tujuan', ['Poli Umum', 'Poli Kandungan']);
            $table->enum('tenaga_medis_tujuan', ['Dokter', 'Bidan'])->default('Dokter');
            $table->enum('jenis_pelayanan', ['umum', 'bpjs', 'asuransi'])->default('umum');
            $table->text('keluhan')->nullable();
            $table->enum('status', ['menunggu', 'diproses', 'selesai', 'batal'])->default('menunggu');
            $table->text('catatan')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // 🔹 1 jadwal (slot jam) per tanggal hanya boleh 1 pendaftaran
            $table->unique(
                ['jadwal_id', 'tanggal_kunjungan'],
                'unique_jadwal_per_tanggal'
            );
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};
