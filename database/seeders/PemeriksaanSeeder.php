<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use App\Models\User;

class PemeriksaanSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil dokter dan bidan
        $dokter = User::whereHas('role', fn($q) => $q->where('name', 'dokter'))->first();
        $bidan  = User::whereHas('role', fn($q) => $q->where('name', 'bidan'))->first();

        if (!$dokter || !$bidan) {
            $this->command->warn('❗ Harus ada user dengan role dokter dan bidan.');
            return;
        }

        // Pendaftaran poli umum → dokter
        $pendaftaranDokter = Pendaftaran::where('poli_tujuan', 'Poli Umum')->get();

        foreach ($pendaftaranDokter as $daftar) {
            Pemeriksaan::create([
                'pendaftaran_id' => $daftar->id,
                'pasien_id'      => $daftar->pasien_id,
                'dokter_id'      => $dokter->id,
                'tanggal_periksa' => now(),
                'status'         => 'proses',
                'keluhan_utama'  => 'Keluhan utama untuk poli umum',
                'tinggi_badan'   => 165,
                'berat_badan'    => 60,
                'tekanan_darah'  => '120/80',
                'suhu'           => 36.7,
                'nadi'           => 88,
                'respirasi'      => 20,
            ]);
        }

        // Pendaftaran poli kandungan → bidan
        $pendaftaranBidan = Pendaftaran::where('poli_tujuan', 'Poli Kandungan')->get();

        foreach ($pendaftaranBidan as $daftar) {
            Pemeriksaan::create([
                'pendaftaran_id' => $daftar->id,
                'pasien_id'      => $daftar->pasien_id,
                'dokter_id'      => $bidan->id,
                'tanggal_periksa' => now(),
                'status'         => 'proses',
                'keluhan_utama'  => 'Keluhan utama untuk poli kandungan',
                'tinggi_badan'   => 160,
                'berat_badan'    => 55,
                'tekanan_darah'  => '110/70',
                'suhu'           => 36.6,
                'nadi'           => 90,
                'respirasi'      => 19,
            ]);
        }
    }
}
