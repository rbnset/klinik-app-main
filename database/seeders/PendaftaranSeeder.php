<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Jadwal;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class PendaftaranSeeder extends Seeder
{
    public function run(): void
    {
        // =====================================================
        // 1. Ambil TENAGA MEDIS (Dokter & Bidan)
        // =====================================================
        $dokter = User::whereHas('role', fn($q) => $q->where('name', 'dokter'))->first();
        $bidan  = User::whereHas('role', fn($q) => $q->where('name', 'bidan'))->first();

        if (! $dokter || ! $bidan) {
            $this->command?->warn("Butuh minimal 1 dokter dan 1 bidan.");
            return;
        }

        // =====================================================
        // 2. Ambil atau buat jadwal default
        // =====================================================
        $jadwal = Jadwal::first() ?? Jadwal::create([
            'user_id'     => $dokter->id,
            'hari'        => 'senin',
            'jam_mulai'   => '08:00',
            'jam_selesai' => '08:30',
            'keterangan'  => 'Jadwal pemeriksaan pagi',
            'sesi'        => '1',
        ]);

        // =====================================================
        // 3. PASIEN DENGAN AKUN → ke Dokter
        // =====================================================
        $userAkun = User::create([
            'name'     => 'Rina Anggraini',
            'email'    => 'rina.anggraini@example.com',
            'password' => Hash::make('password'),
        ]);

        $pasienAkun = Pasien::create([
            'user_id'                  => $userAkun->id,
            'nik'                      => '3276011203900001',
            'nama_pasien'              => 'Rina Anggraini',
            'tempat_lahir'             => 'Bandung',
            'tanggal_lahir'            => '1990-03-12',
            'jenis_kelamin'            => 'Perempuan',
            'alamat'                   => 'Jl. Kopo No. 12, Bandung',
            'no_telp'                  => '081223344556',
            'golongan_darah'           => 'A',
            'agama'                    => 'Islam',
            'status_perkawinan'        => 'Belum Kawin',
            'pekerjaan'                => 'Karyawan Swasta',
            'nama_penanggung_jawab'    => 'Ibu Rina',
            'no_telp_penanggung_jawab' => '081298765432',
        ]);

        Pendaftaran::create([
            'pasien_id'            => $pasienAkun->id,
            'user_id'              => $dokter->id,
            'jadwal_id'            => $jadwal->id,
            'tanggal_kunjungan'    => Carbon::now()->addDay()->toDateString(),
            'poli_tujuan'          => 'Poli Umum',
            'tenaga_medis_tujuan'  => 'Dokter',
            'jenis_pelayanan'      => 'umum',
            'keluhan'              => 'Demam tinggi sejak dua hari terakhir.',
            'status'               => 'menunggu',
        ]);

        // =====================================================
        // 4. PASIEN TANPA AKUN (4 pasien)
        // =====================================================
        $dataPasien = [
            [
                'nama'        => 'Dedi Firmansyah',
                'keluhan'     => 'Batuk berdahak dan flu.',
                'poli'        => 'Poli Umum',
                'tenaga'      => 'Dokter',
                'medis'       => $dokter,
            ],
            [
                'nama'        => 'Siti Maryati',
                'keluhan'     => 'Sakit kepala dan badan pegal.',
                'poli'        => 'Poli Umum',
                'tenaga'      => 'Dokter',
                'medis'       => $dokter,
            ],
            [
                'nama'        => 'Nina Rahmawati',
                'keluhan'     => 'Nyeri perut bagian bawah & keputihan.',
                'poli'        => 'Poli Kandungan',
                'tenaga'      => 'Bidan',
                'medis'       => $bidan,
            ],
            [
                'nama'        => 'Lisa Purnamasari',
                'keluhan'     => 'Haid tidak teratur disertai nyeri.',
                'poli'        => 'Poli Kandungan',
                'tenaga'      => 'Bidan',
                'medis'       => $bidan,
            ],
        ];

        $tanggal = Carbon::now()->addDays(2);

        foreach ($dataPasien as $i => $d) {

            $pasien = Pasien::create([
                'user_id'                  => null,
                'nik'                      => '32760188010000' . ($i + 1),
                'nama_pasien'              => $d['nama'],
                'tempat_lahir'             => 'Bandung',
                'tanggal_lahir'            => '1990-01-0' . ($i + 1),
                'jenis_kelamin'            => 'Laki-laki',
                'alamat'                   => 'Bandung',
                'no_telp'                  => '0812345566' . ($i + 1),
                'golongan_darah'           => 'O',
                'agama'                    => 'Islam',
                'status_perkawinan'        => 'Belum Kawin',
                'pekerjaan'                => 'Tidak Bekerja',
                'nama_penanggung_jawab'    => 'Keluarga',     // aman & pendek
                'no_telp_penanggung_jawab' => '08129911223' . ($i + 1),
            ]);

            Pendaftaran::create([
                'pasien_id'            => $pasien->id,
                'user_id'              => $d['medis']->id,
                'jadwal_id'            => $jadwal->id,
                'tanggal_kunjungan'    => $tanggal->copy()->addDays($i)->toDateString(),
                'poli_tujuan'          => $d['poli'],
                'tenaga_medis_tujuan'  => $d['tenaga'],
                'jenis_pelayanan'      => 'umum',
                'keluhan'              => $d['keluhan'],
                'status'               => 'menunggu',
            ]);
        }

        $this->command?->info('PendaftaranSeeder selesai tanpa error.');
    }
}
