<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'pasien_id',
        'user_id',
        'jadwal_id',
        'tanggal_kunjungan',       // ⬅️ tambah ini
        'poli_tujuan',
        'tenaga_medis_tujuan',
        'jenis_pelayanan',
        'keluhan',
        'status',
        'catatan',
    ];

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jadwal(): BelongsTo
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
