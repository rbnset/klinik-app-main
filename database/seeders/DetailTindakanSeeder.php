<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RekamMedisDetail;
use App\Models\Tindakan;
use App\Models\DetailTindakan;

class DetailTindakanSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada data rekam medis detail
        if (RekamMedisDetail::count() === 0) {
            $this->command->warn("RekamMedisDetail kosong. Jalankan seeder RekamMedisDetail terlebih dahulu.");
            return;
        }

        foreach (RekamMedisDetail::with('rekamMedis.pendaftaran')->where('tipe', 'tindakan')->get() as $rmDetail) {

            $poli = $rmDetail->rekamMedis->pendaftaran->poli_tujuan ?? null;

            if (!$poli) {
                $this->command->warn("RekamMedisDetail ID {$rmDetail->id} tidak memiliki pendaftaran/poli_tujuan.");
                continue;
            }

            // Tentukan role yang sesuai
            $role = match ($poli) {
                'Poli Umum'      => 'dokter',
                'Poli Kandungan' => 'bidan',
                default          => null,
            };

            if (!$role) continue;

            // Ambil semua tindakan sesuai role
            $tindakans = Tindakan::where('role', $role)->get();

            foreach ($tindakans as $tindakan) {
                DetailTindakan::create([
                    'rekam_medis_detail_id' => $rmDetail->id,
                    'tindakan_id'           => $tindakan->id,
                    'qty'                   => 1,
                    'tarif'                 => $tindakan->tarif,
                    'subtotal'              => $tindakan->tarif * 1,
                ]);
            }
        }

        $this->command->info("Seeder DetailTindakan berhasil dijalankan.");
    }
}
