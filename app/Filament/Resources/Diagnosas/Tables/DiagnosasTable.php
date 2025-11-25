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
            ->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();
                $role = $user->role?->name ?? null;

                // ============================
                // ADMIN → tampilkan semua data
                // ============================
                if ($role === 'admin') {
                    return; // tanpa filter
                }

                // ============================
                // DOKTER → diagnosa Poli Umum
                // ============================
                if ($role === 'dokter') {
                    $query->whereHas('pemeriksaan', function ($q) {
                        $q->whereHas('pendaftaran', function ($q2) {
                            $q2->where('poli_tujuan', 'Poli Umum');
                        });
                    });
                    return;
                }

                // ============================
                // BIDAN → diagnosa Poli Kandungan
                // ============================
                if ($role === 'bidan') {
                    $query->whereHas('pemeriksaan', function ($q) {
                        $q->whereHas('pendaftaran', function ($q2) {
                            $q2->where('poli_tujuan', 'Poli Kandungan');
                        });
                    });
                    return;
                }

                // ============================
                // PASIEN → diagnosa miliknya saja
                // ============================
                if ($role === 'pasien') {
                    $pasienId = optional($user->pasien)->id;
                    $query->whereHas('pemeriksaan', function ($q) use ($pasienId) {
                        $q->where('pasien_id', $pasienId);
                    });
                    return;
                }

                // Role lain → tidak dapat melihat apapun
                $query->whereRaw('0 = 1');
            })

            ->columns([
                TextColumn::make('pemeriksaan.id')
                    ->label('Pemeriksaan')
                    ->sortable()
                    ->searchable(fn ($query, $search) => 
                        $query->orWhereHas('pemeriksaan', function ($q) use ($search) {
                            $q->where('id', $search)
                              ->orWhereHas('pasien', function ($qp) use ($search) {
                                  $qp->where('nama_pasien', 'like', "%{$search}%");
                              });
                        })
                    )
                    ->formatStateUsing(function ($state, $record) {
                        $pemeriksaan = $record->pemeriksaan;
                        $pasienNama = $pemeriksaan?->pasien?->nama_pasien ?? '-';
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

            ->filters([])

            ->recordActions([
                EditAction::make()
                    ->visible(fn() => in_array(Auth::user()->role?->name, [
                        'admin',   // admin diperbolehkan edit
                        'dokter',
                        'bidan',
                    ])),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn() => in_array(Auth::user()->role?->name, [
                            'admin', // admin diperbolehkan delete
                        ])),
                ]),
            ]);
    }
}
