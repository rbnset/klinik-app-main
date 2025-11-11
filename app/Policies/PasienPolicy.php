<?php

namespace App\Policies;

use App\Models\{Pasien, User};
use App\Models\Pendaftaran;

class PasienPolicy
{
    public function viewAny(User $user): bool
    {
        // Semua role kecuali yang tidak dikenali bisa lihat daftar pasien (dengan filter di Table)
        return in_array($user->role?->name, ['admin', 'petugas', 'dokter', 'bidan', 'pasien']);
    }

    public function view(User $user, Pasien $pasien): bool
    {
        $role = $user->role?->name;

        if (in_array($role, ['admin', 'petugas'])) {
            return true;
        }

        // pasien hanya boleh melihat datanya sendiri
        if ($role === 'pasien') {
            return $pasien->id === optional($user->pasien)->id;
        }

        // dokter hanya boleh lihat pasien yang pernah daftar ke Poli Umum
        if ($role === 'dokter') {
            return Pendaftaran::where('pasien_id', $pasien->id)
                ->where('poli_tujuan', 'Poli Umum')
                ->exists();
        }

        // bidan hanya boleh lihat pasien yang pernah daftar ke Poli Kandungan
        if ($role === 'bidan') {
            return Pendaftaran::where('pasien_id', $pasien->id)
                ->where('poli_tujuan', 'Poli Kandungan')
                ->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role?->name, ['petugas', 'admin']);
    }

    public function update(User $user, Pasien $pasien): bool
    {
        $role = $user->role?->name;

        if (in_array($role, ['admin', 'petugas'])) {
            return true;
        }

        // pasien boleh update datanya sendiri
        if ($role === 'pasien') {
            return $pasien->id === optional($user->pasien)->id;
        }

        return false;
    }

    public function delete(User $user, Pasien $pasien): bool
    {
        return in_array($user->role?->name, ['admin', 'petugas']);
    }
}
