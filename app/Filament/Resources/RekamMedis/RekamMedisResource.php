<?php

namespace App\Filament\Resources\RekamMedis;

use App\Filament\Resources\RekamMedis\Pages\CreateRekamMedis;
use App\Filament\Resources\RekamMedis\Pages\EditRekamMedis;
use App\Filament\Resources\RekamMedis\Pages\ListRekamMedis;
use App\Filament\Resources\RekamMedis\Schemas\RekamMedisForm;
use App\Filament\Resources\RekamMedis\Tables\RekamMedisTable;
use App\Models\RekamMedis;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RekamMedisResource extends Resource
{
    protected static ?string $model = RekamMedis::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return RekamMedisForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RekamMedisTable::configure($table);
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
            'index' => ListRekamMedis::route('/'),
            'create' => CreateRekamMedis::route('/create'),
            'edit' => EditRekamMedis::route('/{record}/edit'),
        ];
    }
}
