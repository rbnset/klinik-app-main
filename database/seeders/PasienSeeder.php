<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pasien;

class PasienSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nik'                      => '3201123409870001',
                'nama_pasien'              => 'Dewi Lestari',
                'tempat_lahir'             => 'Bandung',
                'tanggal_lahir'            => '1991-03-12',
                'jenis_kelamin'            => 'Perempuan',
                'golongan_darah'           => 'A',
                'agama'                    => 'Islam',
                'status_perkawinan'        => 'Belum Kawin',
                'no_telp'                  => '081221334455',
                'pekerjaan'                => 'Guru',
                'nama_penanggung_jawab'    => 'Budi Santoso',
                'no_telp_penanggung_jawab' => '081234567890',
                'alamat'                   => 'Jl. Terusan Cibaduyut No. 21, Bandung',
                'role'                     => 'pasien',
            ],
            [
                'nik'                      => '3201123409870002',
                'nama_pasien'              => 'Rizky Pratama',
                'tempat_lahir'             => 'Bandung',
                'tanggal_lahir'            => '1989-07-22',
                'jenis_kelamin'            => 'Laki-laki',
                'golongan_darah'           => 'O',
                'agama'                    => 'Islam',
                'status_perkawinan'        => 'Kawin',
                'no_telp'                  => '081322334466',
                'pekerjaan'                => 'Karyawan',
                'nama_penanggung_jawab'    => 'Siti Aisyah',
                'no_telp_penanggung_jawab' => '081233445566',
                'alamat'                   => 'Jl. Kopo Permai No. 10, Bandung',
                'role'                     => 'pasien',
            ],
            [
                'nik'                      => '3201123409870003',
                'nama_pasien'              => 'Melati Sari',
                'tempat_lahir'             => 'Bandung',
                'tanggal_lahir'            => '1993-02-18',
                'jenis_kelamin'            => 'Perempuan',
                'golongan_darah'           => 'B',
                'agama'                    => 'Islam',
                'status_perkawinan'        => 'Belum Kawin',
                'no_telp'                  => '081234556677',
                'pekerjaan'                => 'Mahasiswa',
                'nama_penanggung_jawab'    => 'Andi Purnama',
                'no_telp_penanggung_jawab' => '081232323232',
                'alamat'                   => 'Jl. Soreang Indah No. 3, Bandung',
                'role'                     => 'pasien',
            ],
            [
                'nik'                      => '3201123409870004',
                'nama_pasien'              => 'Dimas Haryanto',
                'tempat_lahir'             => 'Bandung',
                'tanggal_lahir'            => '1990-11-01',
                'jenis_kelamin'            => 'Laki-laki',
                'golongan_darah'           => 'AB',
                'agama'                    => 'Islam',
                'status_perkawinan'        => 'Kawin',
                'no_telp'                  => '081244556677',
                'pekerjaan'                => 'Wiraswasta',
                'nama_penanggung_jawab'    => 'Rina Marlina',
                'no_telp_penanggung_jawab' => '081299887766',
                'alamat'                   => 'Jl. Cijerah No. 5, Bandung',
                'role'                     => 'pasien',
            ],
        ];

        foreach ($data as $pasien) {
            Pasien::create($pasien);
        }
    }
}
