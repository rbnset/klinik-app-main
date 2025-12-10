<?php

namespace App\Filament\Resources\Pendaftarans\Pages;

use App\Filament\Resources\Pendaftarans\PendaftaranResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePendaftaran extends CreateRecord
{
    protected static string $resource = PendaftaranResource::class;

    protected static ?string $title = 'Input Pendaftaran';

    public function getBreadcrumb(): string
    {
        return 'Input Pendaftaran';
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
