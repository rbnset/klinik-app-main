<?php

namespace App\Filament\Resources\Pasiens\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Auth;

class PasiensTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nik')->label('NIK')->searchable()->sortable(),
                TextColumn::make('nama_pasien')->label('Nama Pasien')->searchable()->sortable(),
                TextColumn::make('tempat_lahir')->label('Tempat Lahir')->sortable(),
                TextColumn::make('tanggal_lahir')->label('Tanggal Lahir')->date('d/m/Y')->sortable(),
                TextColumn::make('jenis_kelamin')->label('Jenis Kelamin')->sortable(),
                TextColumn::make('alamat')->label('Alamat')->sortable(),
                TextColumn::make('golongan_darah')->label('Gol. Darah')->sortable(),
                TextColumn::make('agama')->label('Agama')->sortable(),
                TextColumn::make('status_perkawinan')->label('Status Perkawinan')->sortable(),
                TextColumn::make('no_telp')->label('No. Telepon')->sortable(),
                TextColumn::make('pekerjaan')->label('Pekerjaan')->sortable(),
                TextColumn::make('nama_penanggung_jawab')->label('Nama Penanggung Jawab')->sortable(),
                TextColumn::make('no_telp_penanggung_jawab')->label('Telp Penanggung Jawab')->sortable(),
                TextColumn::make('created_at')->label('Dibuat Pada')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();
                $role = $user?->role?->name;

                if ($role === 'pasien') {
                    $query->where('id', optional($user->pasien)->id);
                }

                if ($role === 'dokter') {
                    $pasienIds = Pendaftaran::where('poli_tujuan', 'Poli Umum')->pluck('pasien_id');
                    $query->whereIn('id', $pasienIds);
                }

                if ($role === 'bidan') {
                    $pasienIds = Pendaftaran::where('poli_tujuan', 'Poli Kandungan')->pluck('pasien_id');
                    $query->whereIn('id', $pasienIds);
                }
            })
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),

                // Tombol Kartu Pasien → HANYA muncul untuk pasien & petugas
                Action::make('kartu')
                    ->label('Kartu Pasien')
                    ->visible(fn ($record) => in_array(strtolower(auth()->user()?->role?->name ?? ''), ['pasien', 'petugas']))
                    ->url(fn ($record) => auth()->user()->hasRole('pasien') 
                        ? route('kartu.pasien.saya') 
                        : route('kartu.pasien.show', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
