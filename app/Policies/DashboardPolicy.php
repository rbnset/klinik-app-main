<?php

namespace App\Policies;

use App\Models\User;

class DashboardPolicy
{
    /**
     * Menentukan apakah user bisa melihat dashboard.
     * Kalau logic konten sudah diatur di Blade, policy bisa dibuat longgar.
     */
    public function view(User $user): bool
    {
        // Semua role yang ada di sistem boleh buka halaman Dashboard.
        // Konten diatur di Blade berdasarkan role.
        return in_array($user->role?->name, [
            'admin',
            'pemilik',
            'petugas',
            'dokter',
            'bidan',
            'pasien',
        ]);
    }
}
