<?php

namespace App\Policies;

use App\Models\{Pasien, User};
use App\Policies\Concerns\HandlesRoles;

class PasienPolicy
{
    use HandlesRoles;

    public function viewAny(User $user): bool
    {
        // petugas/dokter/bidan melihat data pasien; pasien juga bisa (nanti di-scope)
        return $this->is($user, ['petugas', 'dokter', 'bidan', 'pasien']);
    }

    public function view(User $user, Pasien $pasien): bool
    {
        if ($this->is($user, ['petugas', 'dokter', 'bidan'])) return true;

        if ($user->hasRole('pasien')) {
            return $pasien->id === optional($user->pasien)->id;
        }

        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        // hanya petugas (opsional: admin juga dianggap lolos via trait)
        return $this->is($user, ['petugas']);
    }

    public function update(User $user, Pasien $pasien): bool
    {
        if ($this->is($user, ['petugas'])) return true;

        // pasien boleh update profilnya sendiri (opsional)
        if ($user->hasRole('pasien')) {
            return $pasien->id === optional($user->pasien)->id;
        }

        return false;
    }

    public function delete(User $user, Pasien $pasien): bool
    {
        return $this->is($user, ['petugas']);
    }
}
