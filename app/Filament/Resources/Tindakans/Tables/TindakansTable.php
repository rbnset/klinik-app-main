<?php

namespace App\Filament\Resources\Tindakans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TindakansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $user = auth()->user();

                if ($user->hasRole('dokter')) {
                    return $query->where('role', 'dokter');
                }

                if ($user->hasRole('bidan')) {
                    return $query->where('role', 'bidan');
                }

                return $query; // admin atau role lainnya → lihat semua
            })
            ->columns([
                TextColumn::make('nama_tindakan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('deskripsi')
                    ->searchable()
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
