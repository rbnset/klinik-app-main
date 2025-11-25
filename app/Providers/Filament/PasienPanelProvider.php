<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class PasienPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('pasien')
            ->path('pasien')
            ->colors([
                'primary' => Color::Amber,
            ])

            // =============================
            // RESOURCE PASIEN (BENAR)
            // =============================
            ->discoverResources(
                in: app_path('Filament/Resources/Pasiens'),
                for: 'App\Filament\Resources\Pasiens'
            )

            // =============================
            // DISCOVER CUSTOM PAGES → TIDAK ADA
            // jadi NONAKTIFKAN SAJA
            // =============================
            // ->discoverPages(
            //     in: app_path('Filament/Pasiens/Pages'),
            //     for: 'App\Filament\Pasiens\Pages'
            // )

            // =============================
            // PAGE YANG MEMANG PANEL PAGE
            // =============================
            ->pages([
                Dashboard::class,
            ])

            // =============================
            // WIDGETS
            // =============================
            ->discoverWidgets(
                in: app_path('Filament/Pasiens/Widgets'),
                for: 'App\Filament\Pasiens\Widgets'
            )
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])

            // =============================
            // MIDDLEWARE
            // =============================
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])

            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
