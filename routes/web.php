<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KartuPasienSayaController;

Route::get('/', function () {
    return view('welcome');
});

// ---------------------------
// ROUTE KARTU PASIEN
// ---------------------------
Route::middleware(['auth'])->group(function () {
    // pasien lihat kartunya sendiri
    Route::get('/kartu-pasien-saya', [KartuPasienSayaController::class, 'pasienSaya'])
        ->name('kartu-pasien.saya');

    // petugas/admin lihat kartu pasien tertentu
    Route::get('/kartu-pasien/{pasien}', [KartuPasienSayaController::class, 'show'])
        ->name('kartu-pasien.show');
});
