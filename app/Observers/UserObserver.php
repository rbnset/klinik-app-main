<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Role;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
public function created(User $user): void
{
    // Set role pasien
    $pasienRole = Role::where('name', 'pasien')->first();
    if ($pasienRole && !$user->role_id) {
        $user->role_id = $pasienRole->id;
        $user->save();
    }

    // Buat record pasien terkait
    if ($user->role_id === $pasienRole->id) {
        Pasien::create([
            'user_id' => $user->id,
            'nama_pasien' => $user->name,
            // bisa tambahkan default lain jika perlu
        ]);
    }
}
}
