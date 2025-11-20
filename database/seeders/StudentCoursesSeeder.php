<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudentCoursesSeeder extends Seeder
{
    public function run()
    {
        $students = [
            [
                "name" => "Crismas Yose Deselfen Kogoya",
                "birth" => "Wamena, 11 Desember 2016",
                "gender" => "L",
                "address" => "Kurima",
                "origin_district" => "Jaya Wijaya",
                "SD" => "Honelama",
                "SMP" => "",
                "SMA" => "",
                "whatsapp_number" => "82188238661",
                "dream" => "Pilot",
                "fee_note" => "yellow",
                "note" => "1. Kuning hadiah",
            ],
            [
                "name" => "Resti Yanengga",
                "birth" => "Wamena, 25 November 2008",
                "gender" => "P",
                "address" => "Jln. Sinakma Abema",
                "origin_district" => "Jaya Wijaya",
                "SD" => "N 1 Wamena",
                "SMP" => "",
                "SMA" => "",
                "whatsapp_number" => "",
                "dream" => "Dokter/Polwan",
                "fee_note" => "red",
                "note" => "2. Merah coret",
            ],
            [
                "name" => "Fransischo Diso Wenda",
                "birth" => "Manokwari, 07 Mei 2013",
                "gender" => "L",
                "address" => "Autakma",
                "origin_district" => "Jaya Wijaya",
                "SD" => "Pgri Wamena",
                "SMP" => "",
                "SMA" => "",
                "whatsapp_number" => "82238782123",
                "dream" => "",
                "fee_note" => "yellow",
                "note" => "",
            ],
            [
                "name" => "Pilamo Tatyal Wenda",
                "birth" => "Wamena, 19 Maret 2016",
                "gender" => "L",
                "address" => "Autakma",
                "origin_district" => "Jaya Wijaya",
                "SD" => "Inpres Okilik",
                "SMP" => "",
                "SMA" => "",
                "whatsapp_number" => "-",
                "dream" => "",
                "fee_note" => "yellow",
                "note" => "",
            ],
            [
                "name" => "Amelia Endriani Gire",
                "birth" => "Jayapura, 12 April 2024",
                "gender" => "P",
                "address" => "Wesaput",
                "origin_district" => "Jaya Wijaya",
                "SD" => "Inpres Wesaput",
                "SMP" => "",
                "SMA" => "",
                "whatsapp_number" => "82113261175",
                "dream" => "Dokter",
                "fee_note" => "yellow",
                "note" => "",
            ],
            [
                "name" => "Abraham Anton Meage",
                "birth" => "Wamena, 18 Agustus 2015",
                "gender" => "L",
                "address" => "Sapelek",
                "origin_district" => "Jaya Wijaya",
                "SD" => "Tiranus",
                "SMP" => "",
                "SMA" => "",
                "whatsapp_number" => "85218888524",
                "dream" => "Pilot",
                "fee_note" => "yellow",
                "note" => "",
            ],
            [
                "name" => "Elani Ghe Olivia Kalolik",
                "birth" => "Napua, 13 November 2014",
                "gender" => "P",
                "address" => "Napua",
                "origin_district" => "Jaya Wijaya",
                "SD" => "YPPK Santo Yusuf",
                "SMP" => "",
                "SMA" => "",
                "whatsapp_number" => "81247566669",
                "dream" => "Pilot",
                "fee_note" => "yellow",
                "note" => "Habel Kalolik",
            ],
            [
                "name" => "Anatasia Elopere",
                "birth" => "-",
                "gender" => "-",
                "address" => "-",
                "origin_district" => "-",
                "SD" => "-",
                "SMP" => "-",
                "SMA" => "-",
                "whatsapp_number" => "-",
                "dream" => "-",
                "fee_note" => "yellow",
                "note" => "-",
            ],
            [
                "name" => "Sara Nawurekhe Wuka",
                "birth" => "Sentani, 15 Agustus 2017",
                "gender" => "P",
                "address" => "Kuloakma, Walelagama",
                "origin_district" => "Jaya Wijaya",
                "SD" => "YPPK St. Don Bosco Pugiman",
                "SMP" => "",
                "SMA" => "",
                "whatsapp_number" => "82211715272",
                "dream" => "",
                "fee_note" => "yellow",
                "note" => "10",
            ],
        ];

        foreach ($students as $student) {
            $birth = explode(', ', $student['birth']);
            $birth_place = $birth[0] ?? null;
            $birth_date = null;

            try {
                if (isset($birth[1]) && trim($birth[1]) !== '-') {
                    // ubah locale Indonesia ke Inggris
                    $birth_date = Carbon::parse(str_replace(
                        ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'],
                        ['January','February','March','April','May','June','July','August','September','October','November','December'],
                        $birth[1]
                    ))->format('Y-m-d');
                }
            } catch (\Exception $e) {
                $birth_date = null;
            }

            // fallback: isi default kalau tetap null
            if (!$birth_date) {
                $birth_date = '2000-01-01';
            }

            DB::table('student_courses')->insert([
                'id' => Str::uuid(),
                'name' => $student['name'],
                'birth_place' => $birth_place !== '-' ? $birth_place : 'Tidak diketahui',
                'birth_date' => $birth_date,
                'gender' => $student['gender'] === 'L' ? 'M' : ($student['gender'] === 'P' ? 'F' : 'M'),
                'address' => $student['address'] !== '-' ? $student['address'] : null,
                'origin_district' => $student['origin_district'] !== '-' ? $student['origin_district'] : null,
                'school_level' => $student['SMA'] ? 'SMA' : ($student['SMP'] ? 'SMP' : ($student['SD'] ? 'SD' : null)),
                'school_origin' => $student['SMA'] ?: ($student['SMP'] ?: ($student['SD'] ?: null)),
                'whatsapp_number' => $student['whatsapp_number'] !== '-' ? $student['whatsapp_number'] : null,
                'dream' => $student['dream'] !== '-' ? $student['dream'] : null,
                'fee_note' => in_array($student['fee_note'], ['yellow', 'red', 'green']) ? $student['fee_note'] : 'yellow',
                'note' => $student['note'] !== '-' ? $student['note'] : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
