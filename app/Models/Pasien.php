<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pasien extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nik',
        'nama_pasien',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'golongan_darah',
        'agama',
        'status_perkawinan',
        'no_telp',
        'pekerjaan',
        'nama_penanggung_jawab',
        'no_telp_penanggung_jawab',
        'role',
    ];



public function diagnosa()
{
    return $this->hasManyThrough(Diagnosa::class, Pemeriksaan::class, 'pasien_id', 'pemeriksaan_id');
}

public function pendaftaran()
{
    return $this->hasMany(Pendaftaran::class);
}


    public function pemeriksaans(): HasMany
    {
        return $this->hasMany(Pemeriksaan::class);
    }

    public function rekamMedis(): HasMany
    {
        return $this->hasMany(RekamMedis::class);
    }
    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

}
