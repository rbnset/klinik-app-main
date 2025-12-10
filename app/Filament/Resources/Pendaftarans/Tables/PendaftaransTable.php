<?php

namespace App\Filament\Resources\Pendaftarans\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PendaftaransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();
                $role = $user->role?->name ?? null;

                if ($role === 'pasien') {
                    $query->where('pasien_id', optional($user->pasien)->id);
                }

                if ($role === 'dokter') {
                    $query->where('poli_tujuan', 'Poli Umum');
                }

                if ($role === 'bidan') {
                    $query->where('poli_tujuan', 'Poli Kandungan');
                }
            })
            ->paginated(false) // tampilkan semua data tanpa pagination
            ->columns([
                TextColumn::make('pasien.nama_pasien')->label('Nama Pasien')->searchable()->sortable(),
                TextColumn::make('poli_tujuan')->label('Poli Tujuan')->badge()->sortable(),
                TextColumn::make('tenaga_medis_tujuan')->label('Tenaga Medis')->badge()->sortable(),
                TextColumn::make('jenis_pelayanan')->label('Jenis Pelayanan')->badge()->sortable(),
                TextColumn::make('keluhan')->label('Keluhan')->limit(30)->wrap(),
                BadgeColumn::make('status')->label('Status')->colors([
                    // pastikan nilai status cocok dengan database (key = nilai status, value = warna)
                    'menunggu' => 'primary',
                    'diproses' => 'warning',
                    'selesai' => 'success',
                    'batal'   => 'danger',
                ])->sortable(),
            ])
            ->filters([
                SelectFilter::make('poli_tujuan')->label('Poli Tujuan')->options([
                    'Poli Umum' => 'Poli Umum',
                    'Poli Kandungan' => 'Poli Kandungan',
                ]),
                SelectFilter::make('tenaga_medis_tujuan')->label('Tenaga Medis')->options([
                    'Dokter' => 'Dokter',
                    'Bidan' => 'Bidan',
                ]),
                SelectFilter::make('jenis_pelayanan')->label('Jenis Pelayanan')->options([
                    'umum' => 'Umum',
                    'bpjs' => 'BPJS',
                    'asuransi' => 'Asuransi',
                ]),
                SelectFilter::make('status')->label('Status')->options([
                    'menunggu' => 'Menunggu',
                    'diproses' => 'Diproses',
                    'selesai' => 'Selesai',
                    'batal' => 'Batal',
                ]),
            ])
            ->recordActions([
                EditAction::make()->visible(fn() => Auth::check() && Auth::user()->role?->name !== 'pasien'),
            ])
            ->toolbarActions([
                // Hanya Bulk Actions, tidak perlu CreateAction
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn() => Auth::check() && in_array(Auth::user()->role?->name, ['admin', 'petugas'])),
                ]),
            ]);
    }
}
