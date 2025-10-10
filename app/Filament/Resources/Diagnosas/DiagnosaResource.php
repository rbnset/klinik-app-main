<?php

namespace App\Filament\Resources\Diagnosas;

use App\Filament\Resources\Diagnosas\Pages\CreateDiagnosa;
use App\Filament\Resources\Diagnosas\Pages\EditDiagnosa;
use App\Filament\Resources\Diagnosas\Pages\ListDiagnosas;
use App\Filament\Resources\Diagnosas\Schemas\DiagnosaForm;
use App\Filament\Resources\Diagnosas\Tables\DiagnosasTable;
use App\Models\Diagnosa;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DiagnosaResource extends Resource
{
    protected static ?string $model = Diagnosa::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return DiagnosaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DiagnosasTable::configure($table);
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
            'index' => ListDiagnosas::route('/'),
            'create' => CreateDiagnosa::route('/create'),
            'edit' => EditDiagnosa::route('/{record}/edit'),
        ];
    }
}
