<?php

namespace App\Filament\Resources\Tindakans\Pages;

use App\Filament\Resources\Tindakans\TindakanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTindakan extends EditRecord
{
    protected static string $resource = TindakanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
