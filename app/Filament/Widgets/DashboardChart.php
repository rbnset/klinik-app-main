<?php

namespace App\Filament\Widgets;

use App\Models\Pendaftaran;
use App\Models\Pemeriksaan;
use Filament\Widgets\ChartWidget;

class DashboardChart extends ChartWidget
{
    protected ?string $heading = 'Jumlah Pendaftar & Pemeriksaan per Bulan';

    /**
     * Hanya role selain pasien yang bisa melihat widget ini.
     * (Sesuaikan daftar role kalau perlu.)
     */
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

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $labels           = [];
        $pendaftaranData  = [];
        $pemeriksaanData  = [];

        // Ambil 6 bulan terakhir
        $months = collect(range(0, 5))
            ->map(fn($i) => now()->subMonths($i))
            ->reverse();

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
            'labels'   => $labels,
            'datasets' => [
                [
                    'label'           => 'Jumlah Pendaftar',
                    'data'            => $pendaftaranData,
                    'borderColor'     => 'rgba(54, 162, 235, 1)',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                ],
                [
                    'label'           => 'Jumlah Pemeriksaan',
                    'data'            => $pemeriksaanData,
                    'borderColor'     => 'rgba(255, 99, 132, 1)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                ],
            ],
        ];
    }
}
