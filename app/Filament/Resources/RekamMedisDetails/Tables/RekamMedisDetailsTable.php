<?php

namespace App\Filament\Resources\RekamMedisDetails\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RekamMedisDetailsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('rekam_medis_id')
                    ->label('RM ID')
                    ->sortable(),

                TextColumn::make('rekamMedis.tanggal')
                    ->label('Tanggal RM')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                /** ===========================
                 *  FIX BAGIAN 'tipe'
                 * =========================== */
                BadgeColumn::make('tipe')
                    ->label('Tipe')
                    ->colors([
                        'warning' => 'obat',
                        'success' => 'suntik',
                        'info'    => 'infus',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'obat'   => 'Obat',
                        'suntik' => 'Suntik',
                        'infus'  => 'Infus',
                        default  => ucfirst($state),
                    })
                    ->sortable(),

                TextColumn::make('deskripsi')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->deskripsi),

                TextColumn::make('qty')
                    ->alignRight()
                    ->sortable(),

                TextColumn::make('satuan')
                    ->label('Sat.')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('harga_satuan')
                    ->label('Harga')
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
            ->filters([])
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
