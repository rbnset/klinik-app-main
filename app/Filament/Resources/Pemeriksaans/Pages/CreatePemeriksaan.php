<?php

namespace App\Filament\Resources\Pemeriksaans\Pages;

use App\Filament\Resources\Pemeriksaans\PemeriksaanResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePemeriksaan extends CreateRecord
{
    protected static string $resource = PemeriksaanResource::class;

    protected static ?string $title = 'Input Pemeriksaan';

    public function getBreadcrumb(): string
    {
        return 'Input Pemeriksaan';
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
