<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RekamMedis;
use App\Models\RekamMedisDetail;

class RekamMedisDetailSeeder extends Seeder
{
    public function run(): void
    {
        if (RekamMedis::count() === 0) {
            $this->command->warn("⚠ Tabel rekam_medis masih kosong. Jalankan RekamMedisSeeder terlebih dahulu.");
            return;
        }

        $rekamMedisList = RekamMedis::with(['pendaftaran'])->get();

        foreach ($rekamMedisList as $rekamMedis) {
            $poli = $rekamMedis->pendaftaran?->poli_tujuan;

            if (! $poli) {
                continue;
            }

            /** ====================================================
             *  POLI UMUM → DOKTER
             * ==================================================== */
            if ($poli === 'Poli Umum') {

                RekamMedisDetail::create([
                    'rekam_medis_id' => $rekamMedis->id,
                    'tipe'           => 'obat',
                    'deskripsi'      => 'Paracetamol 500 mg diminum 3x sehari setelah makan.',
                    'qty'            => 10,
                    'satuan'         => 'tablet',
                ]);

                RekamMedisDetail::create([
                    'rekam_medis_id' => $rekamMedis->id,
                    'tipe'           => 'suntik',
                    'deskripsi'      => 'Injeksi Vitamin B Kompleks sekali pemberian.',
                    'qty'            => 1,
                    'satuan'         => 'ampul',
                ]);

                RekamMedisDetail::create([
                    'rekam_medis_id' => $rekamMedis->id,
                    'tipe'           => 'infus',
                    'deskripsi'      => 'Infus NaCl 0.9% untuk rehidrasi.',
                    'qty'            => 1,
                    'satuan'         => 'botol',
                ]);
            }

            /** ====================================================
             *  POLI KANDUNGAN → BIDAN
             * ==================================================== */
            if ($poli === 'Poli Kandungan') {

                RekamMedisDetail::create([
                    'rekam_medis_id' => $rekamMedis->id,
                    'tipe'           => 'obat',
                    'deskripsi'      => 'Asam folat 400 mcg diminum 1x sehari.',
                    'qty'            => 30,
                    'satuan'         => 'tablet',
                ]);

                RekamMedisDetail::create([
                    'rekam_medis_id' => $rekamMedis->id,
                    'tipe'           => 'suntik',
                    'deskripsi'      => 'Imunisasi TT sesuai jadwal kehamilan.',
                    'qty'            => 1,
                    'satuan'         => 'ampul',
                ]);

                RekamMedisDetail::create([
                    'rekam_medis_id' => $rekamMedis->id,
                    'tipe'           => 'infus',
                    'deskripsi'      => 'Infus D5 untuk menjaga hidrasi dan energi ibu hamil.',
                    'qty'            => 1,
                    'satuan'         => 'botol',
                ]);
            }
        }
    }
}
