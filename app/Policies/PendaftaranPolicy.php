<?php

namespace App\Policies;

use App\Models\{Pendaftaran, User};
use App\Policies\Concerns\HandlesRoles;

class PendaftaranPolicy
{
    use HandlesRoles;

    public function viewAny(User $user): bool
    {
        // petugas bisa; pasien juga bisa (nanti di-scope miliknya)
        return $this->is($user, ['petugas', 'pasien']);
    }

    public function view(User $user, Pendaftaran $p): bool
    {
        if ($this->is($user, ['petugas'])) return true;

        if ($user->hasRole('pasien')) {
            return $p->pasien_id === optional($user->pasien)->id;
        }

        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        // pasien boleh daftar sendiri, petugas juga boleh
        return $this->is($user, ['petugas', 'pasien']);
    }

    public function update(User $user, Pendaftaran $p): bool
    {
        // default: hanya petugas
        return $this->is($user, ['petugas']);
    }

    public function delete(User $user, Pendaftaran $p): bool
    {
        // default: hanya petugas
        return $this->is($user, ['petugas']);
    }
}
