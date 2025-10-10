<?php

namespace App\Filament\Resources\DetailTindakans;

use App\Filament\Resources\DetailTindakans\Pages\CreateDetailTindakan;
use App\Filament\Resources\DetailTindakans\Pages\EditDetailTindakan;
use App\Filament\Resources\DetailTindakans\Pages\ListDetailTindakans;
use App\Filament\Resources\DetailTindakans\Schemas\DetailTindakanForm;
use App\Filament\Resources\DetailTindakans\Tables\DetailTindakansTable;
use App\Models\DetailTindakan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DetailTindakanResource extends Resource
{
    protected static ?string $model = DetailTindakan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return DetailTindakanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DetailTindakansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDetailTindakans::route('/'),
            'create' => CreateDetailTindakan::route('/create'),
            'edit' => EditDetailTindakan::route('/{record}/edit'),
        ];
    }
}
