<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\StudentEvent;
use Carbon\Carbon;

class StudentEventDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Jika ingin bersihkan folder foto & surat tugas (opsional)
        // Storage::deleteDirectory('student_events');
        // Storage::makeDirectory('student_events/photos');
        // Storage::makeDirectory('student_events/letters');

        $districts = ['Jayapura', 'Merauke', 'Wamena', 'Timika', 'Biak', 'Nabire', 'Manokwari'];
        $schools = ['SD Inpres', 'SMP Negeri', 'SMA YPK', 'SMA Advent', 'SMP Katolik', 'SD Negeri', 'SMA Negeri'];
        $dreams = ['Dokter', 'Pilot', 'Guru', 'Tentara', 'Insinyur', 'Pengusaha', 'Atlet'];
        $papuaNames = ['Yohana', 'Markus', 'Maria', 'Yanto', 'Selina', 'Kornelius', 'Anita', 'Natalius', 'Beni', 'Arjuna'];
        $lastNames = ['Wenda', 'Tabuni', 'Logo', 'Doga', 'Elopere', 'Itlay', 'Womsiwor', 'Mael', 'Kambu', 'Muari'];

        for ($i = 0; $i < 30; $i++) {
            $name = $papuaNames[array_rand($papuaNames)] . ' ' . $lastNames[array_rand($lastNames)];

            StudentEvent::create([
                'id' => Str::uuid(),
                'user_id' => null, // Jika ingin hubungkan ke user, bisa ubah di sini
                'name' => $name,
                'nik' => '99' . rand(1000000000, 9999999999), // 11-12 digit
                'birth_place' => $districts[array_rand($districts)],
                'birth_date' => Carbon::now()->subYears(rand(10, 18))->subDays(rand(0, 365)),
                'gender' => rand(0, 1) ? 'M' : 'F',
                'address' => 'Jl. ' . $districts[array_rand($districts)] . ' No. ' . rand(1, 150),
                'origin_district' => $districts[array_rand($districts)],
                'school_level' => ['SD', 'SMP', 'SMA'][rand(0, 2)],
                'whatsapp_number' => '+62' . rand(81200000000, 81999999999),
                'dream' => $dreams[array_rand($dreams)],
                'school_origin' => $schools[array_rand($schools)] . ' ' . $districts[array_rand($districts)],
                'photo' => null, // Bisa diganti 'student_events/photos/default.jpg'
                'letter_of_assignment' => null, // Bisa diganti 'student_events/letters/surat.pdf'
                'status' => ['Pending', 'Accepted', 'Rejected'][rand(0, 2)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
