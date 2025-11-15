<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Diagnosa;
use App\Models\Pemeriksaan;
use Illuminate\Support\Str;

class DiagnosaSeeder extends Seeder
{
    public function run(): void
    {
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
                    'deskripsi' => fake()->sentence(8),
                ]);
            }
        }

        $this->command->info("✅ DiagnosaSeeder berhasil dijalankan.");
    }
}
