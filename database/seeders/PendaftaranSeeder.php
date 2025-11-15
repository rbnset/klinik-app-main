<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Jadwal;

class PendaftaranSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada jadwal default
        $jadwal = Jadwal::first() ?? Jadwal::create([
            'hari' => 'Senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'tenaga_medis_id' => 1, // pastikan data tenaga medis ada
        ]);

        // ================================
        // 1. PASIEN YANG PUNYA AKUN
        // ================================
        $userPasien = User::create([
            'name' => 'Pasien Akun',
            'email' => 'pasienakun@example.com',
            'password' => bcrypt('password'),
            'role_id' => 3, // role pasien
        ]);

        $pasienDenganAkun = Pasien::create([
            'user_id' => $userPasien->id,
            'nik' => '3276010101010001',
            'nama_pasien' => 'Pasien Dengan Akun',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1995-02-02',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Merdeka No. 1',
            'no_telp' => '081234567890',
            'golongan_darah' => 'A',
            'agama' => 'Islam',
            'status_perkawinan' => 'Belum Kawin',
            'pekerjaan' => 'Karyawan',
            'nama_penanggung_jawab' => 'Penanggung Jawab 1',
            'no_telp_penanggung_jawab' => '0811111111',
        ]);

        // Pasien dengan akun → Poli Umum, Dokter
        Pendaftaran::create([
            'pasien_id' => $pasienDenganAkun->id,
            'user_id' => $userPasien->id,
            'jadwal_id' => $jadwal->id,
            'poli_tujuan' => 'Poli Umum',
            'tenaga_medis_tujuan' => 'Dokter',
            'jenis_pelayanan' => 'umum',
            'keluhan' => 'Keluhan pasien dengan akun',
            'status' => 'menunggu',
        ]);

        // ================================
        // 2. PASIEN TANPA AKUN (4 ORANG)
        // ================================
        for ($i = 1; $i <= 4; $i++) {

            $pasien = Pasien::create([
                'user_id' => null,
                'nik' => '32760101010100' . $i,
                'nama_pasien' => "Pasien Tanpa Akun $i",
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => "1990-01-0$i",
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => "Alamat Pasien $i",
                'no_telp' => "0812345678$i",
                'golongan_darah' => 'A',
                'agama' => 'Islam',
                'status_perkawinan' => 'Belum Kawin',
                'pekerjaan' => 'Tidak Bekerja',
                'nama_penanggung_jawab' => "PJ Pasien $i",
                'no_telp_penanggung_jawab' => "081111111$i",
            ]);

            // Aturan:
            // Pasien tanpa akun 1 & 2 → Poli Umum, Dokter
            // Pasien tanpa akun 3 & 4 → Poli Kandungan, Bidan

            if ($i <= 2) {
                $poli = 'Poli Umum';
                $tenaga = 'Dokter';
            } else {
                $poli = 'Poli Kandungan';
                $tenaga = 'Bidan';
            }

            Pendaftaran::create([
                'pasien_id' => $pasien->id,
                'user_id' => null,
                'jadwal_id' => $jadwal->id,
                'poli_tujuan' => $poli,
                'tenaga_medis_tujuan' => $tenaga,
                'jenis_pelayanan' => 'umum',
                'keluhan' => "Keluhan pasien tanpa akun $i",
                'status' => 'menunggu',
            ]);
        }
    }
}
