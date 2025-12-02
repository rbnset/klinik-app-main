<?php

namespace App\Policies;

use App\Models\User;

class DashboardPolicy
{
    public function view(User $user): bool
    {
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
