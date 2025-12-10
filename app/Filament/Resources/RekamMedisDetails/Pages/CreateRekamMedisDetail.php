<?php

namespace App\Filament\Resources\RekamMedisDetails\Pages;

use App\Filament\Resources\RekamMedisDetails\RekamMedisDetailResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRekamMedisDetail extends CreateRecord
{
    protected static string $resource = RekamMedisDetailResource::class;

    protected static ?string $title = 'Input Rekam Medis Detail';

    public function getBreadcrumb(): string
    {
        return 'Input Rekam Medis Detail';
    }

    // CreateResourcePage.php
    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('create')
                ->label('Simpan'),
            \Filament\Actions\Action::make('cancel')
                ->label('Batal'),
        ];
    }
}
