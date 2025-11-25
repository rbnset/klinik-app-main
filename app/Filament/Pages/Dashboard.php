<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BackedEnum;

class Dashboard extends Page
{
    // WAJIB: Jangan static! Ikuti parent class
    protected string $view = 'filament.pages.dashboard';

    // Ikon menu Dashboard (pakai string heroicon)
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-home';

    // Menu Dashboard hanya muncul untuk admin, pemilik, petugas
    public static function shouldRegisterNavigation(): bool
    {
        $role = auth()->user()?->role?->name;

        return in_array($role, ['admin', 'pemilik', 'petugas']);
    }
}
