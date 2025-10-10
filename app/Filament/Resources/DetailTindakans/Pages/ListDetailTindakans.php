<?php

namespace App\Filament\Resources\DetailTindakans\Pages;

use App\Filament\Resources\DetailTindakans\DetailTindakanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDetailTindakans extends ListRecords
{
    protected static string $resource = DetailTindakanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
