<?php

namespace App\Filament\Resources\RekamMedis\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\{
    Grid,
    Select,
    DateTimePicker,
    Textarea,
    TextInput
};
use Filament\Schemas\Components\Grid as ComponentsGrid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\Pasien;
use App\Models\Pemeriksaan;
use App\Models\Diagnosa;

class RekamMedisForm
{
    public static function configure(Schema $schema): Schema
    {
        $user = Auth::user();
        $role = $user->role?->name ?? null; // dokter / bidan

        return $schema->components([
            // ==============================
            // Pilih Pasien, Pemeriksaan, Diagnosa
            // ==============================
            ComponentsGrid::make(3)->schema([
                Select::make('pasien_id')
                    ->label('Pasien')
                    ->options(function () use ($user, $role) {
                        return Pasien::query()
                            ->whereHas('diagnosa') // pasien harus sudah ada diagnosa
                            ->whereHas('pendaftaran', function ($q) use ($role) {
                                // filter sesuai role login
                                $q->where('tenaga_medis_tujuan', ucfirst($role));
                            })
                            ->get()
                            ->mapWithKeys(function ($pasien) {
                                return [$pasien->id => $pasien->nama_pasien];
                            });
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn($state, callable $set) => $set('pemeriksaan_id', null)),

                Select::make('pemeriksaan_id')
                    ->label('Pemeriksaan')
                    ->options(function (callable $get) {
                        $pid = $get('pasien_id');
                        if (!$pid) {
                            return [];
                        }

                        return Pemeriksaan::query()
                            ->where('pasien_id', $pid)
                            ->latest('tanggal_periksa')
                            ->get()
                            ->mapWithKeys(function ($rec) {
                                $dt = $rec->tanggal_periksa
                                    ? ($rec->tanggal_periksa instanceof \DateTimeInterface
                                        ? $rec->tanggal_periksa->format('d/m/Y H:i')
                                        : Carbon::parse($rec->tanggal_periksa)->format('d/m/Y H:i'))
                                    : '-';
                                return [$rec->id => 'PM-' . $rec->id . ' | ' . $dt];
                            })
                            ->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disablePlaceholderSelection()
                    ->disabled(fn(callable $get) => blank($get('pasien_id')))
                    ->rule('exists:pemeriksaans,id'),

                Select::make('diagnosa_id')
                    ->label('Diagnosa')
                    ->options(function (callable $get) {
                        $pemeriksaanId = $get('pemeriksaan_id');
                        if (!$pemeriksaanId) return [];

                        return \App\Models\Diagnosa::where('pemeriksaan_id', $pemeriksaanId)
                            ->pluck('nama_diagnosa', 'id')
                            ->toArray();
                    })
                    ->required()
                    ->searchable()
                    ->preload(),
    
            ]),

            // ==============================
            // Detail Rekam Medis
            // ==============================
            ComponentsGrid::make(2)->schema([
                DateTimePicker::make('tanggal')
                    ->label('Tanggal Rekam Medis')
                    ->native(false)
                    ->default(now())
                    ->required(),

                Textarea::make('rencana_terapi')
                    ->label('Rencana Terapi')
                    ->rows(2),
            ]),

            ComponentsGrid::make(2)->schema([
                Textarea::make('riwayat_alergi')
                    ->label('Riwayat Alergi')
                    ->rows(2)
                    ->columnSpan(1),

                Textarea::make('riwayat_penyakit')
                    ->label('Riwayat Penyakit')
                    ->rows(2)
                    ->columnSpan(1),
            ]),

            Textarea::make('catatan')
                ->label('Catatan Tambahan')
                ->rows(3)
                ->columnSpanFull(),
        ]);
    }
}
