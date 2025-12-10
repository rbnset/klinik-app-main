<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;

class TotalPasienBidanController extends Controller
{
    public function index()
    {
        // Ambil pendaftaran dengan poli kandungan / tenaga medis Bidan
        $pendaftarans = Pendaftaran::where('poli_tujuan', 'Poli Kandungan')
                                    ->where('tenaga_medis_tujuan', 'Bidan')
                                    ->with('pasien') // ambil relasi pasien
                                    ->get();

        return view('total-pasien-bidan', compact('pendaftarans'));
    }
}
