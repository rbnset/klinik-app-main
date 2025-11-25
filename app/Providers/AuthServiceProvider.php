<?php

namespace App\Providers;

use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Policies\PasienPolicy;
use App\Policies\PendaftaranPolicy;
use App\Policies\DashboardPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Pasien::class => PasienPolicy::class,
        Pendaftaran::class => PendaftaranPolicy::class,
        \App\Filament\Pages\Dashboard::class => \App\Policies\DashboardPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
