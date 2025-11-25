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
        // =====================================================
        // 1. Ambil user yang berperan sebagai dokter & bidan
        // =====================================================
        $dokter = User::whereHas('role', function ($q) {
            $q->where('name', 'dokter');
        })->first();

        $bidan = User::whereHas('role', function ($q) {
            $q->where('name', 'bidan');
        })->first();

        if (! $dokter || ! $bidan) {
            $this->command?->warn(
                '❗ PemeriksaanSeeder membutuhkan minimal satu user dengan role "dokter" dan satu user dengan role "bidan". ' .
                    'Silakan buat terlebih dahulu user dokter dan bidan sebelum menjalankan seeder ini.'
            );

            return;
        }

        // =====================================================
        // 2. Buat data pemeriksaan untuk pendaftaran POLI UMUM
        //    → ditangani oleh DOKTER
        // =====================================================
        $pendaftaranDokter = Pendaftaran::where('poli_tujuan', 'Poli Umum')->get();

        foreach ($pendaftaranDokter as $daftar) {
            Pemeriksaan::create([
                'pendaftaran_id'  => $daftar->id,
                'pasien_id'       => $daftar->pasien_id,
                'dokter_id'       => $dokter->id,
                'tanggal_periksa' => now(),
                'status'          => 'proses',
                'keluhan_utama'   => 'Pasien mengeluh pusing, lemas, dan nyeri ringan di bagian kepala sejak dua hari terakhir.',
                'tinggi_badan'    => 165,
                'berat_badan'     => 60,
                'tekanan_darah'   => '120/80',
                'suhu'            => 36.7,
                'nadi'            => 88,
                'respirasi'       => 20,
            ]);
        }

        // =====================================================
        // 3. Buat data pemeriksaan untuk pendaftaran POLI KANDUNGAN
        //    → ditangani oleh BIDAN
        // =====================================================
        $pendaftaranBidan = Pendaftaran::where('poli_tujuan', 'Poli Kandungan')->get();

        foreach ($pendaftaranBidan as $daftar) {
            Pemeriksaan::create([
                'pendaftaran_id'  => $daftar->id,
                'pasien_id'       => $daftar->pasien_id,
                'dokter_id'       => $bidan->id, // kolom tetap bernama dokter_id
                'tanggal_periksa' => now(),
                'status'          => 'proses',
                'keluhan_utama'   => 'Pasien mengeluh nyeri perut bagian bawah dan mual, terutama pada pagi hari, sesuai keluhan awal kehamilan.',
                'tinggi_badan'    => 160,
                'berat_badan'     => 55,
                'tekanan_darah'   => '110/70',
                'suhu'            => 36.6,
                'nadi'            => 90,
                'respirasi'       => 19,
            ]);
        }
    }
}
