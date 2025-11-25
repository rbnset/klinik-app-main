<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RekamMedis;
use App\Models\RekamMedisDetail;

class RekamMedisDetailSeeder extends Seeder
{
    public function run(): void
    {
        if (RekamMedis::count() == 0) {
            $this->command->warn("⚠ Tabel RekamMedis masih kosong. Jalankan RekamMedisSeeder terlebih dahulu.");
            return;
        }

        foreach (RekamMedis::with('pendaftaran')->get() as $rekamMedis) {

            $poli = $rekamMedis->pendaftaran->poli_tujuan;

            /** ============================
             *  POLI UMUM → DOKTER
             * ============================ */
            if ($poli === 'Poli Umum') {

                // Obat
                RekamMedisDetail::create([
                    'rekam_medis_id' => $rekamMedis->id,
                    'tipe'           => 'obat',
                    'deskripsi'      => 'Paracetamol 500 mg diminum 3x sehari setelah makan.',
                    'qty'            => 10,
                    'satuan'         => 'tablet',
                    'harga_satuan'   => 1500,
                    'subtotal'       => 10 * 1500,
                ]);

                // Suntik
                RekamMedisDetail::create([
                    'rekam_medis_id' => $rekamMedis->id,
                    'tipe'           => 'suntik',
                    'deskripsi'      => 'Vitamin B Kompleks injeksi sekali pemberian.',
                    'qty'            => 1,
                    'satuan'         => 'ampul',
                    'harga_satuan'   => 25000,
                    'subtotal'       => 1 * 25000,
                ]);

                // Infus
                RekamMedisDetail::create([
                    'rekam_medis_id' => $rekamMedis->id,
                    'tipe'           => 'infus',
                    'deskripsi'      => 'Infus NaCl 0.9% untuk rehidrasi.',
                    'qty'            => 1,
                    'satuan'         => 'botol',
                    'harga_satuan'   => 35000,
                    'subtotal'       => 1 * 35000,
                ]);
            }

            /** ============================
             *  POLI KANDUNGAN → BIDAN
             * ============================ */
            if ($poli === 'Poli Kandungan') {

                RekamMedisDetail::create([
                    'rekam_medis_id' => $rekamMedis->id,
                    'tipe'           => 'obat',
                    'deskripsi'      => 'Asam folat 400 mcg diminum 1x sehari.',
                    'qty'            => 30,
                    'satuan'         => 'tablet',
                    'harga_satuan'   => 1000,
                    'subtotal'       => 30 * 1000,
                ]);

                RekamMedisDetail::create([
                    'rekam_medis_id' => $rekamMedis->id,
                    'tipe'           => 'suntik',
                    'deskripsi'      => 'Imunisasi TT sesuai jadwal kehamilan.',
                    'qty'            => 1,
                    'satuan'         => 'ampul',
                    'harga_satuan'   => 50000,
                    'subtotal'       => 1 * 50000,
                ]);

                RekamMedisDetail::create([
                    'rekam_medis_id' => $rekamMedis->id,
                    'tipe'           => 'infus',
                    'deskripsi'      => 'Infus D5 untuk menjaga hidrasi dan kebutuhan energi.',
                    'qty'            => 1,
                    'satuan'         => 'botol',
                    'harga_satuan'   => 30000,
                    'subtotal'       => 1 * 30000,
                ]);
            }
        }
    }
}
