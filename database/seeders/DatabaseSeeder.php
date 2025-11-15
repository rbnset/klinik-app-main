<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AllAccountsSeeder::class,
            JadwalSeeder::class,
            PasienSeeder::class,
            PendaftaranSeeder::class,
            PemeriksaanSeeder::class,
            DiagnosaSeeder::class,
            RekamMeisSeeder::class,
        ]);
    }
}
