<?php

namespace App\Policies;

use App\Models\{Pendaftaran, User};
use App\Policies\Concerns\HandlesRoles;

class PendaftaranPolicy
{
    use HandlesRoles;

    /**
     * Siapa yang boleh melihat semua daftar pendaftaran
     */
    public function viewAny(User $user): bool
    {
        // semua role boleh melihat, tapi nanti dibatasi scope query
        return $this->is($user, ['petugas', 'dokter', 'bidan', 'pasien', 'admin']);
    }

    /**
     * Siapa yang boleh melihat satu record pendaftaran
     */
    public function view(User $user, Pendaftaran $pendaftaran): bool
    {
        if ($this->is($user, ['petugas', 'admin'])) return true;

        if ($user->hasRole('dokter')) {
            return strtolower($pendaftaran->poli_tujuan) === 'poli umum';
        }

        if ($user->hasRole('bidan')) {
            return strtolower($pendaftaran->poli_tujuan) === 'poli kandungan';
        }

        if ($user->hasRole('pasien')) {
            return $pendaftaran->pasien_id === optional($user->pasien)->id;
        }

        return false;
    }

    /**
     * Siapa yang boleh membuat pendaftaran
     */
    public function create(User $user): bool
    {
        // pasien boleh daftar sendiri, petugas dan admin juga
        return $this->is($user, ['petugas', 'pasien', 'admin']);
    }

    /**
     * Siapa yang boleh mengedit pendaftaran
     */
    public function update(User $user, Pendaftaran $pendaftaran): bool
    {
        if ($this->is($user, ['petugas', 'admin'])) return true;

        if ($user->hasRole('dokter')) {
            return strtolower($pendaftaran->poli_tujuan) === 'poli umum';
        }

        if ($user->hasRole('bidan')) {
            return strtolower($pendaftaran->poli_tujuan) === 'poli kandungan';
        }

        if ($user->hasRole('pasien')) {
            return $pendaftaran->pasien_id === optional($user->pasien)->id;
        }

        return false;
    }

    /**
     * Siapa yang boleh menghapus pendaftaran
     */
    public function delete(User $user, Pendaftaran $pendaftaran): bool
    {
        // hanya petugas dan admin yang boleh hapus
        return $this->is($user, ['petugas', 'admin']);
    }
}
