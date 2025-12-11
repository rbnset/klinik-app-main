<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Pasien;

class AllAccountsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        /**
         * 1. Roles
         */
        DB::table('roles')->upsert([
            ['name' => 'admin',   'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'dokter',  'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'bidan',   'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'pasien',  'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'petugas', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'pemilik', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
        ], ['name'], ['guard_name', 'updated_at']);

        $roleIds = DB::table('roles')->pluck('id', 'name');

        /**
         * 2. Users dengan nama nyata Indonesia
         * Password default: "password"
         */
        $users = [
            ['name' => 'Admin Klinik Utama', 'email' => 'admin@klinik.id', 'role' => 'admin'],

            // Dokter
            ['name' => 'dr. Aditya Pratama', 'email' => 'aditya.pratama@klinik.id', 'role' => 'dokter'],
            ['name' => 'dr. Wulandari Putri', 'email' => 'wulandari.putri@klinik.id', 'role' => 'dokter'],

            // Bidan
            ['name' => 'Bidan Rina Ayu', 'email' => 'rina.ayu@klinik.id', 'role' => 'bidan'],
            ['name' => 'Bidan Siti Rahma', 'email' => 'siti.rahma@klinik.id', 'role' => 'bidan'],

            // Petugas administrasi
            ['name' => 'Dewi Lestari', 'email' => 'dewi.lestari@klinik.id', 'role' => 'petugas'],
            ['name' => 'Fajar Ramadhan', 'email' => 'fajar.ramadhan@klinik.id', 'role' => 'petugas'],

            // Pasien
            ['name' => 'Anisa Ningrum', 'email' => 'anisa.ningrum@gmail.com', 'role' => 'pasien'],

            // Pemilik klinik
            ['name' => 'H. Budi Santoso', 'email' => 'pemilik@klinik.id', 'role' => 'pemilik'],
        ];

        /**
         * Simpan User + buat data Pasien jika role pasien
         */
        foreach ($users as $u) {
            $user = User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name'              => $u['name'],
                    'password'          => Hash::make('password'),
                    'role_id'           => $roleIds[$u['role']] ?? null,
                    'email_verified_at' => now(),
                    'remember_token'    => Str::random(10),
                ]
            );

            /**
             * 3. Data Pasien (hanya untuk user role "pasien")
             */
            if ($u['role'] === 'pasien') {

                // Buat NIK unik
                $nik = '3201' . str_pad((string) $user->id, 12, '0', STR_PAD_LEFT);

                Pasien::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nik'                      => $nik,
                        'nama_pasien'              => $user->name,
                        'tempat_lahir'             => 'Bandung',
                        'tanggal_lahir'            => '1997-05-14',
                        'jenis_kelamin'            => 'Perempuan',
                        'golongan_darah'           => 'A',
                        'agama'                    => 'Islam',
                        'status_perkawinan'        => 'Belum Kawin',
                        'alamat'                   => 'Jl. Anggrek No. 23, Bandung',
                        'no_telp'                  => '082112345678',
                        'pekerjaan'                => 'Guru',
                        'nama_penanggung_jawab'    => 'Siti Nurhayati',
                        'no_telp_penanggung_jawab' => '081323456789',
                    ]
                );
            }
        }
    }
}
