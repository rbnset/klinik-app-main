<?php

namespace App\Filament\Resources\Diagnosas\Pages;

use App\Filament\Resources\Diagnosas\DiagnosaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDiagnosa extends EditRecord
{
    protected static string $resource = DiagnosaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
