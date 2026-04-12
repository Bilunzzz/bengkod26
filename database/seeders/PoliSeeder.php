<?php

namespace Database\Seeders;

use App\Models\Poli;
use Illuminate\Database\Seeder;

class PoliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $poliRows = [
            ['nama_poli' => 'Poli Umum', 'keterangan' => 'Layanan pemeriksaan umum'],
            ['nama_poli' => 'Poli Gigi', 'keterangan' => 'Layanan kesehatan gigi'],
            ['nama_poli' => 'Poli Anak', 'keterangan' => 'Layanan kesehatan anak'],
        ];

        foreach ($poliRows as $row) {
            Poli::query()->updateOrCreate(
                ['nama_poli' => $row['nama_poli']],
                ['keterangan' => $row['keterangan']]
            );
        }
    }
}
