<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        // ========== DATA STRUKTUR ORGANISASI ==========
        $structures = [
            'PEMBINA' => [
                ['name' => 'KILONER WENDA'],
                ['name' => 'GERMIN WENDA'],
                ['name' => 'TANUS KOGOYA'],
            ],
            'PENGAWAS' => [
                ['name' => 'TARWI KIWO'],
            ],
            'KETUA' => [
                ['name' => 'OBET MEAGE, S.Pd'],
            ],
            'SEKRETARIS' => [
                ['name' => 'AGUSTHINUS ALFRED SANYAR, S.Pd'],
                ['name' => 'ANDRIAN WAMO MATUAN, S.Pd'],
            ],
            'BENDAHARA' => [
                ['name' => 'NOPITA MEAGE, S.Pd'],
            ],
            'SEKSI – PENGEMBANGAN KOMPETENSI SAINTEK' => [
                ['name' => 'ANDREANUS YOTHA, S.Pd', 'title' => 'Koordinator'],
                ['name' => 'EVIS YOMAN, S.Pd', 'title' => 'Anggota'],
                ['name' => 'LISA IWANGGIN, S.Pd', 'title' => 'Anggota'],
                ['name' => 'LADI DIANA M. WOPARI, S.Pd', 'title' => 'Anggota'],
            ],
            'SEKSI – PENGEMBANGAN METODE GASING' => [
                ['name' => 'TONI PAGAWAK, S.Pd', 'title' => 'Koordinator'],
                ['name' => 'YULI BERTHA A. GOMBO, S.Pd', 'title' => 'Anggota'],
                ['name' => 'DESOI WANENA, S.Pd', 'title' => 'Anggota'],
                ['name' => 'PETELINA WENDA, S.Pd', 'title' => 'Anggota'],
                ['name' => 'MERIANA WETIPO, S.Pd', 'title' => 'Anggota'],
            ],
            'SEKSI – SOSIAL KEMASYARAKATAN' => [
                ['name' => 'MILETUS WENDA, S.Pd', 'title' => 'Koordinator'],
                ['name' => 'YUNUS YIKWA, S.Pd', 'title' => 'Anggota'],
                ['name' => 'MAYTENA GURIK, S.Pd', 'title' => 'Anggota'],
                ['name' => 'UTREK SUGUN, S.Pd', 'title' => 'Anggota'],
                ['name' => 'KORNELIA ONDOAPO, S.Pd', 'title' => 'Anggota'],
                ['name' => 'ROY PAWIKA, S.Sos', 'title' => 'Anggota'],
            ],
            'SEKSI – TECHNOPRENEURSHIP' => [
                ['name' => 'ANTHON WORIASI, S.Pd', 'title' => 'Koordinator'],
                ['name' => 'MAITON YOMAN, S.Pd., MM', 'title' => 'Anggota'],
                ['name' => 'NOVRINCE KIWO, S.Pd', 'title' => 'Anggota'],
                ['name' => 'MILES WENDA, S.Pd', 'title' => 'Anggota'],
                ['name' => 'YOP KANENGGA, S.Pd', 'title' => 'Anggota'],
            ],
        ];

        $order = 1;
        foreach ($structures as $position => $members) {
            $structureId = (string) Str::uuid();

            // Simpan ke tabel organization_structures
            DB::table('organization_structures')->insert([
                'id' => $structureId,
                'position' => $position,
                'order' => $order++,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $memberOrder = 1;
            foreach ($members as $member) {
                DB::table('organization_members')->insert([
                    'id' => (string) Str::uuid(),
                    'structure_id' => $structureId,
                    'name' => $member['name'],
                    'order' => $memberOrder++,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
