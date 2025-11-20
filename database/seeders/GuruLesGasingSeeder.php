<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GuruLesGasingSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk guru les gasing asal Papua.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id' => Str::uuid(),
                'name' => 'Yulianus Wonda',
                'email' => 'yulianus.wonda@gasing.id',
                'email_verified_at' => now(),
                'password' => Hash::make('yulianus.wonda@gasing.id'),
                'role_id' => 4, // Guru Les Gasing
                'remember_token' => Str::random(10),
                'nik' => '9201012301980001',
                'birth_place' => 'Wamena',
                'birth_date' => '1989-01-23',
                'gender' => 'M',
                'nip' => 'GAS-2025-001',
                'expertise_field' => 'Matematika Gasing',
                'last_education' => 'S1 Pendidikan Matematika',
                'whatsapp_number' => '+6281234567890',
                'address' => 'Jl. Trikora, Distrik Wamena, Kabupaten Jayawijaya, Papua Pegunungan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Maria Magal',
                'email' => 'maria.magal@gasing.id',
                'email_verified_at' => now(),
                'password' => Hash::make('mariamagal123'),
                'role_id' => 4, // Guru Les Gasing
                'remember_token' => Str::random(10),
                'nik' => '9202121502910002',
                'birth_place' => 'Merauke',
                'birth_date' => '1991-02-15',
                'gender' => 'F',
                'nip' => 'GAS-2025-002',
                'expertise_field' => 'Bahasa Indonesia',
                'last_education' => 'S1 Pendidikan Bahasa Indonesia',
                'whatsapp_number' => '+6281398765432',
                'address' => 'Kampung Wasur, Distrik Merauke, Papua Selatan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
