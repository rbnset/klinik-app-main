<?php

namespace App\Filament\Resources\Diagnosas\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\{Select, TextInput, Textarea};
use App\Models\Pemeriksaan;
use Illuminate\Support\Facades\Auth;

class DiagnosaForm
{
    public static function configure(Schema $schema): Schema
    {
        $user = Auth::user();
        $role = $user->role?->name ?? null;

        return $schema->components([

            // 🔹 PILIH PEMERIKSAAN SESUAI ROLE LOGIN
            Select::make('pemeriksaan_id')
                ->label('Pemeriksaan')
                ->options(function () use ($role) {

                    $query = Pemeriksaan::with(['pendaftaran', 'pasien']);

                    // Filter berdasarkan poli tujuan dari pendaftaran
                    if ($role === 'dokter') {
                        $query->whereHas('pendaftaran', function ($q) {
                            $q->where('poli_tujuan', 'Poli Umum');
                        });
                    } elseif ($role === 'bidan') {
                        $query->whereHas('pendaftaran', function ($q) {
                            $q->where('poli_tujuan', 'Poli Kandungan');
                        });
                    }

                    return $query->get()->mapWithKeys(function ($p) {
                        $nama_pasien = $p->pasien->nama_pasien ?? 'Pasien Tidak Ditemukan';
                        return [
                            $p->id => "Periksa ID: {$p->id} | Pasien: {$nama_pasien}"
                        ];
                    });
                })
                ->searchable()
                ->preload()
                ->required(),

            // 🔹 NAMA DIAGNOSA
            TextInput::make('nama_diagnosa')
                ->label('Nama Diagnosa')
                ->maxLength(100)
                ->required(),

            // 🔹 JENIS DIAGNOSA
            Select::make('jenis_diagnosa')
                ->label('Jenis Diagnosa')
                ->options([
                    'Utama' => 'Utama',
                    'Sekunder' => 'Sekunder',
                    'Komplikasi' => 'Komplikasi',
                ])
                ->default('Utama')
                ->required(),

            // 🔹 DESKRIPSI
            Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->columnSpanFull(),
        ]);
    }
}
