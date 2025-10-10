<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Jadwal;

class JadwalPolicy
{
    protected function is(User $u, array $roles): bool
    {
        // admin boleh semua
        return $u->role?->name === 'admin' || in_array($u->role?->name, $roles, true);
    }

    public function viewAny(User $user): bool
    {
        // semua role di bawah boleh melihat daftar jadwal
        return $this->is($user, ['petugas', 'dokter', 'bidan']);
    }

    public function view(User $user, Jadwal $jadwal): bool
    {
        return $this->is($user, ['petugas', 'dokter', 'bidan']);
    }

    public function create(User $user): bool
    {
        // hanya admin
        return $user->role?->name === 'admin';
    }

    public function update(User $user, Jadwal $jadwal): bool
    {
        // hanya admin
        return $user->role?->name === 'admin';
    }

    public function delete(User $user, Jadwal $jadwal): bool
    {
        // hanya admin
        return $user->role?->name === 'admin';
    }

    // restore/forceDelete kalau dibutuhkan -> sama: admin saja
}
