<?php

namespace App\Filament\Resources\Tindakans\Pages;

use App\Filament\Resources\Tindakans\TindakanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTindakan extends CreateRecord
{
    protected static string $resource = TindakanResource::class;

    protected static ?string $title = 'Input Tindakan';

    public function getBreadcrumb(): string
    {
        return 'Input Tindakan';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ambil nama role user dari relasi user->role->name
        $data['role'] = auth()->user()->role->name;

        return $data;
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
