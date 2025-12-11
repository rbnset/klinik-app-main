<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class WelcomeUserWidget extends Widget
{
    protected string $view = 'filament.widgets.welcome-user-widget';

    protected static ?int $sort = -10; // tampil paling atas

    public function getViewData(): array
    {
        $user = Auth::user();

        $role = $user->role->name ?? 'pengguna';

        // Ucapan berbeda untuk setiap role
        $message = match ($role) {
            // 'admin'     => 'Anda memiliki akses penuh untuk mengelola seluruh sistem.',
            // 'petugas'   => 'Silakan mulai memproses pendaftaran dan pelayanan pasien.',
            // 'dokter'    => 'Berikut adalah daftar pasien yang perlu Anda periksa hari ini.',
            // 'bidan'     => 'Berikut data pasien yang perlu mendapatkan layanan kebidanan.',
            // 'pemilik'   => 'Laporan dan statistik klinik siap ditinjau.',
            // 'pasien'    => 'Silakan melihat riwayat pemeriksaan atau melakukan pendaftaran.',
            default     => 'Selamat datang di sistem.',
        };

        return [
            'name' => $user->name,
            'role' => ucfirst($role),
            'message' => $message,
        ];
    }
}
