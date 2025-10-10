<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Diagnosa extends Model
{
    use HasFactory;

    protected $fillable = [
        'pemeriksaan_id',
        'nama_diagnosa',
        'jenis_diagnosa',
        'deskripsi',
    ];

    /**
     * Relasi ke Rekam Medis (jika ada)
     */
    public function rekamMedis(): HasMany
    {
        return $this->hasMany(RekamMedis::class);
    }

    /**
     * Relasi ke Pemeriksaan (setiap diagnosa milik satu pemeriksaan)
     */
    public function pemeriksaan(): BelongsTo
    {
        return $this->belongsTo(Pemeriksaan::class);
    }
}
