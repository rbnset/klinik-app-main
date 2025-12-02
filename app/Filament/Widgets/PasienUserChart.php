<?php

namespace App\Filament\Widgets;

use App\Models\Pasien;
use App\Models\User;
use Filament\Widgets\ChartWidget;

class PasienUserChart extends ChartWidget
{
    protected ?string $heading = 'Statistik Pasien per Bulan';

    // Setelah DashboardChart, dan 1 kolom juga
    protected static ?int $sort = 20;
    protected int | string | array $columnSpan = 1;

    public static function canView(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        $role = $user->role?->name;

        return in_array($role, [
            'admin',
            'pemilik',
            'petugas',
            'dokter',
            'bidan',
        ]);
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $labels         = [];
        $pasienData     = [];
        $userPasienData = [];
        $allData        = [];

        $months = collect(range(0, 5))
            ->map(fn($i) => now()->subMonths($i))
            ->reverse();

        foreach ($months as $month) {
            $labels[] = $month->format('M Y');

            $pasienCount = Pasien::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->whereNull('user_id')
                ->count();
            $pasienData[] = $pasienCount;

            $userCount = User::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->whereHas('role', fn($q) => $q->where('name', 'pasien'))
                ->count();
            $userPasienData[] = $userCount;

            $allData[] = $pasienCount + $userCount;
        }

        return [
            'labels'   => $labels,
            'datasets' => [
                [
                    'label'           => 'Pasien Walk-in',
                    'data'            => $pasienData,
                    'borderColor'     => 'rgba(75, 192, 192, 1)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                ],
                [
                    'label'           => 'User (Role Pasien)',
                    'data'            => $userPasienData,
                    'borderColor'     => 'rgba(255, 159, 64, 1)',
                    'backgroundColor' => 'rgba(255, 159, 64, 0.2)',
                ],
                [
                    'label'           => 'All Pasien',
                    'data'            => $allData,
                    'borderColor'     => 'rgba(54, 162, 235, 1)',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                ],
            ],
        ];
    }
}
