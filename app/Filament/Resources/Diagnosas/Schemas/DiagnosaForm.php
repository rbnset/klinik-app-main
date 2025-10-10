<?php

namespace App\Filament\Resources\Diagnosas\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\{Select, TextInput, Textarea};

class DiagnosaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
    Select::make('pemeriksaan_id')
        ->label('Pemeriksaan')
        ->relationship('pemeriksaan', 'id') 
        ->searchable()
        ->preload()
        ->required(),

    TextInput::make('nama_diagnosa')
        ->label('Nama Diagnosa')
        ->maxLength(100)
        ->required(),

    Select::make('jenis_diagnosa')
        ->label('Jenis Diagnosa')
        ->options([
            'Utama' => 'Utama',
            'Sekunder' => 'Sekunder',
            'Komplikasi' => 'Komplikasi',
        ])
        ->default('Utama')
        ->required(),

    Textarea::make('deskripsi')
        ->label('Deskripsi')
        ->columnSpanFull(),
]);

    }
}
