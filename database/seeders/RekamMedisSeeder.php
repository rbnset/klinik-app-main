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
        if (! $jadwal) {
            $jadwal = Jadwal::create([
                'user_id'     => 1,        // pastikan ada user_id=1 (dokter/bidan) atau sesuaikan
                'hari'        => 'senin',
                'jam_mulai'   => '08:00',
                'jam_selesai' => '08:30',
                'keterangan'  => 'Ada',
                'sesi'        => '1',
            ]);
        }

        // Pastikan ada pasien
        if (Pasien::count() === 0) {
            Pasien::factory()->count(5)->create();
        }

        // Pool teks dalam bahasa Indonesia
        $keluhanPendaftaranOptions = [
            'Demam sejak dua hari terakhir disertai batuk kering dan sakit kepala.',
            'Nyeri perut bagian tengah terutama setelah makan.',
            'Sesak napas saat beraktivitas dan batuk berdahak.',
            'Pusing berulang, terutama ketika bangun dari posisi duduk atau berbaring.',
            'Mual dan muntah terutama pada pagi hari.',
        ];

        $catatanPendaftaranOptions = [
            'Pasien diarahkan untuk kontrol ulang bila keluhan tidak berkurang.',
            'Pasien sudah diberikan edukasi mengenai pola makan dan istirahat.',
            'Pasien dianjurkan untuk banyak minum air putih dan mengurangi makanan berlemak.',
            'Pasien disarankan memantau suhu tubuh di rumah.',
            'Pasien diminta datang kembali jika muncul gejala sesak berat atau nyeri dada.',
        ];

        $keluhanPemeriksaanOptions = [
            'Keluhan utama berupa demam tinggi, batuk, dan rasa tidak enak badan.',
            'Pasien mengeluhkan sakit perut yang hilang timbul sejak kemarin.',
            'Pasien merasa cepat lelah dan sering pusing saat beraktivitas.',
            'Keluhan nyeri pada sendi lutut saat berjalan jauh.',
            'Pasien mengeluh mual dan muntah ringan disertai penurunan nafsu makan.',
        ];

        $deskripsiDiagnosaOptions = [
            'Keluhan dan hasil pemeriksaan mengarah pada infeksi saluran pernapasan ringan.',
            'Kemungkinan gastritis, dianjurkan menghindari makanan pedas dan asam.',
            'Tekanan darah pasien cukup tinggi, perlu pemantauan berkala dan pengaturan pola hidup.',
            'Gejala mengarah pada anemia ringan, dianjurkan pemeriksaan darah lanjutan.',
        ];

        $rencanaTerapiOptions = [
            'Memberikan obat simptomatik dan menyarankan istirahat cukup.',
            'Meresepkan obat lambung dan edukasi pola makan teratur.',
            'Menganjurkan kontrol tekanan darah rutin dan pengurangan konsumsi garam.',
            'Memberikan suplemen zat besi dan saran untuk konsumsi makanan tinggi zat besi.',
        ];

        $catatanRekamMedisOptions = [
            'Pasien tampak kooperatif dan memahami penjelasan yang diberikan.',
            'Edukasi sudah disampaikan kepada pasien dan keluarga mengenai kondisi yang dialami.',
            'Dianjurkan untuk segera kembali ke fasilitas kesehatan bila gejala memburuk.',
            'Pasien diminta membawa hasil pemeriksaan penunjang jika datang kontrol berikutnya.',
            'Resep dan jadwal kontrol sudah disampaikan dan disetujui oleh pasien.',
        ];

        $counter = 0; // untuk menjamin tanggal_kunjungan unik per pendaftaran

        foreach (Pasien::all() as $pasien) {

            for ($i = 1; $i <= 2; $i++) {

                $poli   = fake()->randomElement(['Poli Umum', 'Poli Kandungan']);
                $tenaga = $poli === 'Poli Umum' ? 'Dokter' : 'Bidan';

                // Tanggal kunjungan unik per pendaftaran, tapi masih dekat dengan hari ini
                $tanggalKunjungan = now()->subDays($counter)->toDateString();
                $counter++;

                // 📌 Buat pendaftaran
                $pendaftaran = Pendaftaran::create([
                    'pasien_id'            => $pasien->id,
                    'user_id'              => null,
                    'jadwal_id'            => $jadwal->id,
                    'tanggal_kunjungan'    => $tanggalKunjungan,
                    'poli_tujuan'          => $poli,
                    'tenaga_medis_tujuan'  => $tenaga,
                    'jenis_pelayanan'      => 'umum',
                    'keluhan'              => fake()->randomElement($keluhanPendaftaranOptions),
                    'status'               => 'selesai',
                    'catatan'              => fake()->randomElement($catatanPendaftaranOptions),
                ]);

                // Pemeriksaan
                $pemeriksaan = Pemeriksaan::create([
                    'pendaftaran_id'  => $pendaftaran->id,
                    'pasien_id'       => $pasien->id,
                    'dokter_id'       => 1,                         // sesuaikan dengan user dokter yang ada
                    'tanggal_periksa' => now()->subDays(rand(1, 10)),
                    'status'          => 'selesai',
                    'keluhan_utama'   => fake()->randomElement($keluhanPemeriksaanOptions),
                    'tinggi_badan'    => rand(150, 180),
                    'berat_badan'     => rand(50, 80),
                    'tekanan_darah'   => '120/80',
                    'suhu'            => 36.5,
                    'nadi'            => 80,
                    'respirasi'       => 20,
                ]);

                // Diagnosa
                $diagnosa = Diagnosa::create([
                    'pemeriksaan_id' => $pemeriksaan->id,
                    'nama_diagnosa'  => fake()->randomElement([
                        'Hipertensi',
                        'Gastritis',
                        'Demam Tinggi',
                        'Asma',
                    ]),
                    'jenis_diagnosa' => fake()->randomElement(['Utama', 'Sekunder']),
                    'deskripsi'      => fake()->randomElement($deskripsiDiagnosaOptions),
                ]);

                // Rekam Medis
                RekamMedis::create([
                    'pasien_id'        => $pasien->id,
                    'pemeriksaan_id'   => $pemeriksaan->id,
                    'diagnosa_id'      => $diagnosa->id,
                    'tanggal'          => now()->subDays(rand(0, 5)),
                    'rencana_terapi'   => fake()->randomElement($rencanaTerapiOptions),
                    'riwayat_alergi'   => fake()->randomElement(['Tidak ada', 'Alergi Obat']),
                    'riwayat_penyakit' => fake()->randomElement(['Tidak ada', 'Asma', 'Diabetes']),
                    'catatan'          => fake()->randomElement($catatanRekamMedisOptions),
                ]);
            }
        }
    }
}
