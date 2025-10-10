<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PemeriksaanDiagnosa extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaan_diagnosa';
    protected $fillable = ['pemeriksaan_id', 'diagnosa_id', 'jenis'];

    public function pemeriksaan(): BelongsTo
    {
        return $this->belongsTo(Pemeriksaan::class);
    }
    public function diagnosa(): BelongsTo
    {
        return $this->belongsTo(Diagnosa::class);
    }
}
