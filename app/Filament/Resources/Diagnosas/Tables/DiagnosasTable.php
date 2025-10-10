<?php

namespace App\Filament\Resources\Diagnosas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DiagnosasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
    TextColumn::make('pemeriksaan.id')
        ->label('Pemeriksaan')
        ->sortable()
        ->searchable(),

    TextColumn::make('nama_diagnosa')
        ->label('Nama Diagnosa')
        ->searchable()
        ->sortable(),

    TextColumn::make('jenis_diagnosa')
        ->label('Jenis Diagnosa')
        ->sortable(),

    TextColumn::make('deskripsi')
        ->label('Deskripsi')
        ->limit(50), // biar tidak terlalu panjang di tabel

    TextColumn::make('updated_at')
        ->label('Diubah')
        ->dateTime('d/m/Y H:i')
        ->sortable(),
])

            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
