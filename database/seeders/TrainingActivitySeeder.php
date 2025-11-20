<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TrainingActivitySeeder extends Seeder
{
    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        $activities = [
            'Pretest',
            'Pengenalan Bilangan',
            'Penjumlahan',
            'Pengurangan',
            'Perkalian',
            'Pembagian',
            'Pasangan 10',
            'Monitoring & Evaluasi',
            'Diagnosa',
            'Posttest',
        ];

        foreach ($activities as $index => $name) {
            DB::table('training_activities')->insert([
                'id' => Str::uuid(),
                'name' => $name,
                'description' => "Kegiatan hari ke-" . ($index + 1) . " dalam pelatihan Gasing.",
                'day_number' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
