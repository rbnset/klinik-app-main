<?php

namespace App\Filament\Resources\RekamMedisDetails\Pages;

use App\Filament\Resources\RekamMedisDetails\RekamMedisDetailResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListRekamMedisDetails extends ListRecords
{
    protected static string $resource = RekamMedisDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    /**
     * Filter data Rekam Medis Detail berdasarkan role user.
     */
    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();
        $user = auth()->user();

        // Jika role dokter → hanya poli umum
        if ($user->hasRole('dokter')) {
            return $query->whereHas('rekamMedis.pendaftaran', function ($q) {
                $q->where('poli_tujuan', 'Poli Umum');
            });
        }

        // Jika role bidan → hanya poli kandungan
        if ($user->hasRole('bidan')) {
            return $query->whereHas('rekamMedis.pendaftaran', function ($q) {
                $q->where('poli_tujuan', 'Poli Kandungan');
            });
        }

        // Admin / selain itu, tampilkan semua
        return $query;
    }
}
