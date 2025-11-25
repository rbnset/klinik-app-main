<?php

namespace App\Filament\Resources\Pemeriksaans\Tables;

use Filament\Actions\BulkActionGroup;
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
            ->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();
                $role = $user->role?->name ?? null;

                // =====================================================
                // 0. Pastikan Tenaga Medis HANYA user dengan role
                //    'dokter' atau 'bidan' (admin tidak pernah ikut)
                // =====================================================
                $query->whereHas('dokter.role', function ($q) {
                    $q->whereIn('name', ['dokter', 'bidan']);
                });

                // =====================================================
                // 1. Filter data sesuai role login
                // =====================================================
                if ($role === 'dokter') {
                    // Dokter → hanya pemeriksaan dari Poli Umum
                    $query->whereHas(
                        'pendaftaran',
                        fn($q) =>
                        $q->where('poli_tujuan', 'Poli Umum')
                    );
                } elseif ($role === 'bidan') {
                    // Bidan → hanya pemeriksaan dari Poli Kandungan
                    $query->whereHas(
                        'pendaftaran',
                        fn($q) =>
                        $q->where('poli_tujuan', 'Poli Kandungan')
                    );
                } elseif ($role === 'pasien') {
                    // Pasien → hanya pemeriksaannya sendiri
                    $query->where('pasien_id', optional($user->pasien)->id);
                }
                // Role lain (admin/petugas) → tetap bisa lihat semua
                // tapi Tenaga Medisnya sudah dibatasi di whereHas di atas.
            })
            ->columns([
                TextColumn::make('pendaftaran.id')
                    ->badge()
                    ->label('Pendaftaran')
                    ->sortable(),

                TextColumn::make('pasien.nama_pasien')
                    ->label('Pasien')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('dokter.name')
                    ->label('Tenaga Medis')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tanggal_periksa')
                    ->label('Tanggal Periksa')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'proses',
                        'success' => 'selesai',
                        'primary' => 'dirujuk',
                    ])
                    ->sortable(),

                TextColumn::make('keluhan_utama')
                    ->label('Keluhan Utama')
                    ->limit(40)
                    ->tooltip(fn($record) => $record->keluhan_utama),

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
            ->recordActions([
                EditAction::make()
                    ->visible(
                        fn() =>
                        in_array(Auth::user()->role?->name, ['dokter', 'bidan'])
                    ),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // bisa diisi aksi aman kalau nanti perlu
                ]),
            ]);
    }
}
