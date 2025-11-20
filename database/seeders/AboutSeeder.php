<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\About;

class AboutSeeder extends Seeder
{
    public function run(): void
    {
        if (!About::exists()) {
            About::create([
                'vision' => 'Menjadi yayasan yang berperan aktif dalam meningkatkan kualitas pendidikan Papua.',
                'mission' => 'Membimbing guru dan siswa melalui metode Gasing (Gampang, Asyik, dan Menyenangkan).',
                'history' => 'Yayasan Gasing Center Papua didirikan untuk membantu peningkatan kualitas pendidikan matematika di Papua.',
            ]);
        }
    }
}
