<?php

namespace App\Filament\Resources\Jadwals\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid as ComponentsGrid;

class JadwalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            ComponentsGrid::make(2)->schema([
            Select::make('user_id')
                ->label('Tenaga Medis')
                ->relationship(
                    name: 'user',
                    titleAttribute: 'name',
                    modifyQueryUsing: function ($query) {
                        $query->whereHas('role', function ($q) {
                            $q->whereIn('name', ['dokter', 'bidan']);
                        });
                    }
                )
                ->searchable()
                ->preload()
                ->required(),


                Select::make('hari')
                    ->label('Hari')
                    ->options([
                        'senin'  => 'Senin',
                        'selasa' => 'Selasa',
                        'rabu'   => 'Rabu',
                        'kamis'  => 'Kamis',
                        'jumat'  => 'Jumat',
                        'sabtu'  => 'Sabtu',
                        'minggu' => 'Minggu',
                    ])
                    ->required(),

                TimePicker::make('jam_mulai')
                    ->label('Jam Mulai')
                    ->required(),

                TimePicker::make('jam_selesai')
                    ->label('Jam Selesai')
                    ->required(),

                // sesuai enum di migration: ['Ada', 'Tidak Ada']
                Select::make('keterangan')
                    ->label('Keterangan')
                    ->options([
                        'Ada'       => 'Ada',
                        'Tidak Ada' => 'Tidak Ada',
                    ])
                    ->required(),

                // kolom nullable, panjang maksimal 8
                TextInput::make('sesi')
                    ->label('Sesi')
                    ->maxLength(8)
                    ->nullable(),
            ]),
        ]);
    }
}
