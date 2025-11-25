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
        // =====================================================
        // 1. Ambil TENAGA MEDIS: dokter & bidan (bukan admin)
        // =====================================================
        $dokter = User::whereHas('role', function ($q) {
            $q->where('name', 'dokter');
        })->first();

        $bidan = User::whereHas('role', function ($q) {
            $q->where('name', 'bidan');
        })->first();

        if (! $dokter || ! $bidan) {
            $this->command?->warn(
                "⚠ PendaftaranSeeder membutuhkan minimal:\n" .
                    "- 1 user dengan role 'dokter'\n" .
                    "- 1 user dengan role 'bidan'\n" .
                    "Silakan buat terlebih dahulu user tenaga medis (dokter & bidan)."
            );

            return;
        }

        // =====================================================
        // 2. Ambil atau buat JADWAL DEFAULT
        //    (boleh 1 dulu, dihubungkan ke dokter)
        // =====================================================
        $jadwal = Jadwal::first();

        if (! $jadwal) {
            $jadwal = Jadwal::create([
                'user_id'     => $dokter->id, // jadwal ini milik dokter
                'hari'        => 'senin',
                'jam_mulai'   => '08:00',
                'jam_selesai' => '08:30',
                'keterangan'  => 'Praktek pagi',
                'sesi'        => '1',
            ]);
        }

        // =====================================================
        // 3. Tentukan TANGGAL KUNJUNGAN
        // =====================================================

        // Pasien dengan akun → dijadwalkan besok
        $tanggalUntukPasienDenganAkun = Carbon::now()
            ->addDay()
            ->toDateString();

        // Pasien tanpa akun → 4 hari berturut-turut setelahnya
        $baseTanggalTanpaAkun = Carbon::now()->addDays(2);

        // =====================================================
        // 4. Contoh data: PASIEN DENGAN AKUN → ke DOKTER (Poli Umum)
        // =====================================================
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
            'alamat'                   => 'Jl. Merdeka No. 1, Bandung',
            'no_telp'                  => '081234567890',
            'golongan_darah'           => 'A',
            'agama'                    => 'Islam',
            'status_perkawinan'        => 'Belum Kawin',
            'pekerjaan'                => 'Karyawan Swasta',
            'nama_penanggung_jawab'    => 'Penanggung Jawab 1',
            'no_telp_penanggung_jawab' => '0811111111',
        ]);

        Pendaftaran::create([
            'pasien_id'            => $pasienDenganAkun->id,
            'user_id'              => $dokter->id,          // ✅ DOKTER
            'jadwal_id'            => $jadwal->id,
            'tanggal_kunjungan'    => $tanggalUntukPasienDenganAkun,
            'poli_tujuan'          => 'Poli Umum',         // ✅ Poli Umum
            'tenaga_medis_tujuan'  => 'Dokter',            // ✅ Dokter
            'jenis_pelayanan'      => 'umum',
            'keluhan'              => 'Demam sejak dua hari terakhir disertai batuk kering dan pusing.',
            'status'               => 'menunggu',
        ]);

        // =====================================================
        // 5. Contoh data: PASIEN TANPA AKUN (4 ORANG)
        //    - 2 pertama → Poli Umum (Dokter)
        //    - 2 berikutnya → Poli Kandungan (Bidan)
        // =====================================================
        for ($i = 1; $i <= 4; $i++) {

            $pasien = Pasien::create([
                'user_id'                  => null,
                'nik'                      => '32760101010100' . $i,
                'nama_pasien'              => "Pasien Tanpa Akun {$i}",
                'tempat_lahir'             => 'Bandung',
                'tanggal_lahir'            => "1990-01-0{$i}",
                'jenis_kelamin'            => 'Laki-laki',
                'alamat'                   => "Alamat Pasien {$i}, Bandung",
                'no_telp'                  => "0812345678{$i}",
                'golongan_darah'           => 'A',
                'agama'                    => 'Islam',
                'status_perkawinan'        => 'Belum Kawin',
                'pekerjaan'                => 'Tidak Bekerja',
                'nama_penanggung_jawab'    => "Penanggung Jawab Pasien {$i}",
                'no_telp_penanggung_jawab' => "081111111{$i}",
            ]);

            // ================================
            // Tentukan Poli & Tenaga Medis
            // ================================
            if ($i <= 2) {
                // 1 & 2 → Poli Umum, Dokter
                $poli               = 'Poli Umum';
                $tenagaMedisTujuan  = 'Dokter';
                $targetTenagaMedis  = $dokter;  // ✅ PASTI user role dokter
            } else {
                // 3 & 4 → Poli Kandungan, Bidan
                $poli               = 'Poli Kandungan';
                $tenagaMedisTujuan  = 'Bidan';
                $targetTenagaMedis  = $bidan;   // ✅ PASTI user role bidan
            }

            // ================================
            // Keluhan disesuaikan dengan poli
            // ================================
            if ($poli === 'Poli Umum') {
                if ($i === 1) {
                    $keluhan = 'Batuk berdahak, pilek, dan tenggorokan terasa sakit sejak tiga hari lalu.';
                } elseif ($i === 2) {
                    $keluhan = 'Sakit kepala, badan pegal-pegal, dan merasa mudah lelah setelah beraktivitas.';
                } else {
                    $keluhan = 'Keluhan umum berupa pusing dan nyeri ringan di badan.';
                }
            } else { // Poli Kandungan
                if ($i === 3) {
                    $keluhan = 'Nyeri perut bagian bawah dan keputihan yang mengganggu sejak beberapa hari terakhir.';
                } elseif ($i === 4) {
                    $keluhan = 'Siklus haid tidak teratur disertai nyeri hebat saat haid.';
                } else {
                    $keluhan = 'Keluhan terkait nyeri perut bagian bawah dan gangguan siklus haid.';
                }
            }

            $tanggalKunjungan = $baseTanggalTanpaAkun
                ->copy()
                ->addDays($i - 1)
                ->toDateString();

            Pendaftaran::create([
                'pasien_id'            => $pasien->id,
                'user_id'              => $targetTenagaMedis->id, // ✅ SELALU dokter/bidan
                'jadwal_id'            => $jadwal->id,
                'tanggal_kunjungan'    => $tanggalKunjungan,
                'poli_tujuan'          => $poli,
                'tenaga_medis_tujuan'  => $tenagaMedisTujuan,
                'jenis_pelayanan'      => 'umum',
                'keluhan'              => $keluhan,
                'status'               => 'menunggu',
            ]);
        }

        $this->command?->info('✅ PendaftaranSeeder berhasil dijalankan. Semua pendaftaran diarahkan hanya ke dokter & bidan (tanpa admin).');
    }
}
