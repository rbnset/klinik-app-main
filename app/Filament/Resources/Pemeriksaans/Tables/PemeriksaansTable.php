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
            // 🔹 Filter data sesuai role dan poli tujuan
            ->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();
                $role = $user->role?->name ?? null;

                if ($role === 'dokter') {
                    // Hanya tampilkan pemeriksaan yang berasal dari pendaftaran Poli Umum
                    $query->whereHas('pendaftaran', fn($q) => $q->where('poli_tujuan', 'Poli Umum'));
                } elseif ($role === 'bidan') {
                    // Hanya tampilkan pemeriksaan yang berasal dari pendaftaran Poli Kandungan
                    $query->whereHas('pendaftaran', fn($q) => $q->where('poli_tujuan', 'Poli Kandungan'));
                } elseif ($role === 'pasien') {
                    // Pasien hanya lihat pemeriksaannya sendiri
                    $query->where('pasien_id', optional($user->pasien)->id);
                } else {
                    // admin / petugas / role lain: lihat semua (read-only for admin is enforced elsewhere)
                    // tidak menambah kondisi => menampilkan semua pemeriksaan
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
                // Edit hanya untuk dokter & bidan
                EditAction::make()
                    ->visible(fn() => in_array(Auth::user()->role?->name, ['dokter', 'bidan'])),
            ])
            ->toolbarActions([
                // Hanya tampilkan toolbar actions yang aman:
                // - Tidak ada CreateAction di sini (create dikontrol oleh Resource::canCreate)
                // - Tidak ada DeleteBulkAction agar tidak ada penghapusan massal
                BulkActionGroup::make([
                    // Kosongkan atau isi dengan aksi yang aman jika perlu
                ]),
            ]);
    }
}
