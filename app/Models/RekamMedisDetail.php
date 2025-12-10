<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekamMedisDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'rekam_medis_id',
        'tipe',
        'deskripsi',
        'qty',
        'satuan',
        
    ];

    protected static function booted(): void
    {
        static::saving(function (self $m) {
            if (is_null($m->subtotal)) {
                $m->subtotal = (float) $m->qty * (float) $m->harga_satuan;
            }
        });
    }

    public function rekamMedis(): BelongsTo
    {
        return $this->belongsTo(RekamMedis::class);
    }

    // hanya relevan ketika tipe = 'tindakan'
    public function detailTindakans(): HasMany
    {
        return $this->hasMany(DetailTindakan::class, 'rekam_medis_detail_id');
    }

    public function isTindakan(): bool
    {
        return $this->tipe === 'tindakan';
    }
}
