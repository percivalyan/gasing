<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventBatch;
use Illuminate\Support\Str;

class EventBatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $years = [2022, 2023, 2024, 2025];
        $phases = ['Tahap 1', 'Tahap 2'];

        foreach ($years as $year) {
            foreach ($phases as $phase) {
                EventBatch::create([
                    'id' => (string) Str::uuid(), // Optional, model boot generates UUID too
                    'event_year' => $year,
                    'event_phase' => $phase,
                ]);
            }
        }
    }
}
