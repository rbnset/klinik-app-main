<?php

namespace App\Filament\Resources\RekamMedisDetails\Pages;

use App\Filament\Resources\RekamMedisDetails\RekamMedisDetailResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListRekamMedisDetails extends ListRecords
{
    protected static string $resource = RekamMedisDetailResource::class;

    /**
     * Hilangkan tombol NEW untuk role admin
     */
    protected function getHeaderActions(): array
    {
        $user = auth()->user();

        // Admin: tidak ada tombol NEW
        if ($user->hasRole('admin')) {
            return [];
        }

        // Role lain: tetap bisa create
        return [
            CreateAction::make(),
        ];
    }

    /**
     * Hilangkan tombol EDIT untuk admin
     */
    protected function getTableActions(): array
    {
        $actions = parent::getTableActions();

        if (auth()->user()->hasRole('admin')) {
            // Hilangkan edit
            return collect($actions)
                ->filter(fn ($action) => $action::class !== \Filament\Tables\Actions\EditAction::class)
                ->values()
                ->all();
        }

        return $actions;
    }

    /**
     * Filter data Rekam Medis Detail berdasarkan role user.
     * (Tidak diubah sesuai permintaan)
     */
    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();
        $user = auth()->user();

        if ($user->hasRole('dokter')) {
            return $query->whereHas('rekamMedis.pendaftaran', function ($q) {
                $q->where('poli_tujuan', 'Poli Umum');
            });
        }

        if ($user->hasRole('bidan')) {
            return $query->whereHas('rekamMedis.pendaftaran', function ($q) {
                $q->where('poli_tujuan', 'Poli Kandungan');
            });
        }

        return $query;
    }
}
