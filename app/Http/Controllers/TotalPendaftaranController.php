<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;

class TotalPendaftaranController extends Controller
{
    public function index()
    {
        $pendaftarans = Pendaftaran::all(); // ambil semua data
        return view('total-pendaftaran', compact('pendaftarans'));
    }
}
