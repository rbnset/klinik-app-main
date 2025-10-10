<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['role_id', 'name', 'email', 'password', 'phone', 'status'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $casts = ['email_verified_at' => 'datetime'];


    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class);
    }
    public function pendaftarans(): HasMany
    {
        return $this->hasMany(Pendaftaran::class);
    }
    // dokter yang memeriksa
    public function pemeriksaans(): HasMany
    {
        return $this->hasMany(Pemeriksaan::class, 'dokter_id');
    }

    // Role and Permission
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
    public function hasRole(string|array $names): bool
    {
        $current = $this->role?->name;
        return is_array($names) ? in_array($current, $names, true) : $current === $names;
    }

    public function hasAnyRole(array $names): bool
    {
        return $this->hasRole($names);
    }
    public function pasien()
    {
        return $this->hasOne(\App\Models\Pasien::class, 'user_id'); // sesuaikan FK-nya
    }
}
