<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DashboardChart;
use App\Filament\Widgets\DashboardStatsOverview;
use App\Filament\Widgets\PasienUserChart;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'Dashboard';

    /**
     * Dashboard muncul untuk semua user yang login,
     * tapi isi widget diatur di masing-masing widget (canView).
     */
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check();
    }

    /**
     * URUTAN widget di dashboard.
     * 1) StatsOverview (card-card)
     * 2) Grafik 1
     * 3) Grafik 2
     */
    public function getWidgets(): array
    {
        return [
            DashboardStatsOverview::class, // baris pertama, full
            DashboardChart::class,         // baris kedua, kiri
            PasienUserChart::class,        // baris kedua, kanan
        ];
    }

    /**
     * Grid kolom dashboard:
     * - HP: 1 kolom
     * - md ke atas: 2 kolom
     */
    public function getColumns(): array|int
    {
        return [
            'sm' => 1,
            'md' => 2,
            'lg' => 2,
        ];
    }
}
