<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id' => Str::uuid(),
                'name' => 'Ryanda',
                'email' => 'ryandadeanova@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('ryandadeanova@gmail.com'),
                'role_id' => 1,
                'remember_token' => Str::random(10),
                'nik' => '1234567890123456',
                'birth_place' => 'Jayapura',
                'birth_date' => '1998-05-20',
                'gender' => 'M',
                'nip' => null,
                'expertise_field' => null,
                'last_education' => null,
                'whatsapp_number' => null,
                'address' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
