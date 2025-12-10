<?php

namespace App\Filament\Resources\Diagnosas\Pages;

use App\Filament\Resources\Diagnosas\DiagnosaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDiagnosa extends CreateRecord
{
    protected static string $resource = DiagnosaResource::class;

    protected static ?string $title = 'Input Diagnosa';

    public function getBreadcrumb(): string
    {
        return 'Input Diagnosa';
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
