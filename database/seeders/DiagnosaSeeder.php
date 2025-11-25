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
            'Pasien menunjukkan gejala batuk dan demam tinggi, kemungkinan infeksi saluran pernapasan.',
            'Tekanan darah pasien di atas normal, dianjurkan kontrol rutin dan perubahan pola hidup.',
            'Keluhan nyeri perut bagian tengah muncul sejak kemarin, perlu evaluasi lebih lanjut.',
            'Pasien merasa pusing dan lemas, disarankan istirahat cukup dan menjaga pola makan.',
            'Kondisi ibu hamil dalam pemantauan rutin, gerak janin baik dan tekanan darah stabil.',
            'Pasien mengalami gangguan pernapasan ringan, diperlukan observasi dan terapi bronkodilator bila perlu.',
            'Tingkat gula darah pasien meningkat, kontrol diet dan pemeriksaan lanjutan dianjurkan.',
            'Pasien menunjukkan tanda-tanda anemia ringan, dianjurkan pemeriksaan darah dan suplementasi zat besi.',
            'Kondisi kesehatan umum pasien stabil namun dianjurkan kontrol berkala untuk pemantauan.',
            'Pasien mengeluhkan sakit kepala berulang, kemungkinan terkait stres atau ketegangan otot.',
        ];

        // === UPDATE SEMUA DATA LAMA ===
        Diagnosa::all()->each(function ($diagnosa) use ($deskripsiOptions) {
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
                    'nama_diagnosa'  => fake()->randomElement([
                        'Infeksi Saluran Pernapasan Akut (ISPA)',
                        'Gastritis',
                        'Hipertensi',
                        'Diabetes Mellitus',
                        'Anemia',
                        'Demam Tinggi',
                        'Sakit Kepala Tegang',
                        'Batuk Kronis',
                        'Asma Bronkial',
                        'Pemantauan Kehamilan',
                    ]),
                    'jenis_diagnosa' => fake()->randomElement([
                        'Utama',
                        'Sekunder',
                        'Komplikasi',
                    ]),
                    'deskripsi'      => fake()->randomElement($deskripsiOptions),
                ]);
            }
        }

        $this->command->info("✅ DiagnosaSeeder berhasil dijalankan dan semua deskripsi diagnosa menggunakan bahasa Indonesia.");
    }
}
