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

        $testPatients = [
            [
                'nama' => 'Pasien 1',
                'email' => 'pasien1@gmail.com',
                'password' => 'pasien1',
                'no_rm' => 'RM0002',
                'no_ktp' => '0000000000000004',
                'no_hp' => '081200000004',
            ],
            [
                'nama' => 'Pasien 2',
                'email' => 'pasien2@gmail.com',
                'password' => 'pasien2',
                'no_rm' => 'RM0003',
                'no_ktp' => '0000000000000005',
                'no_hp' => '081200000005',
            ],
            [
                'nama' => 'Pasien 3',
                'email' => 'pasien3@gmail.com',
                'password' => 'pasien3',
                'no_rm' => 'RM0004',
                'no_ktp' => '0000000000000006',
                'no_hp' => '081200000006',
            ],
            [
                'nama' => 'Pasien 4',
                'email' => 'pasien4@gmail.com',
                'password' => 'pasien4',
                'no_rm' => 'RM0005',
                'no_ktp' => '0000000000000007',
                'no_hp' => '081200000007',
            ],
        ];

        foreach ($testPatients as $patient) {
            User::query()->updateOrCreate(
                ['email' => $patient['email']],
                [
                    'nama' => $patient['nama'],
                    'alamat' => 'Semarang',
                    'no_ktp' => $patient['no_ktp'],
                    'no_hp' => $patient['no_hp'],
                    'no_rm' => $patient['no_rm'],
                    'role' => 'pasien',
                    'password' => Hash::make($patient['password']),
                    'id_poli' => null,
                ]
            );
        }
    }
}
