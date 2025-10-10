<?php

namespace App\Filament\Resources\DetailTindakans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DetailTindakansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('detail.rekamMedis.id')
                    ->label('RM')
                    ->sortable(),

                TextColumn::make('detail.id')
                    ->label('Detail ID')
                    ->sortable(),

                TextColumn::make('tindakan.nama')
                    ->label('Tindakan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('qty')
                    ->label('Qty')
                    ->alignRight()
                    ->sortable(),

                TextColumn::make('tarif')
                    ->label('Tarif')
                    ->money('IDR', true)
                    ->alignRight()
                    ->sortable(),

                TextColumn::make('subtotal')
                    ->money('IDR', true)
                    ->alignRight()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->label('Dibuat')
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
