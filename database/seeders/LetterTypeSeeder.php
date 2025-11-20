<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LetterType;

class LetterTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $letterTypes = [
            ['id' => 1, 'subject' => 'Surat Keputusan', 'code' => '01'],
            ['id' => 2, 'subject' => 'Surat Undangan', 'code' => '02'],
            ['id' => 3, 'subject' => 'Surat Permohonan', 'code' => '03'],
            ['id' => 4, 'subject' => 'Surat Pemberitahuan', 'code' => '04'],
            ['id' => 5, 'subject' => 'Surat Peminjaman', 'code' => '05'],
            ['id' => 6, 'subject' => 'Surat Pernyataan', 'code' => '06'],
            ['id' => 7, 'subject' => 'Surat Mandat', 'code' => '07'],
            ['id' => 8, 'subject' => 'Surat Tugas', 'code' => '08'],
            ['id' => 9, 'subject' => 'Surat Keterangan', 'code' => '09'],
            ['id' => 10, 'subject' => 'Surat Rekomendasi', 'code' => '10'],
            ['id' => 11, 'subject' => 'Surat Balasan', 'code' => '11'],
            ['id' => 12, 'subject' => 'Surat Perintah Perjalanan Dinas', 'code' => '12'],
            ['id' => 13, 'subject' => 'Sertifikat', 'code' => '13'],
            ['id' => 14, 'subject' => 'Perjanjian Kerja', 'code' => '14'],
            ['id' => 15, 'subject' => 'Surat Pengantar', 'code' => '15'],
        ];

        foreach ($letterTypes as $type) {
            LetterType::updateOrCreate(['id' => $type['id']], $type);
        }
    }
}
