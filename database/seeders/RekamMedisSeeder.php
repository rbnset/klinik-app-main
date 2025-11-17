<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Pemeriksaan;
use App\Models\Diagnosa;
use App\Models\RekamMedis;
use App\Models\Jadwal;

class RekamMedisSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil jadwal slot pertama: 08:00–08:30
        $jadwal = Jadwal::first();

        // Jika tidak ada, buat default saja
        if (!$jadwal) {
            $jadwal = Jadwal::create([
                'user_id' => 1, 
                'hari' => 'senin',
                'jam_mulai' => '08:00',
                'jam_selesai' => '08:30',
                'keterangan' => 'Ada',
                'sesi' => '1',
            ]);
        }

        // Pastikan ada pasien
        if (Pasien::count() == 0) {
            Pasien::factory()->count(5)->create();
        }

        foreach (Pasien::all() as $pasien) {

            for ($i = 1; $i <= 2; $i++) {

                $poli = fake()->randomElement(['Poli Umum', 'Poli Kandungan']);
                $tenaga = $poli === 'Poli Umum' ? 'Dokter' : 'Bidan';

                // 📌 Buat pendaftaran (WAJIB ISI SEMUA FIELD)
                $pendaftaran = Pendaftaran::create([
                    'pasien_id'             => $pasien->id,
                    'user_id'               => null,
                    'jadwal_id'             => $jadwal->id,
                    'poli_tujuan'           => $poli,
                    'tenaga_medis_tujuan'   => $tenaga,
                    'jenis_pelayanan'       => 'umum',
                    'keluhan'               => fake()->sentence(),
                    'status'                => 'selesai',
                    'catatan'               => fake()->sentence(6),
                ]);

                // Pemeriksaan
                $pemeriksaan = Pemeriksaan::create([
                    'pendaftaran_id' => $pendaftaran->id,
                    'pasien_id'      => $pasien->id,
                    'dokter_id'      => 1,
                    'tanggal_periksa'=> now()->subDays(rand(1, 10)),
                    'status'         => 'selesai',
                    'keluhan_utama'  => fake()->sentence(),
                    'tinggi_badan'   => rand(150, 180),
                    'berat_badan'    => rand(50, 80),
                    'tekanan_darah'  => '120/80',
                    'suhu'           => 36.5,
                    'nadi'           => 80,
                    'respirasi'      => 20,
                ]);

                // Diagnosa
                $diagnosa = Diagnosa::create([
                    'pemeriksaan_id' => $pemeriksaan->id,
                    'nama_diagnosa'  => fake()->randomElement([
                        'Hipertensi', 'Gastritis', 'Demam Tinggi', 'Asma'
                    ]),
                    'jenis_diagnosa' => fake()->randomElement(['Utama', 'Sekunder']),
                    'deskripsi'      => fake()->sentence(),
                ]);

                // Rekam Medis
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
