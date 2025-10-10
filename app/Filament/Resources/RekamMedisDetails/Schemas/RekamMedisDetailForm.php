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
use App\Models\Tindakan;
use Filament\Schemas\Components\Grid as ComponentsGrid;

class RekamMedisDetailForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            ComponentsGrid::make(3)->schema([
                Select::make('rekam_medis_id')
                    ->label('Rekam Medis')
                    ->relationship('rekamMedis', 'id')
                    ->getOptionLabelFromRecordUsing(function ($rec) {
                        if (! $rec) return '';
                        $tgl = $rec->tanggal ? $rec->tanggal->format('d/m/Y H:i') : '-';
                        return 'RM-' . $rec->getKey() . ' | ' . $tgl;
                    })
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('tipe')
                    ->options([
                        'tindakan'  => 'Tindakan',
                        'obat'      => 'Obat',
                        'lab'       => 'Lab',
                        'radiologi' => 'Radiologi',
                        'lain'      => 'Lain',
                    ])
                    ->required()
                    ->reactive(),

                TextInput::make('satuan')
                    ->maxLength(30)
                    ->placeholder('mis. tablet, botol, kali, dst.'),
            ]),

            Textarea::make('deskripsi')
                ->label('Deskripsi/Instruksi')
                ->rows(2)
                ->required()
                ->columnSpanFull(),

            ComponentsGrid::make(4)->schema([
                TextInput::make('qty')
                    ->numeric()
                    ->default(1)
                    ->reactive()
                    ->afterStateUpdated(
                        fn(callable $get, callable $set) =>
                        $set('subtotal', (float) $get('qty') * (float) $get('harga_satuan'))
                    ),

                TextInput::make('harga_satuan')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->reactive()
                    ->afterStateUpdated(
                        fn(callable $get, callable $set) =>
                        $set('subtotal', (float) $get('qty') * (float) $get('harga_satuan'))
                    ),

                TextInput::make('subtotal')
                    ->numeric()
                    ->prefix('Rp')
                    ->readOnly(),

                TextInput::make('id') // hanya untuk referensi cepat saat edit (opsional)
                    ->label('Detail ID')
                    ->disabled()
                    ->dehydrated(false)
                    ->visibleOn('edit'),
            ])->columnSpanFull(),

            // OPSIONAL: nested tindakan kalau tipe = 'tindakan'
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
                            $t = Tindakan::find($state);
                            if ($t) {
                                $set('tarif', $t->tarif_default);
                            }
                        }),

                    TextInput::make('qty')
                        ->numeric()
                        ->default(1)
                        ->reactive()
                        ->afterStateUpdated(
                            fn(callable $get, callable $set) =>
                            $set('subtotal', (float) $get('qty') * (float) $get('tarif'))
                        ),

                    TextInput::make('tarif')
                        ->numeric()
                        ->prefix('Rp')
                        ->default(0)
                        ->reactive()
                        ->afterStateUpdated(
                            fn(callable $get, callable $set) =>
                            $set('subtotal', (float) $get('qty') * (float) $get('tarif'))
                        ),

                    TextInput::make('subtotal')
                        ->numeric()
                        ->prefix('Rp')
                        ->readOnly(),
                ]),
        ]);
    }
}
