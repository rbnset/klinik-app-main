<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pasien;

class PasienSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 4; $i++) {
            Pasien::create([
                'nik' => '32760' . rand(10000000, 99999999),
                'nama_pasien' => "Pasien $i",
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1990-01-0' . $i,
                'jenis_kelamin' => $i % 2 == 0 ? 'Laki-laki' : 'Perempuan',
                'golongan_darah' => 'O',
                'agama' => 'Islam',
                'status_perkawinan' => 'Belum Kawin',
                'no_telp' => '08123456' . rand(1000, 9999),
                'pekerjaan' => 'Karyawan',
                'nama_penanggung_jawab' => "Penanggung $i",
                'no_telp_penanggung_jawab' => '08123456' . rand(1000, 9999),
                'alamat' => "Jl. Contoh No $i",
                'role' => 'pasien',
            ]);
        }
    }
}
