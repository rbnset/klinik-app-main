<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KartuPasienSayaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TotalPendaftaranController;
use App\Http\Controllers\TotalBidanController;
use App\Http\Controllers\TotalDokterController;
use App\Http\Controllers\TotalPasienBidanController;
use App\Http\Controllers\TotalPasienDokterController;
use Illuminate\Support\Facades\Lang;

// LANDING PAGE
Route::get('/', function () {
    return view('landing');
});

// TOTAL DATA
Route::get('/total-pasien-dokter', [TotalPasienDokterController::class, 'index']);
Route::get('/total-pasien-bidan', [TotalPasienBidanController::class, 'index']);
Route::get('/total-dokter', [TotalDokterController::class, 'index']);
Route::get('/total-bidan', [TotalBidanController::class, 'index']);

// TOTAL PENDAFTARAN → tampil tabel saja tanpa menu/admin
Route::get('/total-pendaftaran', [TotalPendaftaranController::class, 'index']);

// KARTU PASIEN
Route::middleware(['auth'])->group(function () {
    Route::get('/kartu-pasien-saya', [KartuPasienSayaController::class, 'pasienSaya'])
        ->name('kartu-pasien.saya');

    Route::get('/kartu-pasien/{pasien}', [KartuPasienSayaController::class, 'show'])
        ->name('kartu-pasien.show');
});

// LOGIN PASIEN
Route::get('/login-pasien', function () {
    return view('auth.login-pasien');
});
