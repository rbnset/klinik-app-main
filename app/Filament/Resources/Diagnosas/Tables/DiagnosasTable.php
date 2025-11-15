<?php

namespace App\Filament\Resources\Diagnosas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DiagnosasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // Filter rows sesuai role (dokter / bidan / pasien)
            ->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();
                $role = $user->role?->name ?? null;

                if ($role === 'dokter') {
                    // Tampilkan hanya diagnosa yang terkait pemeriksaan → pendaftaran.poli_tujuan = Poli Umum
                    $query->whereHas('pemeriksaan', function ($q) {
                        $q->whereHas('pendaftaran', function ($q2) {
                            $q2->where('poli_tujuan', 'Poli Umum');
                        });
                    });
                } elseif ($role === 'bidan') {
                    // Tampilkan hanya diagnosa yang terkait pemeriksaan → pendaftaran.poli_tujuan = Poli Kandungan
                    $query->whereHas('pemeriksaan', function ($q) {
                        $q->whereHas('pendaftaran', function ($q2) {
                            $q2->where('poli_tujuan', 'Poli Kandungan');
                        });
                    });
                } elseif ($role === 'pasien') {
                    // Pasien hanya melihat diagnosa miliknya sendiri
                    $pasienId = optional($user->pasien)->id;
                    $query->whereHas('pemeriksaan', function ($q) use ($pasienId) {
                        $q->where('pasien_id', $pasienId);
                    });
                } else {
                    // Role lain tidak boleh melihat data
                    $query->whereRaw('0 = 1');
                }
            })

            ->columns([
                // Kolom pemeriksaan dengan label human-readable
                TextColumn::make('pemeriksaan.id')
                    ->label('Pemeriksaan')
                    ->sortable()
                    ->searchable(fn ($query, $search) => $query->orWhereHas('pemeriksaan', function ($q) use ($search) {
                        $q->where('id', $search)
                          ->orWhereHas('pasien', function ($qp) use ($search) {
                              $qp->where('nama_pasien', 'like', "%{$search}%");
                          });
                    }))
                    ->formatStateUsing(function ($state, $record) {
                        $pemeriksaan = $record->pemeriksaan;
                        $pasienNama = $pemeriksaan && $pemeriksaan->pasien ? $pemeriksaan->pasien->nama_pasien : '-';
                        return "Periksa ID: {$state} | Pasien: {$pasienNama}";
                    }),

                TextColumn::make('nama_diagnosa')
                    ->label('Nama Diagnosa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jenis_diagnosa')
                    ->label('Jenis Diagnosa')
                    ->sortable(),

                TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50),

                TextColumn::make('updated_at')
                    ->label('Diubah')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])

            ->filters([
                //
            ])

            ->recordActions([
                EditAction::make()
                    ->visible(fn() => in_array(Auth::user()->role?->name, ['dokter', 'bidan'])),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn() => in_array(Auth::user()->role?->name, ['admin', 'petugas'])),
                ]),
            ]);
    }
}
