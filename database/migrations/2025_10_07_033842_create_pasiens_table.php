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
        Schema::create('pasiens', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique()->nullable(); // Nomor Induk Kependudukan
            $table->string('nama_pasien', 25)->nullable();
            $table->string('tempat_lahir', 25)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O'])->nullable();
            $table->enum('agama', [
                'Islam',
                'Kristen Protestan',
                'Katolik',
                'Hindu',
                'Buddha',
                'Konghucu',
                'Lainnya'
            ])->nullable();
            $table->enum('status_perkawinan', ['Belum Kawin', 'Kawin'])->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_telp', 13)->nullable();
            $table->string('pekerjaan', 50)->nullable();
            $table->string('nama_penanggung_jawab', 25)->nullable();
            $table->string('no_telp_penanggung_jawab', 13)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasiens');
    }
};
