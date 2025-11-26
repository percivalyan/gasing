<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'Dashboard', 'slug' => 'Dashboard', 'groupby' => 0],

            // Admin
            ['name' => 'User', 'slug' => 'User', 'groupby' => 1],
            ['name' => 'Add User', 'slug' => 'Add User', 'groupby' => 1],
            ['name' => 'Edit User', 'slug' => 'Edit User', 'groupby' => 1],
            ['name' => 'Delete User', 'slug' => 'Delete User', 'groupby' => 1],

            ['name' => 'User', 'slug' => 'User Guru Les Gasing', 'groupby' => 1],
            ['name' => 'Add User', 'slug' => 'Add User Guru Les Gasing', 'groupby' => 1],
            ['name' => 'Edit User', 'slug' => 'Edit User Guru Les Gasing', 'groupby' => 1],
            ['name' => 'Delete User', 'slug' => 'Delete User Guru Les Gasing', 'groupby' => 1],

            // Admin
            ['name' => 'Role', 'slug' => 'Role', 'groupby' => 2],
            ['name' => 'Add Role', 'slug' => 'Add Role', 'groupby' => 2],
            ['name' => 'Edit Role', 'slug' => 'Edit Role', 'groupby' => 2],
            ['name' => 'Delete Role', 'slug' => 'Delete Role', 'groupby' => 2],

            // Admin
            ['name' => 'Category', 'slug' => 'Category', 'groupby' => 3],
            ['name' => 'Add Category', 'slug' => 'Add Category', 'groupby' => 3],
            ['name' => 'Edit Category', 'slug' => 'Edit Category', 'groupby' => 3],
            ['name' => 'Delete Category', 'slug' => 'Delete Category', 'groupby' => 3],

            // Admin
            ['name' => 'Article', 'slug' => 'Article', 'groupby' => 4],
            ['name' => 'Add Article', 'slug' => 'Add Article', 'groupby' => 4],
            ['name' => 'Edit Article', 'slug' => 'Edit Article', 'groupby' => 4],
            ['name' => 'Delete Article', 'slug' => 'Delete Article', 'groupby' => 4],

            // Sekretaris
            ['name' => 'Document', 'slug' => 'Document', 'groupby' => 6],
            ['name' => 'Add Document', 'slug' => 'Add Document', 'groupby' => 6],
            ['name' => 'Edit Document', 'slug' => 'Edit Document', 'groupby' => 6],
            ['name' => 'Delete Document', 'slug' => 'Delete Document', 'groupby' => 6],

            // Sekretaris
            ['name' => 'ReferenceNumber', 'slug' => 'ReferenceNumber', 'groupby' => 7],
            ['name' => 'Add ReferenceNumber', 'slug' => 'Add ReferenceNumber', 'groupby' => 7],
            ['name' => 'Delete ReferenceNumber', 'slug' => 'Delete ReferenceNumber', 'groupby' => 7],

            // Sekretaris
            ['name' => 'LetterType', 'slug' => 'LetterType', 'groupby' => 8],
            ['name' => 'Add LetterType', 'slug' => 'Add LetterType', 'groupby' => 8],
            ['name' => 'Edit LetterType', 'slug' => 'Edit LetterType', 'groupby' => 8],
            ['name' => 'Delete LetterType', 'slug' => 'Delete LetterType', 'groupby' => 8],

            // Admin
            ['name' => 'Gallery', 'slug' => 'Gallery', 'groupby' => 9],
            ['name' => 'Add Gallery', 'slug' => 'Add Gallery', 'groupby' => 9],
            ['name' => 'Edit Gallery', 'slug' => 'Edit Gallery', 'groupby' => 9],
            ['name' => 'Delete Gallery', 'slug' => 'Delete Gallery', 'groupby' => 9],

            // Admin
            ['name' => 'About', 'slug' => 'About', 'groupby' => 10],
            ['name' => 'Edit About', 'slug' => 'Edit About', 'groupby' => 10],

            // Admin
            ['name' => 'Organization Structure', 'slug' => 'Organization Structure', 'groupby' => 11],
            ['name' => 'Add Organization Structure', 'slug' => 'Add Organization Structure', 'groupby' => 11],
            ['name' => 'Edit Organization Structure', 'slug' => 'Edit Organization Structure', 'groupby' => 11],
            ['name' => 'Delete Organization Structure', 'slug' => 'Delete Organization Structure', 'groupby' => 11],

            // Admin
            ['name' => 'Organization Member', 'slug' => 'Organization Member', 'groupby' => 12],
            ['name' => 'Add Organization Member', 'slug' => 'Add Organization Member', 'groupby' => 12],
            ['name' => 'Edit Organization Member', 'slug' => 'Edit Organization Member', 'groupby' => 12],
            ['name' => 'Delete Organization Member', 'slug' => 'Delete Organization Member', 'groupby' => 12],

            // Kepala Sekolah
            ['name' => 'Teacher Event', 'slug' => 'Teacher Event', 'groupby' => 13],
            ['name' => 'Add Teacher Event', 'slug' => 'Add Teacher Event', 'groupby' => 13],
            ['name' => 'Add Teacher Event Kepala Sekolah', 'slug' => 'Add Teacher Event', 'groupby' => 13],
            ['name' => 'Edit Teacher Event', 'slug' => 'Edit Teacher Event', 'groupby' => 13],
            ['name' => 'Delete Teacher Event', 'slug' => 'Delete Teacher Event', 'groupby' => 13],

            ['name' => 'Student Event', 'slug' => 'Student Event', 'groupby' => 14],
            ['name' => 'Add Student Event', 'slug' => 'Add Student Event', 'groupby' => 14],
            ['name' => 'Add Student Event Kepala Sekolah', 'slug' => 'Add Student Event', 'groupby' => 14],
            ['name' => 'Edit Student Event', 'slug' => 'Edit Student Event', 'groupby' => 14],
            ['name' => 'Delete Student Event', 'slug' => 'Delete Student Event', 'groupby' => 14],

            // Guru Les Gasing
            ['name' => 'Student Course', 'slug' => 'Student Course', 'groupby' => 15],
            ['name' => 'Add Student Course', 'slug' => 'Add Student Course', 'groupby' => 15],
            ['name' => 'Edit Student Course', 'slug' => 'Edit Student Course', 'groupby' => 15],
            ['name' => 'Delete Student Course', 'slug' => 'Delete Student Course', 'groupby' => 15],

            // Guru Les Gasing
            ['name' => 'Lesson Schedule', 'slug' => 'Lesson Schedule', 'groupby' => 16],
            ['name' => 'Add Lesson Schedule', 'slug' => 'Add Lesson Schedule', 'groupby' => 16],
            ['name' => 'Edit Lesson Schedule', 'slug' => 'Edit Lesson Schedule', 'groupby' => 16],
            ['name' => 'Delete Lesson Schedule', 'slug' => 'Delete Lesson Schedule', 'groupby' => 16],

            ['name' => 'Subject', 'slug' => 'Subject', 'groupby' => 17],
            ['name' => 'Add Subject', 'slug' => 'Add Subject', 'groupby' => 17],
            ['name' => 'Edit Subject', 'slug' => 'Edit Subject', 'groupby' => 17],
            ['name' => 'Delete Subject', 'slug' => 'Delete Subject', 'groupby' => 17],

            ['name' => 'Assign Teacher', 'slug' => 'Assign Teacher', 'groupby' => 17],
            ['name' => 'Add Assign Teacher', 'slug' => 'Add Assign Teacher', 'groupby' => 17],
            ['name' => 'Edit Assign Teacher', 'slug' => 'Edit Assign Teacher', 'groupby' => 17],
            ['name' => 'Delete Assign Teacher', 'slug' => 'Delete Assign Teacher', 'groupby' => 17],

            ['name' => 'Assign Teacher Event', 'slug' => 'Assign Teacher Event', 'groupby' => 18],
            ['name' => 'Add Assign Teacher Event', 'slug' => 'Add Assign Teacher Event', 'groupby' => 18],
            ['name' => 'Edit Assign Teacher Event', 'slug' => 'Edit Assign Teacher Event', 'groupby' => 18],
            ['name' => 'Delete Assign Teacher Event', 'slug' => 'Delete Assign Teacher Event', 'groupby' => 18],

            ['name' => 'Assessment', 'slug' => 'Assessment', 'groupby' => 19],
            ['name' => 'Add Assessment', 'slug' => 'Add Assessment', 'groupby' => 19],
            ['name' => 'Edit Assessment', 'slug' => 'Edit Assessment', 'groupby' => 19],
            ['name' => 'Delete Assessment', 'slug' => 'Delete Assessment', 'groupby' => 19],

            ['name' => 'Attendance Course', 'slug' => 'Attendance Course', 'groupby' => 19],
            ['name' => 'Manage Attendance Course', 'slug' => 'Manage Attendance Course', 'groupby' => 19],

            ['name' => 'Event Schedule', 'slug' => 'Event Schedule', 'groupby' => 20],
            ['name' => 'Add Event Schedule', 'slug' => 'Add Event Schedule', 'groupby' => 20],
            ['name' => 'Edit Event Schedule', 'slug' => 'Edit Event Schedule', 'groupby' => 20],
            ['name' => 'Delete Event Schedule', 'slug' => 'Delete Event Schedule', 'groupby' => 20],

            ['name' => 'Event Batch', 'slug' => 'Event Batch', 'groupby' => 20],
            ['name' => 'Add Event Batch', 'slug' => 'Add Event Batch', 'groupby' => 20],
            ['name' => 'Edit Event Batch', 'slug' => 'Edit Event Batch', 'groupby' => 20],
            ['name' => 'Delete Event Batch', 'slug' => 'Delete Event Batch', 'groupby' => 20],

        ];

        DB::table('permissions')->insert($permissions);
    }
}
