<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;

class TotalPasienDokterController extends Controller
{
    public function index()
    {
        // Ambil pendaftaran dengan poli umum / tenaga medis Dokter
        $pendaftarans = Pendaftaran::where('poli_tujuan', 'Poli Umum')
                                    ->where('tenaga_medis_tujuan', 'Dokter')
                                    ->with('pasien') // ambil relasi pasien
                                    ->get();

        return view('total-pasien-dokter', compact('pendaftarans'));
    }
}
