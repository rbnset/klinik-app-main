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

    /**
     * Menu harus tetap muncul untuk semua role (admin, dokter, bidan)
     */
    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListRekamMedisDetails::route('/'),
            'create' => CreateRekamMedisDetail::route('/create'),
            'edit'   => EditRekamMedisDetail::route('/{record}/edit'),
        ];
    }

    /**
     * Admin tidak boleh create.
     */
    public static function canCreate(): bool
    {
        $role = auth()->user()?->role?->name;
        return in_array($role, ['dokter', 'bidan']);
    }

    /**
     * Admin tidak boleh edit.
     */
    public static function canEdit($record): bool
    {
        $role = auth()->user()?->role?->name;
        return in_array($role, ['dokter', 'bidan']);
    }

    /**
     * Semua role tidak boleh delete.
     */
    public static function canDelete($record): bool
    {
        return false;
    }
}
