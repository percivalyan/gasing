<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Jalankan seeder kategori.
     */
    public function run(): void
    {
        $categories = [
            'Teknologi', 'Olahraga', 'Kesehatan', 'Pendidikan', 'Travel',
            'Kuliner', 'Musik', 'Film', 'Bisnis', 'Gaya Hidup',
            'Politik', 'Ekonomi', 'Sejarah', 'Fotografi', 'Fashion',
            'Otomotif', 'Lingkungan', 'Pertanian', 'Hewan', 'Kecantikan',
            'Gaming', 'Seni', 'Literasi', 'Startup', 'Keluarga',
            'Spiritual', 'Event', 'Hukum', 'Desain', 'Budaya',
            'Keuangan', 'Properti', 'Motivasi', 'Sains', 'Teknik',
            'Komedi', 'Opini', 'Pendidikan Anak', 'Astronomi', 'Teknologi AI'
        ];

        foreach ($categories as $name) {
            Category::create([
                'name' => $name,
                'image_path' => null,
            ]);
        }
    }
}
