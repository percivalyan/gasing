<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionRoleSeeder extends Seeder
{
    public function run(): void
    {
        $data = [];

        /**
         * 1. Administrator -> full access (semua permissions)
         */
        $allPermissions = DB::table('permissions')->pluck('id');
        foreach ($allPermissions as $id) {
            $data[] = [
                'role_id'       => 1, // Administrator
                'permission_id' => $id,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        /**
         * 2. Sekretaris -> permission id 1–9
         *    (biarkan sesuai logic awal kamu, sesuaikan jika mapping id berubah)
         */
        for ($i = 1; $i <= 9; $i++) {
            $data[] = [
                'role_id'       => 2, // Sekretaris
                'permission_id' => $i,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        /**
         * 3. Guru Les Gasing -> permission id 1–1000
         *    (sesuai logic awal, asumsi id permission tidak lebih dari itu)
         */
        for ($i = 1; $i <= 1000; $i++) {
            $data[] = [
                'role_id'       => 4, // Guru Les Gasing
                'permission_id' => $i,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        /**
         * 4. Kepala Sekolah -> hanya Add Student Event & Add Teacher Event
         *
         *    Di sini saya pakai kolom 'name' di tabel permissions.
         *    Pastikan di tabel 'permissions' ada record:
         *      - name = "Add Student Event"
         *      - name = "Add Teacher Event"
         *
         *    Kalau kamu pakai kolom 'slug', ganti ke whereIn('slug', [...]).
         */
        $headmasterPermissionNames = ['Add Student Event Kepala Sekolah', 'Add Teacher Event Kepala Sekolah'];

        $headmasterPermissions = DB::table('permissions')
            ->whereIn('name', $headmasterPermissionNames)
            ->pluck('id');

        foreach ($headmasterPermissions as $id) {
            $data[] = [
                'role_id'       => 6, // Kepala Sekolah
                'permission_id' => $id,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        // Insert semua mapping sekaligus
        DB::table('permission_role')->insert($data);
    }
}
