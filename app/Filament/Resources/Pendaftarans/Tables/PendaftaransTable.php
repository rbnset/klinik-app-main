<?php

namespace App\Filament\Resources\Pendaftarans\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\Pendaftaran;

class PendaftaransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // 🔍 Batasi query berdasarkan role login
            ->modifyQueryUsing(function (Builder $query) {
    $user = Auth::user();
    $role = $user->role?->name;

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


            ->columns([
                TextColumn::make('pasien.nama_pasien')
                    ->label('Nama Pasien')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Nama Petugas')
                    ->searchable(),

                TextColumn::make('poli_tujuan')
                    ->label('Poli Tujuan')
                    ->badge()
                    ->sortable(),

                TextColumn::make('tenaga_medis_tujuan')
                    ->label('Tenaga Medis')
                    ->badge()
                    ->sortable(),

                TextColumn::make('jenis_pelayanan')
                    ->label('Jenis Pelayanan')
                    ->badge()
                    ->sortable(),

                TextColumn::make('keluhan')
                    ->label('Keluhan')
                    ->limit(30)
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

            // 🔧 Action per record
            ->recordActions([
                EditAction::make()
                    ->visible(function ($record) {
                        $user = Auth::user();
                        $role = $user->role?->name;

                        if ($role === 'pasien') {
                            // pasien hanya bisa edit pendaftarannya sendiri
                            return $record->pasien_id === optional($user->pasien)->id;
                        }

                        if ($role === 'dokter') {
                            // dokter bisa edit data pasien yang mendaftar ke poli umum
                            return $record->poli_tujuan === 'Poli Umum';
                        }

                        if ($role === 'bidan') {
                            // bidan bisa edit data pasien yang mendaftar ke poli kandungan
                            return $record->poli_tujuan === 'Poli Kandungan';
                        }

                        // admin dan petugas bisa edit semua
                        return in_array($role, ['admin', 'petugas']);
                    }),
            ])

            // 🔨 Toolbar actions (create, bulk delete)
            ->toolbarActions([
                CreateAction::make()
                    ->visible(fn() => in_array(Auth::user()->role?->name, ['petugas', 'admin'])),

                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn() => in_array(Auth::user()->role?->name, ['petugas', 'admin'])),
                ]),
            ]);
    }
}
