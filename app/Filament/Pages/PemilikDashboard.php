<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\PasienTableWidget;
use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class PemilikDashboard extends Page
{
    /**
     * View harus NON-STATIC
     */
    protected string $view = 'filament.pages.pemilik-dashboard';

    /**
     * Label navigasi
     */
    protected static ?string $navigationLabel = 'Dashboard Pemilik';

    /**
     * Ikon — tipe dari Filament: BackedEnum|string|null
     */
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-home';

    /**
     * Group navigasi — tipe dari Filament: UnitEnum|string|null
     */
    protected static UnitEnum|string|null $navigationGroup = 'Dashboard';

    /**
     * Urutan menu
     */
    protected static ?int $navigationSort = 10;

    /**
     * Slug URL
     */
    protected static ?string $slug = 'pemilik-dashboard';

    /**
     * WAJIB PUBLIC (bukan protected)
     * Digunakan Filament untuk menentukan apakah page muncul di sidebar
     */
    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        return method_exists($user, 'hasRole') && $user->hasRole('pemilik');
    }

    /**
     * Non-static: daftar widget yang akan ditampilkan di footer halaman.
     */
    public function getFooterWidgets(): array
    {
        return [
            PasienTableWidget::class,
        ];
    }

    /**
     * Non-static: atur lebar/kolom widget footer (1 berarti full-width).
     */
    public function getFooterWidgetsColumns(): int|array
    {
        return 1;
    }
}
