<?php

namespace Database\Seeders;

use App\Models\Obat;
use Illuminate\Database\Seeder;

class ObatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = [
            ['nama_obat' => 'Paracetamol', 'kemasan' => 'Strip 10 tablet', 'harga' => 12000, 'stok' => 25],
            ['nama_obat' => 'Amoxicillin', 'kemasan' => 'Strip 10 kapsul', 'harga' => 18000, 'stok' => 8],
            ['nama_obat' => 'Vitamin C', 'kemasan' => 'Botol 30 tablet', 'harga' => 30000, 'stok' => 3],
        ];

        foreach ($rows as $row) {
            Obat::query()->updateOrCreate(
                ['nama_obat' => $row['nama_obat']],
                $row
            );
        }
    }
}
