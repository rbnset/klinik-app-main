<?php

namespace App\Policies;

use App\Models\{DetailTindakan, User};
use App\Policies\Concerns\HandlesRoles;

class DetailTindakanPolicy
{
    use HandlesRoles;

    public function viewAny(User $user): bool
    {
        return $this->is($user, ['dokter', 'bidan']);
    }
    public function view(User $user, DetailTindakan $m): bool
    {
        return $this->is($user, ['dokter', 'bidan']);
    }
    public function create(User $user): bool
    {
        return $this->is($user, ['dokter', 'bidan']);
    }
    public function update(User $user, DetailTindakan $m): bool
    {
        return $this->is($user, ['dokter', 'bidan']);
    }
    public function delete(User $user, DetailTindakan $m): bool
    {
        return $this->is($user, ['dokter', 'bidan']);
    }
}
