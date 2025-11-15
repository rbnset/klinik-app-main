<?php

namespace App\Filament\Resources\Pasiens\Pages;

use App\Filament\Resources\Pasiens\PasienResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePasien extends CreateRecord
{
    protected static string $resource = PasienResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // otomatis beri role "pasien"
        $data['role'] = 'pasien';

        return $data;
    }
}
