<?php

namespace App\Filament\Resources\Pasiens;

use App\Filament\Resources\Pasiens\Pages;
use App\Filament\Resources\Pasiens\Tables\PasiensTable;
use App\Models\Pasien;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use App\Filament\Resources\Pasiens\Pages\CreatePasien;
use App\Filament\Resources\Pasiens\Pages\EditPasien;
use App\Filament\Resources\Pasiens\Pages\ListPasiens;
use App\Filament\Resources\Pasiens\Pages\ViewPasien;
use App\Filament\Resources\Pasiens\Schemas\PasienForm;
use App\Filament\Resources\Pasiens\Schemas\PasienInfolist;
use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class PasienResource extends Resource
{
    protected static ?string $model = Pasien::class;


    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return PasienForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PasienInfolist::configure($schema);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return PasiensTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPasiens::route('/'),
            'create' => Pages\CreatePasien::route('/create'),
            'edit' => Pages\EditPasien::route('/{record}/edit'),

            // ✅ TANPA {record}, route statis
            'kartu-pasien-saya' => Pages\KartuPasienSaya::route('/kartu-pasien-saya'),
        ];
    }
}
