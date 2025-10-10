<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_antrian',
        'pasien_id',
        'user_id',
        'jadwal_id',
        'poli_tujuan',
        'tenaga_medis_tujuan',
        'jenis_pelayanan',
        'keluhan',
        'status',
        'catatan',
    ];

    public function pasien()

    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }


    public function pemeriksaans(): HasMany
    {
        return $this->hasMany(Pemeriksaan::class);
    }

    public function scopeStatus($q, string $status)
    {
        return $q->where('status', $status);
    }
}
