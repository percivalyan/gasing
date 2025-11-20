<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PapuaRandomSeeder extends Seeder
{
    public function run(): void
    {
        $firstNamesMale   = ["Yonas", "Markus", "Moses", "Yosep", "Elius", "Paulus", "Andreas", "Filemon", "Lukas", "Niko", "Samuel", "Yakob", "Melki", "Isak", "Eben", "Daniel"];
        $firstNamesFemale = ["Maria", "Yohana", "Helena", "Ruth", "Martha", "Susi", "Tina", "Debora", "Ester", "Selina", "Ria", "Dina", "Agnes", "Lina", "Olyvia", "Cynthia"];

        // Gabungan marga sesuai daftar kamu
        $marga = [
            // Pegunungan Tengah
            "Wenda",
            "Tabuni",
            "Kogoya",
            "Mote",
            "Murib",
            "Logo",
            "Itlay",
            "Wanimbo",
            "Wakerkwa",
            "Telenggen",
            "Enembe",
            "Wetipo",
            "Gwijangge",
            "Magai",
            "Yogobi",
            "Doga",
            "Wandikbo",
            "Wandosa",
            "Kossay",
            "Wuka",
            "Walilo",
            "Pigai",
            "Matuan",
            // Paniai, Nabire, Mee/Moni
            "Gobai",
            "Tebay",
            "Yogi",
            "Iyai",
            "Bunai",
            "Pigome",
            "Douw",
            "Giay",
            "Edowai",
            "Wonda",
            "Pekei",
            "Goo",
            "Enumbi",
            "Obet",
            "Kayame",
            "Keiya",
            // Papua Barat
            "Mandacan",
            "Kambuaya",
            "Mandat",
            "Indou",
            "Ullo",
            "Rumainum",
            "Mandowen",
            "Krey",
            "Mandatjan",
            "Marani",
            "Rumaseb",
            "Mandosir",
            "Mandatir",
            "Karubaba",
            "Rumbrawer",
            "Dimara",
            "Waromi",
            "Fonataba",
            "Syufi",
            // Biak, Yapen, Waropen, Sarmi
            "Rumbewas",
            "Korwa",
            "Rumbiak",
            "Numberi",
            "Manibuy",
            "Ireeuw",
            "Rumbrar",
            "Sroyer",
            "Ayomi",
            "Monim",
            "Manufandu",
            "Yarangga",
            "Kafiar",
            "Kawer",
            "Worumi",
            // Selatan Papua
            "Kaize",
            "Gebze",
            "Balagaize",
            "Ndiken",
            "Tjilik",
            "Basik-basik",
            "Mahuze",
        ];

        $birthPlaces = ["Jayapura", "Wamena", "Timika", "Biak", "Manokwari", "Merauke", "Nabire"];
        $districts   = ["Kabupaten Jayawijaya", "Kabupaten Mimika", "Kabupaten Paniai", "Kabupaten Nabire", "Kabupaten Manokwari", "Kabupaten Biak Numfor"];
        $schools     = ["SMA Negeri 1 Jayapura", "SMA Yapis Wamena", "SMP YPPK Timika", "SMA Negeri 3 Manokwari", "SMP Negeri 2 Nabire", "SMA Negeri 2 Biak"];
        $dreams      = ["Dokter", "Guru", "Pilot", "Polisi", "Tentara", "Arsitek", "Insinyur", "Perawat", "Pendeta", "Atlet"];
        $expertise   = ["Matematika", "Bahasa Inggris", "Fisika", "Kimia", "Biologi", "Geografi", "Ekonomi", "Seni Musik"];
        $educations  = ["S1 Pendidikan", "S2 Pendidikan", "S1 Non-Pendidikan"];

        // Helper: buat angka acak sebagai string (aman untuk 16/18 digit)
        $randDigits = function (int $len): string {
            $s = '';
            for ($i = 0; $i < $len; $i++) {
                $s .= (string) random_int(0, 9);
            }
            return $s;
        };

        // ===== 50 STUDENT EVENTS =====
        for ($i = 0; $i < 50; $i++) {
            $gender    = random_int(0, 1) ? 'Laki-laki' : 'Perempuan';
            $firstName = $gender === 'Laki-laki'
                ? $firstNamesMale[array_rand($firstNamesMale)]
                : $firstNamesFemale[array_rand($firstNamesFemale)];
            $lastName  = $marga[array_rand($marga)];
            $name      = "{$firstName} {$lastName}";
            $birthDate = Carbon::create(random_int(2006, 2010), random_int(1, 12), random_int(1, 28));
            $slugName  = strtolower(str_replace(' ', '_', "{$firstName}_{$lastName}"));

            DB::table('student_events')->insert([
                'id'                   => (string) Str::uuid(),
                'user_id'              => null,
                'name'                 => $name,                 // <-- ditambahkan
                'nik'                  => $randDigits(16),
                'birth_place'          => $birthPlaces[array_rand($birthPlaces)],
                'birth_date'           => $birthDate->toDateString(),
                'gender'               => $gender,
                'address'              => "Jl. {$lastName} No. " . random_int(1, 120),
                'origin_district'      => $districts[array_rand($districts)],
                'school_level'         => random_int(0, 1) ? "SMP" : "SMA",
                'whatsapp_number'      => "628" . $randDigits(10),
                'dream'                => $dreams[array_rand($dreams)],
                'school_origin'        => $schools[array_rand($schools)],
                'photo'                => "papua_students/{$slugName}.jpg",
                'letter_of_assignment' => "letters/{$slugName}.pdf",
                'created_at'           => now(),
                'updated_at'           => now(),
            ]);
        }

        // ===== 25 TEACHER EVENTS =====
        for ($i = 0; $i < 25; $i++) {
            $gender    = random_int(0, 1) ? 'Laki-laki' : 'Perempuan';
            $firstName = $gender === 'Laki-laki'
                ? $firstNamesMale[array_rand($firstNamesMale)]
                : $firstNamesFemale[array_rand($firstNamesFemale)];
            $lastName  = $marga[array_rand($marga)];
            $name      = "{$firstName} {$lastName}";
            $birthDate = Carbon::create(random_int(1975, 1995), random_int(1, 12), random_int(1, 28));
            $slugName  = strtolower(str_replace(' ', '_', "{$firstName}_{$lastName}"));

            DB::table('teacher_events')->insert([
                'id'                   => (string) Str::uuid(),
                'user_id'              => null,
                'name'                 => $name,
                'birth_place'          => $birthPlaces[array_rand($birthPlaces)],
                'birth_date'           => $birthDate->toDateString(),
                'gender'               => $gender,
                'nip'                  => $randDigits(18), // string 18 digit
                'expertise_field'      => $expertise[array_rand($expertise)],
                'last_education'       => $educations[array_rand($educations)],
                'whatsapp_number'      => "628" . $randDigits(10),
                'address'              => "Jl. Pendidikan No. " . random_int(1, 120) . ", " . $birthPlaces[array_rand($birthPlaces)],
                'school_origin'        => $schools[array_rand($schools)],
                'photo'                => "papua_teachers/{$slugName}.jpg",
                'letter_of_assignment' => "letters/{$slugName}.pdf",
                'created_at'           => now(),
                'updated_at'           => now(),
            ]);
        }
    }
}
