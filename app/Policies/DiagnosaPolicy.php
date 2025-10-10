<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\HandlesRoles;

class DiagnosaPolicy
{
    use HandlesRoles;

    public function viewAny(User $user): bool
    {
        return $this->is($user, ['dokter', 'bidan']);
    }
    public function view(User $user): bool
    {
        return $this->is($user, ['dokter', 'bidan']);
    }
    public function create(User $user): bool
    {
        return $this->is($user, ['dokter', 'bidan']);
    }
    public function update(User $user): bool
    {
        return $this->is($user, ['dokter', 'bidan']);
    }
    public function delete(User $user): bool
    {
        return $this->is($user, ['dokter', 'bidan']);
    }
}
