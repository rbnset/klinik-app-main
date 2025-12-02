<?php

namespace App\Filament\Widgets;

use App\Models\Pasien;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsOverview extends BaseWidget
{
    protected ?string $heading = 'Ringkasan Statistik';

    // ====== POSISI & LEBAR DI DASHBOARD ======
    // Paling atas, dan full width
    protected static ?int $sort = 0;
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        $role = auth()->user()?->role?->name;

        return in_array($role, [
            'admin',
            'pemilik',
            'petugas',
            'dokter',
            'bidan',
        ]);
    }

    protected function getStats(): array
    {
        $role = auth()->user()?->role?->name;

        // ADMIN: fokus total keseluruhan
        if ($role === 'admin') {
            return [
                Stat::make('Total Pasien', number_format(Pasien::count()))
                    ->description('Semua pasien terdaftar 🩺')
                    ->descriptionIcon('heroicon-m-user-group')
                    ->color('success'),

                Stat::make(
                    'Tenaga Medis',
                    number_format(
                        User::whereHas(
                            'role',
                            fn($q) =>
                            $q->whereIn('name', ['dokter', 'bidan'])
                        )->count()
                    )
                )
                    ->description('Dokter & bidan aktif')
                    ->descriptionIcon('heroicon-m-user-group')
                    ->color('info'),

                Stat::make('Total Pendaftaran', number_format(Pendaftaran::count()))
                    ->description('Seluruh pendaftaran pasien')
                    ->descriptionIcon('heroicon-m-clipboard-document-list')
                    ->color('warning'),

                Stat::make('Total Pemeriksaan', number_format(Pemeriksaan::count()))
                    ->description('Tindakan pemeriksaan tercatat')
                    ->descriptionIcon('heroicon-m-document-check')
                    ->color('primary'),
            ];
        }

        // PEMILIK / PETUGAS / DOKTER / BIDAN: fokus hari ini
        if (in_array($role, ['pemilik', 'petugas', 'dokter', 'bidan'])) {
            $totalPasien        = Pasien::count();
            $pendaftaranHariIni = Pendaftaran::whereDate('created_at', today())->count();
            $pemeriksaanHariIni = Pemeriksaan::whereDate('tanggal_periksa', today())->count();
            $petugasTenagaMedis = User::whereHas(
                'role',
                fn($q) =>
                $q->whereIn('name', ['admin', 'pemilik', 'petugas', 'dokter', 'bidan'])
            )->count();

            return [
                Stat::make('Total Pasien', number_format($totalPasien))
                    ->description('Semua pasien terdaftar')
                    ->descriptionIcon('heroicon-m-user-group')
                    ->color('success'),

                Stat::make('Pendaftaran Hari Ini', number_format($pendaftaranHariIni))
                    ->description('Pendaftar pada tanggal hari ini')
                    ->descriptionIcon(
                        $pendaftaranHariIni > 0
                            ? 'heroicon-m-arrow-trending-up'
                            : 'heroicon-m-minus-small'
                    )
                    ->color($pendaftaranHariIni > 0 ? 'warning' : 'gray'),

                Stat::make('Pemeriksaan Hari Ini', number_format($pemeriksaanHariIni))
                    ->description('Tindakan pemeriksaan hari ini')
                    ->descriptionIcon(
                        $pemeriksaanHariIni > 0
                            ? 'heroicon-m-check-badge'
                            : 'heroicon-m-minus-small'
                    )
                    ->color($pemeriksaanHariIni > 0 ? 'primary' : 'gray'),

                Stat::make('Petugas & Tenaga Medis', number_format($petugasTenagaMedis))
                    ->description('Akun yang mengelola klinik')
                    ->descriptionIcon('heroicon-m-user-group')
                    ->color('info'),
            ];
        }

        return [];
    }
}
