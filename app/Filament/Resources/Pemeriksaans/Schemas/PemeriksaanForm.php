<?php

namespace App\Filament\Resources\Pemeriksaans\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\{Grid, Select, DateTimePicker, Textarea, TextInput};
use App\Models\Pendaftaran;
use Filament\Schemas\Components\Grid as ComponentsGrid;

class PemeriksaanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            ComponentsGrid::make(3)->schema([
                Select::make('pendaftaran_id')
                    ->relationship('pendaftaran', 'nomor_antrian')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('pasien_id', optional(Pendaftaran::find($state))->pasien_id);
                    }),

                Select::make('pasien_id')
                    ->relationship('pasien', 'nama_pasien')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('dokter_id')
                    ->relationship('dokter', 'name')
                    ->searchable()
                    ->preload()
                    ->default(fn() => auth()->id())
                    ->required(),
            ]),

            ComponentsGrid::make(2)->schema([
                DateTimePicker::make('tanggal_periksa')
                    ->native(false)
                    ->default(now())
                    ->required(),
                Select::make('status')
                    ->options([
                        'proses'  => 'Proses',
                        'selesai' => 'Selesai',
                        'dirujuk' => 'Dirujuk',
                    ])
                    ->default('proses')
                    ->required(),
            ]),

            Textarea::make('keluhan_utama')
                ->label('Keluhan Utama')
                ->required()
                ->columnSpanFull(),

            ComponentsGrid::make(6)->schema([
                TextInput::make('tinggi_badan')->numeric()->label('TB (cm)'),
                TextInput::make('berat_badan')->numeric()->label('BB (kg)'),
                TextInput::make('tekanan_darah')->label('TD (mmHg)'),
                TextInput::make('suhu')->numeric()->label('Suhu (°C)'),
                TextInput::make('nadi')->numeric()->label('Nadi'),
                TextInput::make('respirasi')->numeric()->label('RR'),
            ]),
        ]);
    }
}
