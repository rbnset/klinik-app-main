<?php

namespace App\Filament\Resources\DetailTindakans\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Models\Tindakan;

class DetailTindakanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            // Relasi ke Detail RM
            Select::make('rekam_medis_detail_id')
                ->label('Detail RM')
                ->relationship('detail', 'deskripsi')
                ->required(),

            // Dropdown Tindakan sesuai role
            Select::make('tindakan_id')
                ->label('Tindakan')
                ->required()
                ->options(function () {
                    $user = auth()->user();

                    // === ROLE ADMIN → LIHAT SEMUA ===
                    if ($user->hasRole('admin')) {
                        return Tindakan::pluck('nama_tindakan', 'id');
                    }

                    // === ROLE DOKTER ===
                    if ($user->hasRole('dokter')) {
                        return Tindakan::where('role', 'dokter')
                            ->pluck('nama_tindakan', 'id');
                    }

                    // === ROLE BIDAN ===
                    if ($user->hasRole('bidan')) {
                        return Tindakan::where('role', 'bidan')
                            ->pluck('nama_tindakan', 'id');
                    }

                    // Role lain kosong
                    return [];
                })
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $tindakan = Tindakan::find($state);
                    if ($tindakan) {
                        // Set tarif dan subtotal otomatis (qty default 1)
                        $set('tarif', $tindakan->tarif);
                        $set('subtotal', $tindakan->tarif);
                    }
                }),

            // Qty
            TextInput::make('qty')
                ->label('Qty')
                ->numeric()
                ->default(1)
                ->required()
                ->reactive(),
        ]);
    }
}
