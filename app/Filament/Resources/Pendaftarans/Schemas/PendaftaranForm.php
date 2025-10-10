<?php

namespace App\Filament\Resources\Pendaftarans\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\{Grid, Select, DateTimePicker, TextInput, Textarea};
use Filament\Schemas\Components\Grid as ComponentsGrid;

class PendaftaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            ComponentsGrid::make(2)->schema([
                Select::make('pasien_id')
                    ->label('Nama Pasien')
                    ->relationship('pasien', 'nama_pasien') // ✅ sesuai dengan relasi di model & kolom di tabel pasien
                    ->searchable()
                    ->preload() //
                    ->required(),

                Select::make('user_id')
                    ->label('Nama Petugas')
                    ->options(function () {
                        // Jika role = petugas, hanya opsi dirinya
                        if (auth()->user()?->role?->name === 'petugas') {
                            return [auth()->id() => auth()->user()?->name];
                        }

                        // Selain petugas (admin, dsb) bisa pilih petugas mana saja
                        return \App\Models\User::whereHas('role', fn($q) => $q->where('name', 'petugas'))
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->default(fn() => auth()->user()?->role?->name === 'petugas' ? auth()->id() : null)
                    ->disabled(fn() => auth()->user()?->role?->name === 'petugas') // dikunci kalau petugas
                    ->dehydrated(true)   // tetap disimpan ke DB meski disabled
                    ->required()
                    ->rule('exists:users,id')
                    // optional: matikan search saat petugas (biar UI simple)
                    ->searchable(fn() => auth()->user()?->role?->name !== 'petugas')
                    ->preload(),


                DateTimePicker::make('tanggal_daftar')
                    ->label('Tanggal Daftar')
                    ->native(false)
                    ->default(now())
                    ->required(),

                TextInput::make('nomor_antrian')
                    ->label('Nomor Antrian')
                    ->required(),

                Select::make('poli_tujuan')
                    ->label('Poli Tujuan')
                    ->options([
                        'Poli Umum' => 'Poli Umum',
                        'Poli Kandungan' => 'Poli Kandungan',
                    ])
                    ->required(),

                Select::make('tenaga_medis_tujuan')
                    ->label('Tenaga Medis')
                    ->options([
                        'Dokter' => 'Dokter',
                        'Bidan' => 'Bidan',
                    ])
                    ->required(),

                Select::make('jenis_pelayanan')
                    ->label('Jenis Pelayanan')
                    ->options([
                        'umum' => 'Umum',
                        'bpjs' => 'BPJS',
                        'asuransi' => 'Asuransi',
                    ])
                    ->required(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'diproses' => 'Diproses',
                        'selesai' => 'Selesai',
                        'batal' => 'Batal',
                    ])
                    ->default('menunggu')
                    ->required(),
            ]),

            Textarea::make('keluhan')
                ->label('Keluhan')
                ->columnSpanFull(),

            Textarea::make('catatan')
                ->label('Catatan')
                ->columnSpanFull(),
        ]);
    }
}
