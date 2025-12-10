<?php

namespace App\Filament\Resources\Tindakans\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\{Grid, TextInput, Textarea};
use Filament\Schemas\Components\Grid as ComponentsGrid;

class TindakanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            ComponentsGrid::make(2)->schema([
                TextInput::make('nama_tindakan')->required()->maxLength(150),
            ]),
            Textarea::make('deskripsi')->columnSpanFull(),
        ]);
    }
}
