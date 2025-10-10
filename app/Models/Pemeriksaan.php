<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pemeriksaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftaran_id',
        'pasien_id',
        'dokter_id',
        'tanggal_periksa',
        'keluhan_utama',
        'tinggi_badan',
        'berat_badan',
        'tekanan_darah',
        'suhu',
        'nadi',
        'respirasi',
        'status',
    ];

    protected $casts = ['tanggal_periksa' => 'datetime'];

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class);
    }
    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class);
    }
    public function dokter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }
    public function rekamMedis(): HasMany
    {
        return $this->hasMany(RekamMedis::class);
    }

    // OPSIONAL (aktif kalau kamu buat tabel pemeriksaan_diagnosa)
    public function diagnosas(): BelongsToMany
    {
        return $this->belongsToMany(Diagnosa::class, 'pemeriksaan_diagnosa')
            ->withPivot('jenis')->withTimestamps();
    }
}
