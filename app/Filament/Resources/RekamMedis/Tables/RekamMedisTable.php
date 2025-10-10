<?php

namespace App\Filament\Resources\RekamMedis\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RekamMedisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
    TextColumn::make('id')
        ->label('RM')
        ->sortable(),

    TextColumn::make('pasien.nama_pasien')
        ->label('Pasien')
        ->searchable(),

    TextColumn::make('pemeriksaan.tanggal_periksa')
        ->label('Tgl Pemeriksaan')
        ->dateTime('d/m/Y H:i')
        ->sortable(),

    TextColumn::make('diagnosa.nama_diagnosa')
        ->label('Diagnosa')
        ->searchable(),

    TextColumn::make('tanggal')
        ->label('Tanggal Rekam Medis')
        ->dateTime('d/m/Y H:i')
        ->sortable(),

    TextColumn::make('riwayat_alergi')
        ->label('Riwayat Alergi')
        ->limit(30) // batasi tampilan agar tabel tetap rapi
        ->toggleable(isToggledHiddenByDefault: true),

    TextColumn::make('riwayat_penyakit')
        ->label('Riwayat Penyakit')
        ->limit(30)
        ->toggleable(isToggledHiddenByDefault: true),

    TextColumn::make('rencana_terapi')
        ->label('Rencana Terapi')
        ->limit(30)
        ->toggleable(isToggledHiddenByDefault: true),

    TextColumn::make('catatan')
        ->label('Catatan')
        ->limit(30)
        ->toggleable(isToggledHiddenByDefault: true),
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
