<?php

namespace App\Filament\Resources\Pemeriksaans\Pages;

use App\Filament\Resources\Pemeriksaans\PemeriksaanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPemeriksaans extends ListRecords
{
    protected static string $resource = PemeriksaanResource::class;

    protected function getHeaderActions(): array
    {
        $role = auth()->user()?->role?->name ?? null;

        // ❌ Admin → jangan tampilkan tombol create
        if (! in_array($role, ['dokter', 'bidan'])) {
            return [];
        }

        // ✔️ Dokter & Bidan → tampilkan tombol create
        return [
            Actions\CreateAction::make(),
        ];
    }
}
