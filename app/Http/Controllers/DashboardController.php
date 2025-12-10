<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pendaftaran;

class DashboardController extends Controller
{
    public function totalPasienDokter()
    {
        $total = Pendaftaran::where('tenaga_medis_tujuan', 'dokter')->count();
        return view('dashboard.total', [
            'title' => 'Total Pasien Dokter',
            'total' => $total
        ]);
    }

    public function totalPasienBidan()
    {
        $total = Pendaftaran::where('tenaga_medis_tujuan', 'bidan')->count();
        return view('dashboard.total', [
            'title' => 'Total Pasien Bidan',
            'total' => $total
        ]);
    }

    public function totalDokter()
    {
        $total = User::whereHas('role', fn ($q) => $q->where('name', 'dokter'))->count();
        return view('dashboard.total', [
            'title' => 'Total Dokter',
            'total' => $total
        ]);
    }

    public function totalBidan()
    {
        $total = User::whereHas('role', fn ($q) => $q->where('name', 'bidan'))->count();
        return view('dashboard.total', [
            'title' => 'Total Bidan',
            'total' => $total
        ]);
    }

    public function totalPendaftaran()
    {
        $total = Pendaftaran::count();
        return view('dashboard.total', [
            'title' => 'Total Pendaftaran',
            'total' => $total
        ]);
    }
}
