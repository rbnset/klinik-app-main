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

        // 1) Roles
        DB::table('roles')->upsert([
            ['name' => 'admin',  'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'dokter', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'bidan',  'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'pasien', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'petugas', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
        ], ['name'], ['guard_name', 'updated_at']);

        $roleIds = DB::table('roles')->pluck('id', 'name');

        // 2) Users (password default: "password")
        $users = [
            ['name' => 'Admin',     'email' => 'admin@demo.test',   'role' => 'admin'],
            ['name' => 'Dr. A',     'email' => 'dokter1@demo.test', 'role' => 'dokter'],
            ['name' => 'Bidan A',   'email' => 'bidan1@demo.test',  'role' => 'bidan'],
            ['name' => 'Petugas A', 'email' => 'petugas1@demo.test', 'role' => 'petugas'],
            ['name' => 'Petugas B', 'email' => 'petugas2@demo.test', 'role' => 'petugas'],
            ['name' => 'Pasien X',  'email' => 'pasien1@demo.test', 'role' => 'pasien'],
        ];

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

            // 3) Buat/tautkan PASIEN untuk user role "pasien"
            if ($u['role'] === 'pasien') {
                // buat NIK unik agar tidak bentrok dengan unique()
                $nik = '3201' . str_pad((string)$user->id, 12, '0', STR_PAD_LEFT);

                Pasien::updateOrCreate(
                    ['user_id' => $user->id], // pastikan kolom user_id sudah ada di tabel pasiens
                    [
                        // ↓↓↓ gunakan kolom sesuai migration kamu ↓↓↓
                        'nik'               => $nik,                 // unique nullable
                        'nama_pasien'       => $user->name,         // string(25)
                        'tempat_lahir'      => 'Jakarta',           // contoh
                        'tanggal_lahir'     => '1990-01-01',        // date
                        'jenis_kelamin'     => 'Laki-laki',         // ENUM: 'Laki-laki' / 'Perempuan'
                        'golongan_darah'    => 'O',                 // nullable enum
                        'agama'             => 'Islam',             // nullable enum
                        'status_perkawinan' => 'Belum Kawin',       // nullable enum
                        'alamat'            => 'Alamat contoh',     // text nullable
                        'no_telp'           => '081234567890',      // string(13)
                        'pekerjaan'         => 'Karyawan',          // string(50) nullable
                        'nama_penanggung_jawab'       => 'Penanggung Demo',
                        'no_telp_penanggung_jawab'    => '081111111111',
                    ]
                );
            }
        }
    }
}
