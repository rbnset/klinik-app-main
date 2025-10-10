<?php

namespace App\Filament\Resources\DetailTindakans\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\{Grid, Select, TextInput};
use App\Models\Tindakan;
use App\Models\RekamMedisDetail; // <-- penting
use Filament\Schemas\Components\Grid as ComponentsGrid;

class DetailTindakanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            ComponentsGrid::make(2)->schema([
                Select::make('rekam_medis_detail_id')
                    ->label('Detail RM')
                    ->options(function () {
                        return RekamMedisDetail::query()
                            ->with('rekamMedis')
                            ->latest('created_at')
                            ->limit(100)
                            ->get()
                            ->mapWithKeys(function ($d) {
                                $rmId = $d->rekamMedis?->id ?? '?';
                                return [$d->id => "RM-{$rmId} | Detail-{$d->id}"];
                            })
                            ->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disablePlaceholderSelection()
                    ->rule('exists:rekam_medis_details,id'),

                Select::make('tindakan_id')
                    ->relationship('tindakan', 'nama_tindakan')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state && ($t = Tindakan::find($state))) {
                            $set('tarif', $t->tarif_default);
                        }
                    })
                    ->rule('exists:tindakans,id'),
            ]),

            ComponentsGrid::make(3)->schema([
                TextInput::make('qty')
                    ->numeric()->default(1)->reactive()
                    ->afterStateUpdated(
                        fn(callable $get, callable $set) =>
                        $set('subtotal', (float)$get('qty') * (float)$get('tarif'))
                    ),

                TextInput::make('tarif')
                    ->numeric()->prefix('Rp')->default(0)->reactive()
                    ->afterStateUpdated(
                        fn(callable $get, callable $set) =>
                        $set('subtotal', (float)$get('qty') * (float)$get('tarif'))
                    ),

                TextInput::make('subtotal')
                    ->numeric()->prefix('Rp')->readOnly(),
            ])->columnSpanFull(),
        ]);
    }
}
