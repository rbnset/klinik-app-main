<?php

namespace App\Filament\Resources\DetailTindakans\Pages;

use App\Filament\Resources\DetailTindakans\DetailTindakanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDetailTindakan extends CreateRecord
{
    protected static string $resource = DetailTindakanResource::class;

    protected static ?string $title = 'Input Detail Tindakan';

    public function getBreadcrumb(): string
    {
        return 'Input Detail Tindakan';
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
