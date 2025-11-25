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
    // Pasien login → melihat kartu dirinya sendiri
    Route::get('/kartu-pasien/saya', [KartuPasienSayaController::class, 'pasienSaya'])
        ->name('kartu.pasien.saya');

    Route::get('/kartu-pasien/{pasien}', [KartuPasienSayaController::class, 'show'])
     ->middleware('role:petugas')
     ->name('kartu.pasien.show');


});
