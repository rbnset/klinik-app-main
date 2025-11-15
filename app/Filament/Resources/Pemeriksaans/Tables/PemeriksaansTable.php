<?php

namespace App\Filament\Resources\Pemeriksaans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PemeriksaansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // 🔹 Filter data sesuai role dan poli tujuan
            ->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();
                $role = $user->role?->name ?? null;

                if ($role === 'dokter') {
                    // Hanya tampilkan yang poli_tujuan Poli Umum
                    $query->whereHas('pendaftaran', function ($q) {
                        $q->where('poli_tujuan', 'Poli Umum');
                    });
                } elseif ($role === 'bidan') {
                    // Hanya tampilkan yang poli_tujuan Poli Kandungan
                    $query->whereHas('pendaftaran', function ($q) {
                        $q->where('poli_tujuan', 'Poli Kandungan');
                    });
                } elseif ($role === 'pasien') {
                    // Pasien hanya lihat data miliknya sendiri
                    $query->where('pasien_id', optional($user->pasien)->id);
                } else {
                    // Role lain tidak boleh melihat data
                    $query->whereRaw('0 = 1');
                }
            })
            ->columns([
                TextColumn::make('pendaftaran.id')->badge()->label('Pendaftaran')->sortable(),
                TextColumn::make('pasien.nama_pasien')->label('Pasien')->searchable()->sortable(),
                TextColumn::make('dokter.name')->label('Tenaga Medis')->searchable()->sortable(),
                TextColumn::make('tanggal_periksa')->label('Tanggal Periksa')->dateTime('d/m/Y H:i')->sortable(),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'proses',
                        'success' => 'selesai',
                        'primary' => 'dirujuk',
                    ])
                    ->sortable(),
                TextColumn::make('keluhan_utama')->label('Keluhan Utama')->limit(40)->tooltip(fn($record) => $record->keluhan_utama),
                TextColumn::make('tinggi_badan')->label('TB (cm)')->sortable(),
                TextColumn::make('berat_badan')->label('BB (kg)')->sortable(),
                TextColumn::make('tekanan_darah')->label('TD (mmHg)')->sortable(),
                TextColumn::make('suhu')->label('Suhu (°C)')->sortable(),
                TextColumn::make('nadi')->label('Nadi (x/mnt)')->sortable(),
                TextColumn::make('respirasi')->label('RR (x/mnt)')->sortable(),
            ])
            ->recordActions([
                EditAction::make()->visible(fn() => in_array(Auth::user()->role?->name, ['dokter', 'bidan'])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->visible(fn() => in_array(Auth::user()->role?->name, ['admin', 'petugas'])),
                ]),
            ]);
    }
}
