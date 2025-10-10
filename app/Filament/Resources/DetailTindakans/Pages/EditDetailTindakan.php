<?php

namespace App\Filament\Resources\DetailTindakans\Pages;

use App\Filament\Resources\DetailTindakans\DetailTindakanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDetailTindakan extends EditRecord
{
    protected static string $resource = DetailTindakanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
