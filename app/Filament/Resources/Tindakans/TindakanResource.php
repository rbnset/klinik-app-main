<?php

namespace App\Filament\Resources\Tindakans;

use App\Filament\Resources\Tindakans\Pages\CreateTindakan;
use App\Filament\Resources\Tindakans\Pages\EditTindakan;
use App\Filament\Resources\Tindakans\Pages\ListTindakans;
use App\Filament\Resources\Tindakans\Schemas\TindakanForm;
use App\Filament\Resources\Tindakans\Tables\TindakansTable;
use App\Models\Tindakan;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TindakanResource extends Resource
{
    protected static ?string $model = Tindakan::class;

    protected static string| \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return TindakanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TindakansTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        // ADMIN -> lihat semua tindakan
        if ($user->hasRole('admin')) {
            return parent::getEloquentQuery();
        }

        // BIDAN -> hanya tindakan milik bidan
        if ($user->hasRole('bidan')) {
            return parent::getEloquentQuery()
                ->where('role', 'bidan');
        }

        // DOKTER -> hanya tindakan milik dokter
        if ($user->hasRole('dokter')) {
            return parent::getEloquentQuery()
                ->where('role', 'dokter');
        }

        // Default
        return parent::getEloquentQuery();
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTindakans::route('/'),
            'create' => CreateTindakan::route('/create'),
            'edit' => EditTindakan::route('/{record}/edit'),
        ];
    }
}
