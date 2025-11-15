<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Jadwal;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua user yang role-nya dokter atau bidan
        $tenagaMedis = User::whereHas('role', fn($q) =>
            $q->whereIn('name', ['dokter', 'bidan'])
        )->get();

        // Daftar hari
        $hariList = [
            'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'
        ];

        // Waktu operasional
        $mulai    = '08:00';
        $selesai  = '22:00';
        $interval = 30; // menit

        // Function pembuat slot 30 menit
        $generateSlots = function ($start, $end, $minutes) {
            $slots = [];
            $current = strtotime($start);
            $endTime = strtotime($end);

            while ($current < $endTime) {
                $next = strtotime("+{$minutes} minutes", $current);

                $slots[] = [
                    'jam_mulai'   => date('H:i', $current),
                    'jam_selesai' => date('H:i', $next),
                ];

                $current = $next;
            }

            return $slots;
        };

        $slotList = $generateSlots($mulai, $selesai, $interval);

        // ================================
        //      GENERATE UNTUK SEMUA USER
        // ================================
        foreach ($tenagaMedis as $tm) {
            foreach ($hariList as $hari) {

                $sesiKe = 1;

                foreach ($slotList as $slot) {

                    Jadwal::firstOrCreate([
                        'user_id'    => $tm->id,
                        'hari'       => $hari,
                        'jam_mulai'  => $slot['jam_mulai'],
                        'jam_selesai'=> $slot['jam_selesai'],
                    ], [
                        'keterangan' => 'Ada',    // sesuai enum
                        'sesi'       => (string) $sesiKe, // otomatis
                    ]);

                    $sesiKe++;
                }
            }
        }
    }
}
