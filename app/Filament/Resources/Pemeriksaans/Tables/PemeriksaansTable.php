<?php

namespace App\Filament\Resources\Pemeriksaans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PemeriksaansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 🔹 Nomor antrian dari tabel pendaftaran
                TextColumn::make('pendaftaran.nomor_antrian')
                    ->badge()
                    ->label('Antrian')
                    ->sortable(),

                // 🔹 Nama pasien
                TextColumn::make('pasien.nama_pasien') // pastikan pakai kolom 'nama_pasien'
                    ->label('Pasien')
                    ->searchable()
                    ->sortable(),

                // 🔹 Dokter pemeriksa
                TextColumn::make('dokter.name')
                    ->label('Dokter')
                    ->searchable()
                    ->sortable(),

                // 🔹 Tanggal periksa
                TextColumn::make('tanggal_periksa')
                    ->label('Tanggal Periksa')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                // 🔹 Status pemeriksaan
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'proses',
                        'success' => 'selesai',
                        'primary' => 'dirujuk',
                    ])
                    ->sortable(),

                // 🔹 Keluhan utama
                TextColumn::make('keluhan_utama')
                    ->label('Keluhan Utama')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->keluhan_utama),

                // 🔹 Data pemeriksaan fisik
                TextColumn::make('tinggi_badan')
                    ->label('TB (cm)')
                    ->sortable(),

                TextColumn::make('berat_badan')
                    ->label('BB (kg)')
                    ->sortable(),

                TextColumn::make('tekanan_darah')
                    ->label('TD (mmHg)')
                    ->sortable(),

                TextColumn::make('suhu')
                    ->label('Suhu (°C)')
                    ->sortable(),

                TextColumn::make('nadi')
                    ->label('Nadi (x/mnt)')
                    ->sortable(),

                TextColumn::make('respirasi')
                    ->label('RR (x/mnt)')
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
