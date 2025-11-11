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

                // 🔹 Nama Pasien
                Select::make('pasien_id')
                    ->label('Nama Pasien')
                    ->relationship('pasien', 'nama_pasien')
                    ->searchable()
                    ->preload()
                    ->required(),

                // 🔹 Nama Petugas (otomatis terisi kalau login sebagai petugas)
                Select::make('user_id')
                    ->label('Nama Petugas')
                    ->options(function () {
                        if (auth()->user()?->role?->name === 'petugas') {
                            return [auth()->id() => auth()->user()?->name];
                        }

                        return \App\Models\User::whereHas('role', fn($q) => $q->where('name', 'petugas'))
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->default(fn() => auth()->user()?->role?->name === 'petugas' ? auth()->id() : null)
                    ->afterStateHydrated(function ($set) {
                        if (auth()->user()?->role?->name === 'petugas') {
                            $set('user_id', auth()->id()); // tampil langsung di form
                        }
                    })
                    ->disabled(fn() => auth()->user()?->role?->name === 'petugas') // tidak bisa diubah oleh petugas
                    ->dehydrated(true)
                    ->required()
                    ->rule('exists:users,id')
                    ->searchable(fn() => auth()->user()?->role?->name !== 'petugas')
                    ->preload(),

                // 🔹 Jadwal
                Select::make('jadwal_id')
                    ->label('Jadwal')
                    ->options(function () {
                        $user = auth()->user();

                        $q = \App\Models\Jadwal::query()
                            ->with('user')
                            ->orderBy('hari')
                            ->orderBy('jam_mulai');

                        if ($user?->role?->name === 'dokter') {
                            $q->where('user_id', $user->id);
                        }

                        return $q->get()->mapWithKeys(function ($j) {
                            $nama    = $j->user->name ?? '-';
                            $hari    = ucfirst($j->hari);
                            $mulai   = (string) $j->jam_mulai;
                            $selesai = (string) $j->jam_selesai;
                            $ket     = $j->keterangan ? " | {$j->keterangan}" : '';
                            return [$j->id => "{$nama} | {$hari} {$mulai}-{$selesai}{$ket}"];
                        })->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->rule('exists:jadwals,id'),

                // 🔹 Poli Tujuan dengan logika otomatis ubah tenaga medis
                Select::make('poli_tujuan')
                    ->label('Poli Tujuan')
                    ->options([
                        'Poli Umum' => 'Poli Umum',
                        'Poli Kandungan' => 'Poli Kandungan',
                    ])
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state) {
                        if ($state === 'Poli Kandungan') {
                            $set('tenaga_medis_tujuan', 'Bidan');
                        } else {
                            $set('tenaga_medis_tujuan', 'Dokter');
                        }
                    })
                    ->required(),

                // 🔹 Tenaga Medis Tujuan (otomatis terisi dari pilihan Poli)
                Select::make('tenaga_medis_tujuan')
                    ->label('Tenaga Medis')
                    ->options([
                        'Dokter' => 'Dokter',
                        'Bidan' => 'Bidan',
                    ])
                    ->disabled()
                    ->dehydrated(true)
                    ->required(),

                // 🔹 Jenis Pelayanan
                Select::make('jenis_pelayanan')
                    ->label('Jenis Pelayanan')
                    ->options([
                        'umum' => 'Umum',
                        'bpjs' => 'BPJS',
                        'asuransi' => 'Asuransi',
                    ])
                    ->required(),

                // 🔹 Status
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

            // 🔹 Keluhan
            Textarea::make('keluhan')
                ->label('Keluhan')
                ->columnSpanFull(),

            // 🔹 Catatan
            Textarea::make('catatan')
                ->label('Catatan')
                ->columnSpanFull(),
        ]);
    }
}
