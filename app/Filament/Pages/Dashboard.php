<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DashboardChart;
use App\Filament\Widgets\DashboardStatsOverview;
use App\Filament\Widgets\PasienUserChart;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    // Label sidebar
    protected static ?string $navigationLabel = 'Beranda';

    // Judul halaman dashboard
    protected static ?string $title = 'Beranda';

    // Ikon
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-home';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check();
    }

    /**
     * ⛔ Tidak pakai widget sama sekali
     */
    public function getWidgets(): array
    {
        return [];
    }

    /**
     * Grid kolom
     */
    public function getColumns(): array|int
    {
        return [
            'sm' => 1,
            'md' => 1,
            'lg' => 1,
        ];
    }
}
