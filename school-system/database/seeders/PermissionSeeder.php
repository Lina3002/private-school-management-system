<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use App\Models\JobTitle;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultPermissions = [
            'manage_students',
            'view_grades',
            'edit_timetable',
            'assign_teachers',
            'send_announcement',
            'manage_transport',
            'access_finance',
            'view_attendance',
            'manage_homework',
            'manage_permissions',
        ];

        $schools = \App\Models\School::all();

        foreach ($schools as $school) {
            foreach ($defaultPermissions as $title) {
                Permission::firstOrCreate([
                    'title' => $title,
                    'school_id' => $school->id,
                ]);
            }

            // Assign all permissions to super_admin
            $superAdminRole = Role::where([
                'name' => 'super_admin',
                'school_id' => $school->id,
            ])->first();

            if ($superAdminRole) {
                foreach (Permission::where('school_id', $school->id)->get() as $permission) {
                    $superAdminRole->permissions()->syncWithoutDetaching([$permission->id]);
                }
            }

            // Example: Give specific permissions to 'teacher'
            $teacherJob = JobTitle::where([
                'name' => 'teacher',
                'school_id' => $school->id,
            ])->first();

            if ($teacherJob) {
                $teacherPermissions = ['view_grades', 'manage_homework', 'view_attendance'];
                foreach ($teacherPermissions as $permTitle) {
                    $permission = Permission::where([
                        'title' => $permTitle,
                        'school_id' => $school->id,
                    ])->first();

                    if ($permission) {
                        $teacherJob->permissions()->syncWithoutDetaching([$permission->id]);
                    }
                }
            }
        }
    }
}
