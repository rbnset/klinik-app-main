<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Diagnosa;
use App\Models\Pemeriksaan;

class DiagnosaSeeder extends Seeder
{
    public function run(): void
    {
        $deskripsiOptions = [
            'Pasien menunjukkan gejala batuk dan demam tinggi.',
            'Tekanan darah pasien di atas normal, perlu observasi lebih lanjut.',
            'Keluhan nyeri perut muncul sejak kemarin.',
            'Pasien merasa pusing dan lemas, dianjurkan istirahat cukup.',
            'Kondisi ibu hamil dalam pemantauan rutin.',
            'Pasien mengalami gangguan pernapasan ringan.',
            'Tingkat gula darah pasien perlu dikontrol lebih ketat.',
            'Pasien menunjukkan tanda-tanda anemia ringan.',
            'Kondisi kesehatan umum pasien stabil namun perlu kontrol berkala.',
            'Pasien mengeluhkan sakit kepala berulang kali.'
        ];

        // === UPDATE SEMUA DATA LAMA ===
        Diagnosa::all()->each(function($diagnosa) use ($deskripsiOptions) {
            $diagnosa->deskripsi = fake()->randomElement($deskripsiOptions);
            $diagnosa->save();
        });

        // === BUAT DATA BARU DARI TABEL PEMERIKSAAN ===
        $pemeriksaans = Pemeriksaan::all();

        if ($pemeriksaans->count() == 0) {
            $this->command->warn("⚠ Tidak ada data pemeriksaan. Seeder Diagnosa dilewati.");
            return;
        }

        foreach ($pemeriksaans as $pemeriksaan) {
            // Buat 1–3 diagnosa per pemeriksaan
            $jumlah = rand(1, 3);

            for ($i = 0; $i < $jumlah; $i++) {
                Diagnosa::create([
                    'pemeriksaan_id' => $pemeriksaan->id,
                    'nama_diagnosa' => fake()->randomElement([
                        'Infeksi Saluran Pernapasan Akut',
                        'Gastritis',
                        'Hipertensi',
                        'Diabetes Mellitus',
                        'Anemia',
                        'ISPA',
                        'Demam Tinggi',
                        'Sakit Kepala',
                        'Batuk Kronis',
                        'Masalah Kehamilan'
                    ]),
                    'jenis_diagnosa' => fake()->randomElement([
                        'Utama',
                        'Sekunder',
                        'Komplikasi'
                    ]),
                    'deskripsi' => fake()->randomElement($deskripsiOptions),
                ]);
            }
        }

        $this->command->info("✅ DiagnosaSeeder berhasil dijalankan dan semua deskripsi diubah ke bahasa Indonesia.");
    }
}
