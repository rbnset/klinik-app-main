<?php

namespace App\Filament\Resources\Pasiens\Tables;

use App\Filament\Resources\Pasiens\Pages\KartuPasienSaya;
use App\Models\Pendaftaran;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PasiensTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_pasien')
                    ->label('Nama Pasien')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tempat_lahir')
                    ->label('Tempat Lahir')
                    ->sortable(),

                TextColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->sortable(),

                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->sortable(),

                TextColumn::make('golongan_darah')
                    ->label('Gol. Darah')
                    ->sortable(),

                TextColumn::make('agama')
                    ->label('Agama')
                    ->sortable(),

                TextColumn::make('status_perkawinan')
                    ->label('Status Perkawinan')
                    ->sortable(),

                TextColumn::make('no_telp')
                    ->label('No. Telepon')
                    ->sortable(),

                TextColumn::make('pekerjaan')
                    ->label('Pekerjaan')
                    ->sortable(),

                TextColumn::make('nama_penanggung_jawab')
                    ->label('Nama Penanggung Jawab')
                    ->sortable(),

                TextColumn::make('no_telp_penanggung_jawab')
                    ->label('Telp Penanggung Jawab')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();

                if (! $user) {
                    return;
                }

                // ========= ROLE PASIEN → hanya melihat dirinya =========
                // Lebih aman pakai relasi pasien, bukan role name
                if (method_exists($user, 'pasien') && $user->pasien()->exists()) {
                    $pasienId = optional($user->pasien)->id;

                    if ($pasienId) {
                        $query->where('id', $pasienId);
                    } else {
                        $query->whereRaw('1 = 0');
                    }
                }

                // ========= ROLE DOKTER → pasien Poli Umum =========
                if ($user->hasRole('dokter')) {
                    $pasienIds = Pendaftaran::where('poli_tujuan', 'Poli Umum')
                        ->pluck('pasien_id');

                    $query->whereIn('id', $pasienIds);
                }

                // ========= ROLE BIDAN → pasien Poli Kandungan =========
                if ($user->hasRole('bidan')) {
                    $pasienIds = Pendaftaran::where('poli_tujuan', 'Poli Kandungan')
                        ->pluck('pasien_id');

                    $query->whereIn('id', $pasienIds);
                }

                // ROLE admin / petugas → lihat semua pasien
            })
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),

                // ========= Tombol "Kartu Pasien" =========
                Action::make('kartu')
                    ->label('Kartu Pasien')
                    ->icon('heroicon-o-identification')
                    ->visible(function () {
                        $user = Auth::user();

                        if (! $user) {
                            return false;
                        }

                        return $user->hasAnyRole([
                            'petugas',
                            'admin',
                            'dokter',
                            'bidan',
                        ]) || (method_exists($user, 'pasien') && $user->pasien()->exists());
                    })
                    ->url(function ($record) {
                        $user = Auth::user();

                        // Pasien → buka page tanpa parameter, diambil dari relasi user->pasien
                        if ($user && method_exists($user, 'pasien') && $user->pasien()->exists()) {
                            return KartuPasienSaya::getUrl();
                        }

                        // Petugas / admin / dokter / bidan → kirim pasien_id via query string
                        return KartuPasienSaya::getUrl([
                            'pasien_id' => $record->id,
                        ]);
                    })
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
