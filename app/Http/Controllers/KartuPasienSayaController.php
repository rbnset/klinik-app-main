<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // <- ini yang kurang

class KartuPasienSayaController extends Controller
{
    // Untuk petugas (lihat kartu pasien mana saja)
    public function show(Pasien $pasien)
    {
        return view('kartu-pasien-saya', compact('pasien'));
    }

    // Untuk pasien login (lihat kartunya sendiri)
    public function pasienSaya()
    {
        $pasien = auth()->user()->pasien;

        if (!$pasien) {
            abort(403, 'Data pasien tidak ditemukan untuk pengguna ini.');
        }

        return view('kartu-pasien-saya', compact('pasien'));
    }
}
