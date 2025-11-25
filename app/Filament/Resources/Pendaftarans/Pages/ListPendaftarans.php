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
            CreateAction::make()
                ->visible(function () {
                    if (!Auth::check()) return false;

                    $role = Auth::user()->role?->name;

                    // IZINKAN: admin, petugas, pasien
                    return in_array($role, ['admin', 'petugas', 'pasien']);
                }),
        ];
    }
}
