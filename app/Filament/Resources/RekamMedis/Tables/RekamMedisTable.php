<?php

namespace App\Filament\Resources\RekamMedis\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class RekamMedisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // Filter sesuai role dan eager load relasi
            ->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();
                $role = $user->role?->name ?? null;

                // Eager load relasi penting
                $query->with(['diagnosa', 'pemeriksaan', 'pemeriksaan.pendaftaran', 'pasien']);

                if ($role === 'dokter') {
                    $query->whereHas('pemeriksaan', function ($q) {
                        $q->whereHas('pendaftaran', function ($q2) {
                            $q2->where('poli_tujuan', 'Poli Umum');
                        });
                    });
                } elseif ($role === 'bidan') {
                    $query->whereHas('pemeriksaan', function ($q) {
                        $q->whereHas('pendaftaran', function ($q2) {
                            $q2->where('poli_tujuan', 'Poli Kandungan');
                        });
                    });
                } elseif ($role === 'pasien') {
                    $pasienId = optional($user->pasien)->id;
                    $query->where('pasien_id', $pasienId);
                } else {
                    $query->whereRaw('0 = 1'); // role lain tidak bisa lihat
                }
            })

            ->columns([
                TextColumn::make('id')
                    ->label('RM')
                    ->sortable(),

                TextColumn::make('pasien.nama_pasien')
                    ->label('Pasien')
                    ->searchable(),

                TextColumn::make('pemeriksaan.tanggal_periksa')
                    ->label('Tgl Pemeriksaan')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('diagnosa.nama_diagnosa')
                    ->label('Diagnosa')
                    ->searchable()
                    ->formatStateUsing(fn($state, $record) => $record->diagnosa?->nama_diagnosa ?? '-'),

                TextColumn::make('tanggal')
                    ->label('Tanggal Rekam Medis')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('riwayat_alergi')
                    ->label('Riwayat Alergi')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('riwayat_penyakit')
                    ->label('Riwayat Penyakit')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('rencana_terapi')
                    ->label('Rencana Terapi')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('catatan')
                    ->label('Catatan')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
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
    