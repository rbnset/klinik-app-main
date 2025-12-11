<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\WelcomeUserWidget;
use BackedEnum;
use UnitEnum;

class Dashboard extends Page
{
    protected static ?string $navigationLabel = 'Beranda';
    protected static ?string $title = 'Beranda';
    protected static ?int $navigationSort = -1;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-home';

    /**
     * VIEW HARUS NON-STATIC
     */
    protected string $view = 'filament.pages.dashboard';

    public function getHeaderWidgets(): array
    {
        return [
            WelcomeUserWidget::class,
        ];
    }

    public function getFooterWidgets(): array
    {
        return [];
    }
}
