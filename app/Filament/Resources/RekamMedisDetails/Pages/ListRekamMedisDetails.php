<?php

namespace App\Filament\Resources\RekamMedisDetails\Pages;

use App\Filament\Resources\RekamMedisDetails\RekamMedisDetailResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRekamMedisDetails extends ListRecords
{
    protected static string $resource = RekamMedisDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
