<?php

namespace App\Filament\Resources\Pasiens\Pages;

use Filament\Resources\Pages\Page;
use App\Filament\Resources\Pasiens\PasienResource;
use Illuminate\Support\Facades\Auth;
use App\Models\Pasien;
use BackedEnum;

class KartuPasienSaya extends Page
{
    protected static string $resource = PasienResource::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-identification';

    public ?Pasien $pasien = null;

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return auth()->check() && auth()->user()->hasRole('pasien');
    }

    public function mount(): void
    {
        $user = Auth::user();

        $this->pasien = Pasien::query()
            ->withoutGlobalScopes()
            ->where('user_id', $user->id)
            ->with([
                'rekamMedis',
                'rekamMedis.diagnosa',
                'rekamMedis.pemeriksaan',
            ])
            ->firstOrFail();
    }

    // ⚠️ HARUS PUBLIC, bukan protected
    public function render(): \Illuminate\View\View
    {
        return view('filament.resources.pasiens.pages.kartu-pasien-saya', [
            'pasien' => $this->pasien,
        ]);
    }
}
