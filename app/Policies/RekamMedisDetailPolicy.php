<?php

namespace App\Policies;

use App\Models\{RekamMedisDetail, User};
use App\Policies\Concerns\HandlesRoles;

class RekamMedisDetailPolicy
{
    use HandlesRoles;

    public function viewAny(User $user): bool
    {
        return $this->is($user, ['dokter', 'bidan']);
    }

    public function view(User $user, RekamMedisDetail $detail): bool
    {
        // hanya dokter/bidan/admin
        return $this->is($user, ['dokter', 'bidan']);
    }

    public function create(User $user): bool
    {
        return $this->is($user, ['dokter', 'bidan']);
    }

    public function update(User $user, RekamMedisDetail $detail): bool
    {
        return $this->is($user, ['dokter', 'bidan']);
    }

    public function delete(User $user, RekamMedisDetail $detail): bool
    {
        return $this->is($user, ['dokter', 'bidan']);
    }
}
