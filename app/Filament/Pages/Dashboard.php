<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DashboardChart;
use App\Filament\Widgets\DashboardStatsOverview;
use App\Filament\Widgets\PasienUserChart;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    // 🔥 UBAH LABEL NAVIGASI DI SIDEBAR
    protected static ?string $navigationLabel = 'Beranda';

    // Opsional → ubah judul halaman di header
    protected static ?string $title = 'Beranda';

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-home';

    /**
     * Dashboard muncul untuk semua user yang login,
     * tapi isi widget diatur di masing-masing widget (canView).
     */
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check();
    }

    /**
     * Urutan widget dashboard.
     */
    public function getWidgets(): array
    {
        return [
            DashboardStatsOverview::class,
            DashboardChart::class,
            PasienUserChart::class,
        ];
    }

    /**
     * Grid kolom dashboard.
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
