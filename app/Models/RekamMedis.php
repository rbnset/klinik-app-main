<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RekamMedis extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pasien_id',
        'pemeriksaan_id',
        'diagnosa_id',
        'tanggal',
        'riwayat_alergi',
        'riwayat_penyakit',
        'rencana_terapi',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class);
    }

    public function pemeriksaan(): BelongsTo
    {
        return $this->belongsTo(Pemeriksaan::class);
    }

    public function diagnosa(): BelongsTo
    {
        return $this->belongsTo(Diagnosa::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(RekamMedisDetail::class);
    }
}
