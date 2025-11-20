<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionRoleGuruLesGasingSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk memberikan akses Guru Les Gasing.
     */
    public function run(): void
    {
        // Ambil daftar permission_id yang relevan
        $permissionIds = DB::table('permissions')
            ->whereIn('groupby', [15, 16, 17, 18, 19, 20]) // Student Course & Lesson Schedule
            ->pluck('id')
            ->toArray();

        $data = [];

        foreach ($permissionIds as $pid) {
            $data[] = [
                'role_id' => 4, // Guru Les Gasing
                'permission_id' => $pid,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('permission_role')->insert($data);
    }
}
