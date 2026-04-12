<?php

namespace Database\Seeders;

use App\Models\JadwalPeriksa;
use App\Models\User;
use Illuminate\Database\Seeder;

class JadwalPeriksaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dokter = User::query()->where('role', 'dokter')->first();

        if (!$dokter) {
            return;
        }

        $rows = [
            ['hari' => 'Senin', 'jam_mulai' => '08:00:00', 'jam_selesai' => '09:00:00'],
            ['hari' => 'Rabu', 'jam_mulai' => '09:00:00', 'jam_selesai' => '10:30:00'],
            ['hari' => 'Jumat', 'jam_mulai' => '13:00:00', 'jam_selesai' => '15:00:00'],
        ];

        foreach ($rows as $row) {
            JadwalPeriksa::query()->updateOrCreate(
                [
                    'id_dokter' => $dokter->id,
                    'hari' => $row['hari'],
                    'jam_mulai' => $row['jam_mulai'],
                    'jam_selesai' => $row['jam_selesai'],
                ],
                $row
            );
        }
    }
}
