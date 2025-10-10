<?php

namespace App\Filament\Resources\Diagnosas\Pages;

use App\Filament\Resources\Diagnosas\DiagnosaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDiagnosas extends ListRecords
{
    protected static string $resource = DiagnosaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
