<?php

namespace App\Filament\Resources\Jadwals;

use App\Filament\Resources\Jadwals\Pages\CreateJadwal;
use App\Filament\Resources\Jadwals\Pages\EditJadwal;
use App\Filament\Resources\Jadwals\Pages\ListJadwals;
use App\Filament\Resources\Jadwals\Schemas\JadwalForm;
use App\Filament\Resources\Jadwals\Tables\JadwalsTable;
use App\Models\Jadwal;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;


class JadwalResource extends Resource
{
    protected static ?string $model = Jadwal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user  = auth()->user();

        if (! $user) {
            return $query;
        }

        // Dokter: hanya lihat jadwal miliknya (FK = user_id)
        if ($user->role?->name === 'dokter') {
            $query->where('user_id', $user->id);
        }

        // (Opsional) Bidan juga dibatasi ke miliknya sendiri:
        if ($user->role?->name === 'bidan') {
            $query->where('user_id', $user->id);
        }

        return $query;
    }


    public static function form(Schema $schema): Schema
    {
        return JadwalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JadwalsTable::configure($table);
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
            'index' => ListJadwals::route('/'),
            'create' => CreateJadwal::route('/create'),
            'edit' => EditJadwal::route('/{record}/edit'),
        ];
    }
}
