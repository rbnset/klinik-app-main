<?php

namespace App\Filament\Resources\Pemeriksaans;

use App\Filament\Resources\Pemeriksaans\Pages\CreatePemeriksaan;
use App\Filament\Resources\Pemeriksaans\Pages\EditPemeriksaan;
use App\Filament\Resources\Pemeriksaans\Pages\ListPemeriksaans;
use App\Filament\Resources\Pemeriksaans\Schemas\PemeriksaanForm;
use App\Filament\Resources\Pemeriksaans\Tables\PemeriksaansTable;
use App\Models\Pemeriksaan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PemeriksaanResource extends Resource
{
    protected static ?string $model = Pemeriksaan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PemeriksaanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PemeriksaansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPemeriksaans::route('/'),
            'create' => CreatePemeriksaan::route('/create'),
            'edit'   => EditPemeriksaan::route('/{record}/edit'),
        ];
    }

    /**
     * Hanya dokter & bidan yang boleh membuat pemeriksaan.
     * Admin hanya melihat (read-only).
     */
    public static function canCreate(): bool
    {
        $role = auth()->user()?->role?->name ?? null;
        return in_array($role, ['dokter', 'bidan']);
    }

    /**
     * Hanya dokter & bidan boleh edit.
     */
    public static function canEdit($record): bool
    {
        $role = auth()->user()?->role?->name ?? null;
        return in_array($role, ['dokter', 'bidan']);
    }

    /**
     * Tidak ada yang boleh menghapus pemeriksaan.
     */
    public static function canDelete($record): bool
    {
        return false;
    }
}
