<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        DB::table('roles')->upsert([
            ['name' => 'admin',   'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'dokter',  'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'bidan',   'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'pasien',  'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'petugas', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'pemilik', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
        ], ['name'], ['guard_name', 'updated_at']);
    }
}
