<?php

namespace App\Filament\Widgets;

use App\Models\Pendaftaran;
use App\Models\Pemeriksaan;
use Filament\Widgets\ChartWidget;

class DashboardChart extends ChartWidget
{
    

    protected ?string $heading = 'Jumlah Pendaftar & Pemeriksaan per Bulan';

    protected function getType(): string
    {
        return 'line'; // tipe chart
    }

    protected function getData(): array
    {
        $labels = [];
        $pendaftaranData = [];
        $pemeriksaanData = [];

        // Ambil 6 bulan terakhir
        $months = collect(range(0, 5))->map(fn($i) => now()->subMonths($i))->reverse();

        foreach ($months as $month) {
            $labels[] = $month->format('M Y');

            // Jumlah pendaftaran bulan ini
            $pendaftaranCount = Pendaftaran::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $pendaftaranData[] = $pendaftaranCount;

            // Jumlah pemeriksaan bulan ini
            $pemeriksaanCount = Pemeriksaan::whereYear('tanggal_periksa', $month->year)
                ->whereMonth('tanggal_periksa', $month->month)
                ->count();
            $pemeriksaanData[] = $pemeriksaanCount;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Pendaftar',
                    'data' => $pendaftaranData,
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                ],
                [
                    'label' => 'Jumlah Pemeriksaan',
                    'data' => $pemeriksaanData,
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                ],
            ],
        ];
    }

    // Batasi widget hanya untuk pemilik
    public static function getWidgets(): array
    {
        if (auth()->user()->hasRole('pemilik')) {
            return [
                DashboardChart::class,
            ];
        }

        return [];
    }

    protected function authorizeAccess(): void
    {
    $this->authorize('view', auth()->user());
    }

}
