<?php

namespace App\Filament\Resources\Jadwals\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class JadwalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Tenaga Medis')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('hari')
                    ->label('Hari')
                    ->formatStateUsing(fn($state) => [
                        'senin'  => 'Senin',
                        'selasa' => 'Selasa',
                        'rabu'   => 'Rabu',
                        'kamis'  => 'Kamis',
                        'jumat'  => 'Jumat',
                        'sabtu'  => 'Sabtu',
                        'minggu' => 'Minggu',
                    ][$state] ?? $state)
                    ->sortable(),

                TextColumn::make('jam_mulai')
                    ->label('Mulai')
                    ->sortable(),

                TextColumn::make('jam_selesai')
                    ->label('Selesai')
                    ->sortable(),

                // === kolom baru: Keterangan (enum) ===
                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Ada'       => 'success',
                        'Tidak Ada' => 'danger',
                        default     => 'gray',
                    })
                    ->sortable(),

                // === kolom baru: Sesi (nullable) ===
                TextColumn::make('sesi')
                    ->label('Sesi')
                    ->placeholder('-')
                    ->toggleable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->label('Dibuat')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('hari')->options([
                    'senin'  => 'Senin',
                    'selasa' => 'Selasa',
                    'rabu'   => 'Rabu',
                    'kamis'  => 'Kamis',
                    'jumat'  => 'Jumat',
                    'sabtu'  => 'Sabtu',
                    'minggu' => 'Minggu',
                ]),
                // filter baru: keterangan
                SelectFilter::make('keterangan')->options([
                    'Ada'       => 'Ada',
                    'Tidak Ada' => 'Tidak Ada',
                ]),
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
