<?php

namespace App\Filament\Widgets;

use App\Models\Pasien;
use Filament\Tables;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class PasienTableWidget extends TableWidget
{
    protected static ?string $heading = 'Data Pasien';

    protected function getTableQuery(): Builder
    {
        return Pasien::query();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('nama_pasien')
                ->label('Nama Pasien')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('nik')
                ->label('NIK')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('no_telp')
                ->label('No Telp')
                ->sortable(),

            Tables\Columns\TextColumn::make('jenis_kelamin')
                ->label('JK'),
        ];
    }
}
