<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\TeacherEvent;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            PermissionSeeder::class,
            PermissionRoleSeeder::class,
            TrainingActivitySeeder::class,
            LetterTypeSeeder::class,
            AboutSeeder::class,
            OrganizationSeeder::class,
            CategorySeeder::class,
            ArticleSeeder::class,
            StudentCoursesSeeder::class,
            // Guru Les Gasing Seeders
            GuruLesGasingSeeder::class,
            PermissionRoleGuruLesGasingSeeder::class,
            TeacherEventSeeder::class,
            // Papua Random Seeder
            // PapuaRandomSeeder::class,
            FooterSeeder::class,
            StudentEventDummySeeder::class,
            EventBatchSeeder::class,
        ]);
    }
}
