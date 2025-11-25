<?php

namespace App\Filament\Resources\Tindakans\Pages;

use App\Filament\Resources\Tindakans\TindakanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTindakan extends CreateRecord
{
    protected static string $resource = TindakanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ambil nama role user dari relasi user->role->name
        $data['role'] = auth()->user()->role->name;

        return $data;
    }
}
