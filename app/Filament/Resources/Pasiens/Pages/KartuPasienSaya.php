<?php

namespace App\Filament\Resources\Pasiens\Pages;

use App\Filament\Resources\Pasiens\PasienResource;
use App\Models\Pasien;
use BackedEnum;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;

class KartuPasienSaya extends Page
{
    protected static string $resource = PasienResource::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-identification';

    public ?Pasien $pasien = null;

    /**
     * Sidebar navigation:
     * - Muncul hanya jika user punya relasi pasien.
     */
    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return method_exists($user, 'pasien') && $user->pasien()->exists();
    }

    public function mount(): void
    {
        $user = Auth::user();

        if (! $user) {
            abort(401);
        }

        // =======================
        // 1. USER YANG PUNYA RELASI PASIEN (PASIEN LOGIN)
        // =======================
        if (method_exists($user, 'pasien') && $user->pasien()->exists()) {
            $pasien = $user->pasien()->with([
                'rekamMedis',
                'rekamMedis.diagnosa',
                'rekamMedis.pemeriksaan',
            ])->first();

            $this->pasien = $pasien;

            return;
        }

        // =======================
        // 2. PETUGAS / ADMIN / DOKTER / BIDAN
        // =======================
        if ($user->hasAnyRole(['admin', 'petugas', 'dokter', 'bidan'])) {
            $pasienId = request()->integer('pasien_id');

            if (! $pasienId) {
                abort(404, 'Pasien belum dipilih.');
            }

            $this->pasien = Pasien::query()
                ->withoutGlobalScopes()
                ->with([
                    'rekamMedis',
                    'rekamMedis.diagnosa',
                    'rekamMedis.pemeriksaan',
                ])
                ->findOrFail($pasienId);

            return;
        }

        // =======================
        // 3. ROLE LAIN → TIDAK BOLEH AKSES
        // =======================
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }

    /**
     * View khusus untuk Filament Page ini.
     */
    public function getView(): string
    {
        return 'filament.resources.pasiens.pages.kartu-pasien-saya';
    }

    /**
     * Data yang dikirim ke view.
     */
    public function getViewData(): array
    {
        return [
            'pasien' => $this->pasien,
        ];
    }
}
