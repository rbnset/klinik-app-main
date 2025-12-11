<?php

namespace App\Filament\Widgets;

use App\Models\Pendaftaran;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LaporanKunjunganTableWidget extends TableWidget
{
    protected static ?string $heading = 'Laporan Kunjungan Pasien';

    protected function getTableQuery(): Builder
    {
        return Pendaftaran::with(['pasien', 'user', 'jadwal'])->orderBy('tanggal_kunjungan', 'desc');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('tanggal_kunjungan')
                ->label('Tanggal Kunjungan')
                ->date()
                ->sortable(),

            Tables\Columns\TextColumn::make('pasien.nama_pasien')
                ->label('Nama Pasien')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('poli_tujuan')
                ->label('Poli Tujuan')
                ->badge()
                ->sortable(),

            Tables\Columns\TextColumn::make('tenaga_medis_tujuan')
                ->label('Tenaga Medis')
                ->badge()
                ->sortable(),

            Tables\Columns\TextColumn::make('jenis_pelayanan')
                ->label('Jenis Pelayanan')
                ->badge()
                ->sortable(),

            Tables\Columns\TextColumn::make('keluhan')
                ->label('Keluhan')
                ->limit(40)
                ->wrap(),

            Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->colors([
                    'menunggu' => 'primary',
                    'diproses' => 'warning',
                    'selesai' => 'success',
                    'batal'   => 'danger',
                ])
                ->sortable(),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            Tables\Filters\SelectFilter::make('poli_tujuan')
                ->options([
                    'Poli Umum' => 'Poli Umum',
                    'Poli Kandungan' => 'Poli Kandungan',
                ]),

            Tables\Filters\SelectFilter::make('tenaga_medis_tujuan')
                ->options([
                    'Dokter' => 'Dokter',
                    'Bidan' => 'Bidan',
                ]),

            Tables\Filters\SelectFilter::make('jenis_pelayanan')
                ->options([
                    'umum' => 'Umum',
                    'bpjs' => 'BPJS',
                    'asuransi' => 'Asuransi',
                ]),

            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'menunggu' => 'Menunggu',
                    'diproses' => 'Diproses',
                    'selesai' => 'Selesai',
                    'batal' => 'Batal',
                ]),
        ];
    }

    protected function getTablePaginationPageOptions(): array
    {
        return [10, 25, 50, 100];
    }
}
