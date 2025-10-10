<?php

namespace App\Policies\Concerns;

use App\Models\User;

trait HandlesRoles
{
    protected function is(User $u, array $roles): bool
    {
        // admin boleh semuanya
        return $u->hasRole('admin') || $u->hasAnyRole($roles);
    }
}
