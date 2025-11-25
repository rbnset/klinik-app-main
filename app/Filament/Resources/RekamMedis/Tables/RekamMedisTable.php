<?php

namespace App\Filament\Resources\RekamMedis\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class RekamMedisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => self::applyRoleFilter($query))

            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('RM')
                    ->sortable(),

                Tables\Columns\TextColumn::make('pasien.nama_pasien')
                    ->label('Pasien')
                    ->searchable(),

                Tables\Columns\TextColumn::make('pemeriksaan.tanggal_periksa')
                    ->label('Tgl Pemeriksaan')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('diagnosa.nama_diagnosa')
                    ->label('Diagnosa')
                    ->formatStateUsing(fn ($state, $record) => $record->diagnosa?->nama_diagnosa ?? '-')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal Rekam Medis')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('riwayat_alergi')
                    ->label('Riwayat Alergi')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('riwayat_penyakit')
                    ->label('Riwayat Penyakit')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('rencana_terapi')
                    ->label('Rencana Terapi')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->recordActions(self::recordActions())
            ->toolbarActions(self::toolbarActions());
    }

    /**
     * ===========================
     * FILTER DATA SESUAI ROLE
     * ===========================
     */
    private static function applyRoleFilter(Builder $query)
    {
        $user = Auth::user();
        $role = $user->role?->name ?? null;

        // Eager load relasi penting
        $query->with(['diagnosa', 'pemeriksaan', 'pemeriksaan.pendaftaran', 'pasien']);

        // ADMIN → Melihat semua data
        if ($role === 'admin') {
            return;
        }

        // DOKTER → Hanya poli umum
        if ($role === 'dokter') {
            $query->whereHas('pemeriksaan.pendaftaran', fn ($q) =>
                $q->where('poli_tujuan', 'Poli Umum')
            );
            return;
        }

        // BIDAN → Hanya poli kandungan
        if ($role === 'bidan') {
            $query->whereHas('pemeriksaan.pendaftaran', fn ($q) =>
                $q->where('poli_tujuan', 'Poli Kandungan')
            );
            return;
        }

        // PASIEN → Hanya RM miliknya
        if ($role === 'pasien') {
            $pasienId = $user->pasien?->id;
            $query->where('pasien_id', $pasienId);
            return;
        }

        // Role lain tidak boleh lihat apapun
        $query->whereRaw('0 = 1');
    }

    /**
     * ===========================
     * ACTION PER ROLE
     * ===========================
     */
    private static function recordActions(): array
    {
        $role = Auth::user()->role?->name;

        if ($role === 'admin') {
            // Admin tidak boleh edit sama sekali
            return [];
        }

        // Dokter & Bidan → bisa edit
        return [
            EditAction::make(),
        ];
    }

    /**
     * ===========================
     * TOOLBAR ACTIONS (BULK)
     * ===========================
     */
    private static function toolbarActions(): array
    {
        $role = Auth::user()->role?->name;

        if ($role === 'admin') {
            // Admin tidak boleh delete
            return [];
        }

        return [
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ];
    }
}
