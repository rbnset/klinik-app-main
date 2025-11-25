<?php

namespace App\Policies;

use App\Models\User;

class DashboardPolicy
{
    /**
     * Determine whether the user can view the dashboard.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function view(User $user): bool
    {
        // Hanya admin, petugas, dan pemilik yang boleh mengakses
        return in_array($user->role?->name, ['admin', 'petugas', 'pemilik']);
    }
}
