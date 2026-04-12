<?php

namespace Database\Seeders;

use App\Models\Poli;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $umumPoli = Poli::query()->where('nama_poli', 'Poli Umum')->first();

        User::query()->updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'nama' => 'Admin',
                'alamat' => 'Klinik Pusat',
                'no_ktp' => '0000000000000001',
                'no_hp' => '081200000001',
                'role' => 'admin',
                'password' => Hash::make('admin'),
                'id_poli' => null,
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'dokter@gmail.com'],
            [
                'nama' => 'Dr. Dokter',
                'alamat' => 'Semarang',
                'no_ktp' => '0000000000000002',
                'no_hp' => '081200000002',
                'role' => 'dokter',
                'password' => Hash::make('dokter'),
                'id_poli' => $umumPoli?->id,
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'pasien@gmail.com'],
            [
                'nama' => 'Pasien Demo',
                'alamat' => 'Semarang',
                'no_ktp' => '0000000000000003',
                'no_hp' => '081200000003',
                'no_rm' => 'RM0001',
                'role' => 'pasien',
                'password' => Hash::make('pasien'),
                'id_poli' => null,
            ]
        );
    }
}
