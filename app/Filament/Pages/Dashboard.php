<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;

class Dashboard extends Page
{
    // View dashboard
    protected string $view = 'filament.pages.dashboard';

    // Ikon menu Dashboard (heroicon)
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-home';

    /**
     * Menu Dashboard tampil untuk semua user yang sudah login,
     * termasuk pasien. Isi kontennya nanti dibatasi di Blade.
     */
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check();
    }
}
