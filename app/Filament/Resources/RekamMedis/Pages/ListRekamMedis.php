<?php

namespace App\Filament\Resources\RekamMedis\Pages;

use App\Filament\Resources\RekamMedis\RekamMedisResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRekamMedis extends ListRecords
{
    protected static string $resource = RekamMedisResource::class;

    protected function getHeaderActions(): array
    {
        $role = auth()->user()?->role?->name;

        // Admin tidak boleh melihat tombol New Rekam Medis
        if ($role === 'admin') {
            return [];
        }

        // Dokter & Bidan tetap bisa create
        return [
            CreateAction::make(),
        ];
    }
}
