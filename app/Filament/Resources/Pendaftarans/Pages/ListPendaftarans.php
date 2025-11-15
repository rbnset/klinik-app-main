<?php

namespace App\Filament\Resources\Pendaftarans\Pages;

use App\Filament\Resources\Pendaftarans\PendaftaranResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListPendaftarans extends ListRecords
{
    protected static string $resource = PendaftaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tombol "New Pendaftaran" hanya muncul untuk admin & petugas
            CreateAction::make()
                ->visible(fn() => Auth::check() && in_array(Auth::user()->role?->name, ['admin', 'petugas'])),
        ];
    }
}
