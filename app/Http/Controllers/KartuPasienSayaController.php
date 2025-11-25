<?php

namespace App\Http\Controllers;

use App\Models\Pasien;

class KartuPasienSayaController extends Controller
{
    // Untuk petugas / admin: lihat kartu pasien mana saja
    public function show(Pasien $pasien)
    {
        $pasien->load([
            'rekamMedis',
            'rekamMedis.diagnosa',
            'rekamMedis.pemeriksaan',
        ]);

        return view('kartu-pasien-saya', compact('pasien'));
    }

    // Untuk pasien login: lihat kartunya sendiri
    public function pasienSaya()
    {
        $user = auth()->user();

        if (! $user || ! $user->pasien) {
            abort(403, 'Data pasien tidak ditemukan untuk pengguna ini.');
        }

        $pasien = $user->pasien()
            ->with([
                'rekamMedis',
                'rekamMedis.diagnosa',
                'rekamMedis.pemeriksaan',
            ])
            ->first();

        return view('kartu-pasien-saya', compact('pasien'));
    }
}
