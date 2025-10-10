<?php

namespace App\Policies;

use App\Models\{RekamMedis, User};
use App\Policies\Concerns\HandlesRoles;

class RekamMedisPolicy
{
    use HandlesRoles;

    public function viewAny(User $user): bool
    {
        // dokter, bidan bisa; pasien juga bisa (nanti di-scope hanya miliknya)
        return $this->is($user, ['dokter', 'bidan', 'pasien']);
    }

    public function view(User $user, RekamMedis $rm): bool
    {
        if ($this->is($user, ['dokter', 'bidan'])) return true;

        if ($user->hasRole('pasien')) {
            // pastikan RekamMedis punya pasien_id
            return $rm->pasien_id === optional($user->pasien)->id;
        }

        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $this->is($user, ['dokter', 'bidan']);
    }

    public function update(User $user, RekamMedis $rm): bool
    {
        return $this->is($user, ['dokter', 'bidan']);
    }

    public function delete(User $user, RekamMedis $rm): bool
    {
        return $this->is($user, ['dokter', 'bidan']);
    }
}
