<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Pemeriksaan;
use App\Models\Diagnosa;
use App\Models\RekamMedis;

class RekamMedisSeeder extends Seeder
{
    public function run(): void
    {
        // Jika belum ada pasien, buat dummy
        if (Pasien::count() == 0) {
            Pasien::factory()->count(5)->create();
        }

        foreach (Pasien::all() as $pasien) {

            // Buat 2 pendaftaran per pasien
            for ($i = 1; $i <= 2; $i++) {

                $poli = fake()->randomElement(['Poli Umum', 'Poli Kandungan']);
                $tenaga = $poli === 'Poli Umum' ? 'Dokter' : 'Bidan';

                // 🟦 Create Pendaftaran (TANPA kolom tanggal_daftar)
                $pendaftaran = Pendaftaran::create([
                    'pasien_id' => $pasien->id,
                    'poli_tujuan' => $poli,
                    'tenaga_medis_tujuan' => $tenaga,
                ]);

                // 🟩 Create Pemeriksaan
                $pemeriksaan = Pemeriksaan::create([
                    'pendaftaran_id' => $pendaftaran->id,
                    'pasien_id'      => $pasien->id,
                    'dokter_id'      => 1, // fallback tenaga medis
                    'tanggal_periksa' => now()->subDays(rand(1, 10)),
                    'status'          => 'selesai',
                    'keluhan_utama'   => fake()->sentence(),
                    'tinggi_badan'    => rand(150, 180),
                    'berat_badan'     => rand(50, 80),
                    'tekanan_darah'   => '120/80',
                    'suhu'            => 36.5,
                    'nadi'            => 80,
                    'respirasi'       => 20,
                ]);

                // 🟨 Create Diagnosa
                $diagnosa = Diagnosa::create([
                    'pemeriksaan_id' => $pemeriksaan->id,
                    'nama_diagnosa'  => fake()->randomElement([
                        'Hipertensi', 'Gastritis', 'Demam Tinggi', 'Asma'
                    ]),
                    'jenis_diagnosa' => fake()->randomElement(['Utama', 'Sekunder']),
                    'deskripsi'      => fake()->sentence(),
                ]);

                // 🟥 Create Rekam Medis
                RekamMedis::create([
                    'pasien_id'        => $pasien->id,
                    'pemeriksaan_id'   => $pemeriksaan->id,
                    'diagnosa_id'      => $diagnosa->id,
                    'tanggal'          => now()->subDays(rand(0, 5)),
                    'rencana_terapi'   => fake()->sentence(),
                    'riwayat_alergi'   => fake()->randomElement(['Tidak ada', 'Alergi Obat']),
                    'riwayat_penyakit' => fake()->randomElement(['Tidak ada', 'Asma', 'Diabetes']),
                    'catatan'          => fake()->sentence(10),
                ]);
            }
        }
    }
}
