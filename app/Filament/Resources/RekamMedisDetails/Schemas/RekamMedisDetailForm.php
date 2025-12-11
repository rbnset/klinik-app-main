<?php

namespace App\Filament\Resources\RekamMedisDetails\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\{
    Grid,
    Select,
    TextInput,
    Textarea,
    Repeater
};
use Filament\Schemas\Components\Grid as ComponentsGrid;
use Illuminate\Support\Facades\Auth;
use App\Models\RekamMedis;
use App\Models\Tindakan;

class RekamMedisDetailForm
{
    public static function configure(Schema $schema): Schema
    {
        $user = Auth::user();
        $role = ucfirst($user->role?->name ?? ''); // Dokter / Bidan

        return $schema->components([

            // ==============================
            // SELECT REKAM MEDIS (FILTER BY ROLE)
            // ==============================
            ComponentsGrid::make(3)->schema([

                Select::make('rekam_medis_id')
                    ->label('Rekam Medis')
                    ->options(function () use ($role) {

                        return RekamMedis::query()
                            ->whereHas('pemeriksaan.pendaftaran', function ($q) use ($role) {
                                // rekam medis harus sesuai tujuan tenaga medis
                                $q->where('tenaga_medis_tujuan', $role);
                            })
                            ->with(['pemeriksaan', 'pasien'])
                            ->get()
                            ->mapWithKeys(function ($rm) {
                                $tgl = $rm->tanggal?->format('d/m/Y H:i') ?? '-';
                                $nama = $rm->pasien?->nama_pasien ?? 'Tanpa Nama';
                                return [
                                    $rm->id => "RM-{$rm->id} | {$nama} | {$tgl}"
                                ];
                            });
                    })
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('tipe')
                    ->options([
                        'obat'      => 'Obat',
                        'suntik'       => 'suntik',
                        'infus' => 'infus',
                    ])
                    ->required()
                    ->reactive(),

                TextInput::make('satuan')
                    ->label('Satuan')
                    ->maxLength(30)
                    ->placeholder('mis. tablet, botol, kali'),
            ]),

            // ==============================
            // DESKRIPSI
            // ==============================
            Textarea::make('deskripsi')
                ->label('Deskripsi / Instruksi')
                ->rows(2)
                ->required()
                ->columnSpanFull(),

            // ==============================
            // QTY – HARGA — SUBTOTAL
            // ==============================
            ComponentsGrid::make(4)->schema([
                TextInput::make('qty')
                    ->numeric()
                    ->default(1)
                    ->reactive()
                    ->afterStateUpdated(function (callable $get, callable $set) {
                        $set('subtotal', (float)$get('qty') * (float)$get('harga_satuan'));
                    }),

                TextInput::make('id')
                    ->label('Detail ID')
                    ->disabled()
                    ->dehydrated(false)
                    ->visibleOn('edit'),
            ])->columnSpanFull(),

            // ==============================
            // REPEATER TINDAKAN
            // ==============================
            Repeater::make('detailTindakans')
                ->relationship('detailTindakans')
                ->hidden(fn(callable $get) => $get('tipe') !== 'tindakan')
                ->columns(4)
                ->collapsible()
                ->schema([

                    Select::make('tindakan_id')
                        ->relationship('tindakan', 'nama')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $set('tarif', Tindakan::find($state)?->tarif_default ?? 0);
                        }),

                    TextInput::make('qty')
                        ->numeric()
                        ->default(1)
                        ->reactive()
                        ->afterStateUpdated(function (callable $get, callable $set) {
                            $set('subtotal', (float)$get('qty') * (float)$get('tarif'));
                        }),

                    TextInput::make('tarif')
                        ->numeric()
                        ->prefix('Rp')
                        ->default(0)
                        ->reactive()
                        ->afterStateUpdated(function (callable $get, callable $set) {
                            $set('subtotal', (float)$get('qty') * (float)$get('tarif'));
                        }),

                    TextInput::make('subtotal')
                        ->numeric()
                        ->prefix('Rp')
                        ->readOnly(),
                ]),

        ]);
    }
}
