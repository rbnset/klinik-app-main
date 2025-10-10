<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tindakan extends Model
{
    use HasFactory;

    protected $fillable = [ 'nama_tindakan', 'deskripsi', 'tarif'];

    public function detailTindakans(): HasMany
    {
        return $this->hasMany(DetailTindakan::class);
    }
}
