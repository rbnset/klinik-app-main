<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;

class TotalDokterController extends Controller
{
    public function index()
    {
        $pendaftarans = Pendaftaran::where('poli_tujuan', 'Poli Umum')
                            ->where('tenaga_medis_tujuan', 'Dokter')
                            ->whereNotNull('user_id')   // tambahkan ini
                            ->with('user')
                            ->get();

$dokter = $pendaftarans->pluck('user')->filter()->unique('id')->values();


        return view('total-dokter', compact('dokter'));
    }
}
