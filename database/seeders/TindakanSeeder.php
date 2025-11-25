<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tindakan;

class TindakanSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================
        // TINDAKAN UNTUK ROLE DOKTER
        // ==========================
        $tindakanDokter = [
            [
                'nama_tindakan' => 'Pemeriksaan Fisik Umum',
                'deskripsi'     => 'Pemeriksaan fisik lengkap oleh dokter umum, termasuk pemeriksaan tekanan darah, nadi, suhu, dan kondisi umum.',
                'tarif'         => 25000,
                'role'          => 'dokter',
            ],
            [
                'nama_tindakan' => 'Nebulizer',
                'deskripsi'     => 'Tindakan nebulizer untuk membantu melegakan saluran pernapasan pada pasien dengan keluhan sesak atau batuk berat.',
                'tarif'         => 30000,
                'role'          => 'dokter',
            ],
        ];

        // ==========================
        // TINDAKAN UNTUK ROLE BIDAN
        // ==========================
        $tindakanBidan = [
            [
                'nama_tindakan' => 'Pemeriksaan Kehamilan Rutin',
                'deskripsi'     => 'Pemeriksaan kehamilan oleh bidan, termasuk pemantauan tekanan darah ibu, berat badan, dan kondisi janin.',
                'tarif'         => 35000,
                'role'          => 'bidan',
            ],
            [
                'nama_tindakan' => 'USG Kehamilan 2D',
                'deskripsi'     => 'Pemeriksaan USG 2D untuk melihat kondisi janin dan usia kehamilan.',
                'tarif'         => 80000,
                'role'          => 'bidan',
            ],
        ];

        foreach ($tindakanDokter as $t) {
            Tindakan::create($t);
        }

        foreach ($tindakanBidan as $t) {
            Tindakan::create($t);
        }
    }
}
