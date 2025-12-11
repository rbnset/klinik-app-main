<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\LaporanKunjunganTableWidget;
use Filament\Pages\Page;
use UnitEnum;
use BackedEnum;

class LaporanKunjunganPasien extends Page
{
    protected string $view = 'filament.pages.laporan-kunjungan-pasien';

    protected static ?string $title = 'Laporan Kunjungan Pasien';

    protected static ?string $navigationLabel = 'Laporan Kunjungan';

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';

    protected static UnitEnum|string|null $navigationGroup = 'Laporan';

    protected static ?int $navigationSort = 11;

    protected static ?string $slug = 'laporan-kunjungan-pasien';

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        if (! $user) return false;

        return $user->hasRole('pemilik'); // hanya pemilik dapat melihat laporan
    }

    public function getFooterWidgets(): array
    {
        return [
            LaporanKunjunganTableWidget::class,
        ];
    }

    public function getFooterWidgetsColumns(): int|array
    {
        return 1;
    }
}
