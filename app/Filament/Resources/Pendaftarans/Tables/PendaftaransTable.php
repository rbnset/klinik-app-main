<?php

namespace App\Filament\Resources\Pendaftarans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PendaftaransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
    TextColumn::make('nomor_antrian')
        ->label('Antrian')
        ->badge()
        ->sortable()
        ->searchable(),

    TextColumn::make('pasien.nama_pasien')
    ->label('Nama Pasien')
    ->searchable(),

TextColumn::make('user.name')
    ->label('Nama Petugas')

    ->searchable(),


    TextColumn::make('tanggal_daftar')
        ->label('Tanggal Daftar')
        ->dateTime('d/m/Y H:i')
        ->sortable(),

    TextColumn::make('poli_tujuan')
        ->label('Poli Tujuan')
        ->sortable()
        ->badge(),

    TextColumn::make('tenaga_medis_tujuan')
        ->label('Tenaga Medis')
        ->sortable()
        ->badge(),

    TextColumn::make('jenis_pelayanan')
        ->label('Jenis Pelayanan')
        ->sortable()
        ->badge(),

    TextColumn::make('keluhan')
        ->label('Keluhan')
        ->limit(30)
        ->toggleable()
        ->wrap(),

    BadgeColumn::make('status')
        ->label('Status')
        ->colors([
            'primary' => 'menunggu',
            'warning' => 'diproses',
            'success' => 'selesai',
            'danger'  => 'batal',
        ])
        ->sortable(),
])

->filters([
    SelectFilter::make('poli_tujuan')
        ->label('Poli Tujuan')
        ->options([
            'Poli Umum' => 'Poli Umum',
            'Poli Kandungan' => 'Poli Kandungan',
        ]),

    SelectFilter::make('tenaga_medis_tujuan')
        ->label('Tenaga Medis')
        ->options([
            'Dokter' => 'Dokter',
            'Bidan' => 'Bidan',
        ]),

    SelectFilter::make('jenis_pelayanan')
        ->label('Jenis Pelayanan')
        ->options([
            'umum' => 'Umum',
            'bpjs' => 'BPJS',
            'asuransi' => 'Asuransi',
        ]),

    SelectFilter::make('status')
        ->label('Status')
        ->options([
            'menunggu' => 'Menunggu',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'batal' => 'Batal',
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
