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

                    if ($user->hasRole('dokter')) {
                        $role = 'dokter';
                    } elseif ($user->hasRole('bidan')) {
                        $role = 'bidan';
                    } else {
                        return [];
                    }

                    return Tindakan::where('role', $role)
                        ->pluck('nama_tindakan', 'id');
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
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, $get) {
                    $tarif = $get('tarif') ?? 0;
                    $set('subtotal', $tarif * $state);
                }),

            // Tarif otomatis
            TextInput::make('tarif')
                ->label('Tarif')
                ->disabled()
                ->numeric()
                ->required(),

            // Subtotal otomatis
            TextInput::make('subtotal')
                ->label('Subtotal')
                ->disabled()
                ->numeric(),
        ]);
    }
}
