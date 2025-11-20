<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];

        for ($i = 1; $i <= 10000; $i++) {
            $data[] = [
                'role_id' => 1,
                'permission_id' => $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        for ($i = 1; $i <= 9; $i++) {
            $data[] = [
                'role_id' => 2,
                'permission_id' => $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        for ($i = 1; $i <= 1000; $i++) {
            $data[] = [
                'role_id' => 4,
                'permission_id' => $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }


        DB::table('permission_role')->insert($data);
    }
}
