<?php

namespace App\Filament\Resources\RekamMedisDetails\Pages;

use App\Filament\Resources\RekamMedisDetails\RekamMedisDetailResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRekamMedisDetail extends EditRecord
{
    protected static string $resource = RekamMedisDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
