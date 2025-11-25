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
        // 1. Ambil user yang berperan sebagai DOKTER & BIDAN
        //    (admin tidak akan pernah terpilih di sini)
        // =====================================================
        $dokter = User::whereHas('role', function ($query) {
            $query->where('name', 'dokter');
        })->first();

        $bidan = User::whereHas('role', function ($query) {
            $query->where('name', 'bidan');
        })->first();

        // Jika belum ada user dengan role dokter atau bidan, hentikan seeder
        if (! $dokter || ! $bidan) {
            $this->command?->warn(
                '❗ PemeriksaanSeeder membutuhkan minimal:' . PHP_EOL .
                    '- 1 user dengan role "dokter"' . PHP_EOL .
                    '- 1 user dengan role "bidan"' . PHP_EOL .
                    'Silakan jalankan atau periksa kembali AllAccountsSeeder / seeder user Anda.'
            );

            return;
        }

        // =====================================================
        // 2. Buat data pemeriksaan untuk pendaftaran POLI UMUM
        //    → ditangani oleh DOKTER
        // =====================================================
        $pendaftaranPoliUmum = Pendaftaran::where('poli_tujuan', 'Poli Umum')->get();

        foreach ($pendaftaranPoliUmum as $pendaftaran) {
            Pemeriksaan::create([
                'pendaftaran_id'  => $pendaftaran->id,
                'pasien_id'       => $pendaftaran->pasien_id,
                'dokter_id'       => $dokter->id, // Hanya user dengan role "dokter"
                'tanggal_periksa' => now(),
                'status'          => 'proses',

                // Anamnesis / keluhan
                'keluhan_utama'   => 'Pasien mengeluh pusing, lemas, dan nyeri ringan di bagian kepala sejak dua hari terakhir.',

                // Pemeriksaan fisik dasar
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
        //
        //    Catatan:
        //    - Kolom di tabel tetap bernama "dokter_id",
        //      tapi isinya diisi ID user dengan role "bidan".
        // =====================================================
        $pendaftaranPoliKandungan = Pendaftaran::where('poli_tujuan', 'Poli Kandungan')->get();

        foreach ($pendaftaranPoliKandungan as $pendaftaran) {
            Pemeriksaan::create([
                'pendaftaran_id'  => $pendaftaran->id,
                'pasien_id'       => $pendaftaran->pasien_id,
                'dokter_id'       => $bidan->id, // DIISI user dengan role "bidan", BUKAN admin
                'tanggal_periksa' => now(),
                'status'          => 'proses',

                // Anamnesis / keluhan
                'keluhan_utama'   => 'Pasien mengeluh nyeri perut bagian bawah dan mual, terutama pada pagi hari, sesuai keluhan awal kehamilan.',

                // Pemeriksaan fisik dasar
                'tinggi_badan'    => 160,
                'berat_badan'     => 55,
                'tekanan_darah'   => '110/70',
                'suhu'            => 36.6,
                'nadi'            => 90,
                'respirasi'       => 19,
            ]);
        }

        $this->command?->info('✅ PemeriksaanSeeder berhasil dijalankan. Data pemeriksaan terisi dengan dokter & bidan (tanpa admin).');
    }
}
