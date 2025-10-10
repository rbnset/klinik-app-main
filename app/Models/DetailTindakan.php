<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailTindakan extends Model
{
    use HasFactory;

    protected $fillable = [
        'rekam_medis_detail_id',
        'tindakan_id',
        'qty',
        'tarif',
        'subtotal',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $m) {
            if (is_null($m->subtotal)) {
                $m->subtotal = (float) $m->qty * (float) $m->tarif;
            }
        });
    }

    public function detail(): BelongsTo
    {
        return $this->belongsTo(RekamMedisDetail::class, 'rekam_medis_detail_id');
    }

    public function tindakan(): BelongsTo
    {
        return $this->belongsTo(Tindakan::class);
    }
}
