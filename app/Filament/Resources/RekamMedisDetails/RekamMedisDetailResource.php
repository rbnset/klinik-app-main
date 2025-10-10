<?php

namespace App\Filament\Resources\RekamMedisDetails;

use App\Filament\Resources\RekamMedisDetails\Pages\CreateRekamMedisDetail;
use App\Filament\Resources\RekamMedisDetails\Pages\EditRekamMedisDetail;
use App\Filament\Resources\RekamMedisDetails\Pages\ListRekamMedisDetails;
use App\Filament\Resources\RekamMedisDetails\Schemas\RekamMedisDetailForm;
use App\Filament\Resources\RekamMedisDetails\Tables\RekamMedisDetailsTable;
use App\Models\RekamMedisDetail;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RekamMedisDetailResource extends Resource
{
    protected static ?string $model = RekamMedisDetail::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return RekamMedisDetailForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RekamMedisDetailsTable::configure($table);
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
            'index' => ListRekamMedisDetails::route('/'),
            'create' => CreateRekamMedisDetail::route('/create'),
            'edit' => EditRekamMedisDetail::route('/{record}/edit'),
        ];
    }
}
