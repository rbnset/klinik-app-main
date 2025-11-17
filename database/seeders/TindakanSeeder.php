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
                'deskripsi'     => 'Pemeriksaan fisik oleh dokter umum',
                'tarif'         => 25000,
                'role'          => 'dokter',
            ],
            [
                'nama_tindakan' => 'Nebulizer',
                'deskripsi'     => 'Nebulizer untuk masalah pernapasan',
                'tarif'         => 30000,
                'role'          => 'dokter',
            ],
        ];

        // ==========================
        // TINDAKAN UNTUK ROLE BIDAN
        // ==========================
        $tindakanBidan = [
            [
                'nama_tindakan' => 'Pemeriksaan Kehamilan',
                'deskripsi'     => 'Cek kondisi kehamilan oleh bidan',
                'tarif'         => 35000,
                'role'          => 'bidan',
            ],
            [
                'nama_tindakan' => 'USG Kehamilan',
                'deskripsi'     => 'USG 2D untuk pemeriksaan kandungan',
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
