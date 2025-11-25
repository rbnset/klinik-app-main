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
            ['name' => 'admin',   'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'dokter',  'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'bidan',   'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'pasien',  'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'petugas', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'pemilik', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
        ], ['name'], ['guard_name', 'updated_at']);

        $roleIds = DB::table('roles')->pluck('id', 'name');

        // 2) Users (password default: "password")
        $users = [
            ['name' => 'Admin Utama',   'email' => 'admin@demo.test',   'role' => 'admin'],
            ['name' => 'Dr. Andi',      'email' => 'dokter1@demo.test', 'role' => 'dokter'],
            ['name' => 'Dr. Budi',      'email' => 'dokter2@demo.test', 'role' => 'dokter'],
            ['name' => 'Bidan Sari',    'email' => 'bidan1@demo.test',  'role' => 'bidan'],
            ['name' => 'Bidan Rina',    'email' => 'bidan2@demo.test',  'role' => 'bidan'],
            ['name' => 'Petugas', 'email' => 'petugas1@demo.test', 'role' => 'petugas'],
            ['name' => 'Petugas', 'email' => 'petugas2@demo.test', 'role' => 'petugas'],
            ['name' => 'Pasien Demo',   'email' => 'pasien1@demo.test', 'role' => 'pasien'],
            ['name' => 'Pemilik Klinik', 'email' => 'pemilik@demo.test', 'role' => 'pemilik'],
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
                $nik = '3201' . str_pad((string) $user->id, 12, '0', STR_PAD_LEFT);

                Pasien::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nik'                      => $nik,
                        'nama_pasien'              => $user->name,
                        'tempat_lahir'             => 'Jakarta',
                        'tanggal_lahir'            => '1990-01-01',
                        'jenis_kelamin'            => 'Laki-laki',
                        'golongan_darah'           => 'O',
                        'agama'                    => 'Islam',
                        'status_perkawinan'        => 'Belum Kawin',
                        'alamat'                   => 'Jl. Melati No. 10, Jakarta',
                        'no_telp'                  => '081234567890',
                        'pekerjaan'                => 'Karyawan Swasta',
                        'nama_penanggung_jawab'    => 'Penanggung Jawab Demo',
                        'no_telp_penanggung_jawab' => '081111111111',
                    ]
                );
            }
        }
    }
}
