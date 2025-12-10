<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;

class TotalBidanController extends Controller
{
    public function index()
    {
        // Ambil semua pendaftaran dengan poli kandungan dan tenaga medis Bidan
        $pendaftarans = Pendaftaran::where('poli_tujuan', 'Poli Kandungan')
                                    ->where('tenaga_medis_tujuan', 'Bidan')
                                    ->with('user') // eager load relasi user
                                    ->get();

        // Ambil nama bidan unik dari relasi user
        $bidans = $pendaftarans->map(fn($p) => $p->user->name ?? 'Bidan Susi',)
                               ->unique()
                               ->values(); // reset index

        return view('total-bidan', compact('bidans'));
    }
}
