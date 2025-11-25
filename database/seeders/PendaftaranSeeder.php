<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Jadwal;
use Illuminate\Support\Carbon;

class PendaftaranSeeder extends Seeder
{
    public function run(): void
    {
        // =======================================
        // 1. Ambil tenaga medis (dokter / bidan)
        // =======================================
        $tenagaMedis = User::whereHas(
            'role',
            fn($q) =>
            $q->whereIn('name', ['dokter', 'bidan'])
        )->first();

        if (! $tenagaMedis) {
            $this->command?->warn("⚠ Tidak ada user dengan role 'dokter' atau 'bidan'. Buat dulu user-nya.");
            return;
        }

        // =======================================
        // 2. Ambil atau buat jadwal default
        // =======================================
        $jadwal = Jadwal::first();

        if (! $jadwal) {
            $jadwal = Jadwal::create([
                'user_id'     => $tenagaMedis->id,
                'hari'        => 'senin',
                'jam_mulai'   => '08:00',
                'jam_selesai' => '08:30',
                'keterangan'  => 'Ada',
                'sesi'        => '1',
            ]);
        }

        // =======================================
        // 3. TANGGAL-TANGGAL KUNJUNGAN
        // =======================================
        // Pasien dengan akun → besok
        $tanggalUntukPasienDenganAkun = Carbon::now()->addDay()->toDateString();

        // Pasien tanpa akun → 4 hari berturut-turut setelahnya
        $baseTanggalTanpaAkun = Carbon::now()->addDays(2); // mulai 2 hari dari sekarang

        // =======================================
        // 4. PASIEN DENGAN AKUN
        // =======================================
        $userPasien = User::create([
            'name'     => 'Pasien Akun',
            'email'    => 'pasienakun@example.com',
            'password' => bcrypt('password'),
        ]);

        $pasienDenganAkun = Pasien::create([
            'user_id'                  => $userPasien->id,
            'nik'                      => '3276010101010001',
            'nama_pasien'              => 'Pasien Dengan Akun',
            'tempat_lahir'             => 'Bandung',
            'tanggal_lahir'            => '1995-02-02',
            'jenis_kelamin'            => 'Laki-laki',
            'alamat'                   => 'Jl. Merdeka No. 1',
            'no_telp'                  => '081234567890',
            'golongan_darah'           => 'A',
            'agama'                    => 'Islam',
            'status_perkawinan'        => 'Belum Kawin',
            'pekerjaan'                => 'Karyawan',
            'nama_penanggung_jawab'    => 'Penanggung Jawab 1',
            'no_telp_penanggung_jawab' => '0811111111',
        ]);

        Pendaftaran::create([
            'pasien_id'            => $pasienDenganAkun->id,
            'user_id'              => $tenagaMedis->id,
            'jadwal_id'            => $jadwal->id,
            'tanggal_kunjungan'    => $tanggalUntukPasienDenganAkun,
            'poli_tujuan'          => 'Poli Umum',
            'tenaga_medis_tujuan'  => 'Dokter',
            'jenis_pelayanan'      => 'umum',
            'keluhan'              => 'Keluhan pasien dengan akun',
            'status'               => 'menunggu',
        ]);

        // =======================================
        // 5. PASIEN TANPA AKUN (4 ORANG)
        // =======================================
        for ($i = 1; $i <= 4; $i++) {

            $pasien = Pasien::create([
                'user_id'                  => null,
                'nik'                      => '32760101010100' . $i,
                'nama_pasien'              => "Pasien Tanpa Akun $i",
                'tempat_lahir'             => 'Bandung',
                'tanggal_lahir'            => "1990-01-0$i",
                'jenis_kelamin'            => 'Laki-laki',
                'alamat'                   => "Alamat Pasien $i",
                'no_telp'                  => "0812345678$i",
                'golongan_darah'           => 'A',
                'agama'                    => 'Islam',
                'status_perkawinan'        => 'Belum Kawin',
                'pekerjaan'                => 'Tidak Bekerja',
                'nama_penanggung_jawab'    => "PJ Pasien $i",
                'no_telp_penanggung_jawab' => "081111111$i",
            ]);

            // Aturan poli & tenaga medis tujuan
            if ($i <= 2) {
                $poli              = 'Poli Umum';
                $tenagaMedisTujuan = 'Dokter';
            } else {
                $poli              = 'Poli Kandungan';
                $tenagaMedisTujuan = 'Bidan';
            }

            // Set tanggal unik per pasien tanpa akun
            $tanggalKunjungan = $baseTanggalTanpaAkun->copy()->addDays($i - 1)->toDateString();

            // Untuk role bidan, coba cari user dengan role 'bidan'
            $targetTenagaMedis = $i <= 2
                ? $tenagaMedis
                : User::whereHas(
                    'role',
                    fn($q) =>
                    $q->where('name', 'bidan')
                )->first() ?? $tenagaMedis;

            Pendaftaran::create([
                'pasien_id'            => $pasien->id,
                'user_id'              => $targetTenagaMedis->id,
                'jadwal_id'            => $jadwal->id,
                'tanggal_kunjungan'    => $tanggalKunjungan,
                'poli_tujuan'          => $poli,
                'tenaga_medis_tujuan'  => $tenagaMedisTujuan,
                'jenis_pelayanan'      => 'umum',
                'keluhan'              => "Keluhan pasien tanpa akun $i",
                'status'               => 'menunggu',
            ]);
        }
    }
}
