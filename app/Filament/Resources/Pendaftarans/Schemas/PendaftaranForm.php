<?php

namespace App\Filament\Resources\Pendaftarans\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\{Grid, Select, Textarea};
use Filament\Schemas\Components\Grid as ComponentsGrid;
use Filament\Forms;

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
                    ->default(fn() => auth()->user()?->role?->name === 'pasien'
                        ? optional(auth()->user()->pasien)->id
                        : null)
                    ->disabled(fn() => auth()->user()?->role?->name === 'pasien')
                    ->dehydrated(true)
                    ->searchable(fn() => auth()->user()?->role?->name !== 'pasien')
                    ->preload()
                    ->required(),

                // 🔹 User ID (Hidden, otomatis untuk semua role saat create)
                Forms\Components\Hidden::make('user_id')
                    ->default(fn() => auth()->id())
                    ->dehydrated(true)
                    ->visible(fn($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord),

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
                        return $q->get()->mapWithKeys(fn($j) => [
                            $j->id => ($j->user->name ?? '-') . " | " . ucfirst($j->hari) . " {$j->jam_mulai}-{$j->jam_selesai}" . ($j->keterangan ? " | {$j->keterangan}" : '')
                        ])->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->rule('exists:jadwals,id'),

                // 🔹 Poli Tujuan
                Select::make('poli_tujuan')
                    ->label('Poli Tujuan')
                    ->options([
                        'Poli Umum' => 'Poli Umum',
                        'Poli Kandungan' => 'Poli Kandungan',
                    ])
                    ->reactive()
                    ->afterStateUpdated(fn(callable $set, $state) => $set('tenaga_medis_tujuan', $state === 'Poli Kandungan' ? 'Bidan' : 'Dokter'))
                    ->required(),

                // 🔹 Tenaga Medis
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
                    ->visible(fn() => auth()->user()?->role?->name !== 'pasien')
                    ->dehydrated(true)
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
