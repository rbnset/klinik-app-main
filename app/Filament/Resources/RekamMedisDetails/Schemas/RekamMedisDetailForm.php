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
use App\Models\RekamMedis;
use Filament\Schemas\Components\Grid as ComponentsGrid;

class RekamMedisDetailForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            ComponentsGrid::make(3)->schema([

                // 🔹 Pilih Rekam Medis (wajib tampil di create)
                Select::make('rekam_medis_id')
                    ->label('Rekam Medis')
                    ->relationship('rekamMedis', 'id')
                    ->getOptionLabelFromRecordUsing(fn($rec) => $rec ? 'RM-'.$rec->id.' | '.$rec->tanggal?->format('d/m/Y H:i') : '')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->default(fn($livewire) => $livewire->record?->rekam_medis_id ?? null),

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
                    ->afterStateUpdated(fn(callable $get, callable $set) =>
                        $set('subtotal', (float) $get('qty') * (float) $get('harga_satuan'))
                    ),

                TextInput::make('harga_satuan')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->reactive()
                    ->afterStateUpdated(fn(callable $get, callable $set) =>
                        $set('subtotal', (float) $get('qty') * (float) $get('harga_satuan'))
                    ),

                TextInput::make('subtotal')
                    ->numeric()
                    ->prefix('Rp')
                    ->readOnly(),

                TextInput::make('id')
                    ->label('Detail ID')
                    ->disabled()
                    ->dehydrated(false)
                    ->visibleOn('edit'),
            ])->columnSpanFull(),

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
                        ->afterStateUpdated(fn($state, callable $set) => $set('tarif', Tindakan::find($state)?->tarif_default ?? 0)),

                    TextInput::make('qty')
                        ->numeric()
                        ->default(1)
                        ->reactive()
                        ->afterStateUpdated(fn(callable $get, callable $set) =>
                            $set('subtotal', (float) $get('qty') * (float) $get('tarif'))
                        ),

                    TextInput::make('tarif')
                        ->numeric()
                        ->prefix('Rp')
                        ->default(0)
                        ->reactive()
                        ->afterStateUpdated(fn(callable $get, callable $set) =>
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
