<?php

namespace App\Filament\Resources\Tindakans\Pages;

use App\Filament\Resources\Tindakans\TindakanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTindakans extends ListRecords
{
    protected static string $resource = TindakanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
