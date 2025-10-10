<?php

namespace App\Policies;

use App\Models\{Pemeriksaan, User};
use App\Policies\Concerns\HandlesRoles;

class PemeriksaanPolicy
{
    use HandlesRoles;

    public function viewAny(User $user): bool
    {
        return $this->is($user, ['dokter', 'bidan']);
    }
    public function view(User $user, Pemeriksaan $m): bool
    {
        return $this->is($user, ['dokter', 'bidan']);
    }
    public function create(User $user): bool
    {
        return $this->is($user, ['dokter', 'bidan']);
    }
    public function update(User $user, Pemeriksaan $m): bool
    {
        return $this->is($user, ['dokter', 'bidan']);
    }
    public function delete(User $user, Pemeriksaan $m): bool
    {
        return $this->is($user, ['dokter', 'bidan']);
    }
}
