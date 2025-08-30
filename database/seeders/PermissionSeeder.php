<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use App\Models\JobTitle;
use Illuminate\Support\Facades\DB;

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
            'manage_homework',
            'view_attendance',
            'school.create', 'school.edit', 'school.delete', 'school.view', 'school.activate', 'school.deactivate',
            'teacher.create', 'teacher.edit', 'teacher.delete', 'teacher.view', 'teacher.assign_class', 'teacher.assign_subject',
            'student.create', 'student.edit', 'student.delete', 'student.view', 'student.assign_class', 'student.assign_parent', 'student.suspend',
            'parent.create', 'parent.edit', 'parent.delete', 'parent.view',
            'staff.create', 'staff.edit', 'staff.delete', 'staff.view', 'staff.assign_role', 'staff.assign_permission',
            'role.create', 'role.edit', 'role.delete', 'role.view', 'role.assign', 'permission.manage',
            'class.create', 'class.edit', 'class.delete', 'class.view',
            'subject.create', 'subject.edit', 'subject.delete', 'subject.view',
            'busroute.create', 'busroute.edit', 'busroute.delete', 'busroute.assign_driver', 'busroute.assign_assistant', 'busroute.track', 'busroute.view_schedule',
            'grades.view', 'grades.edit', 'reportcard.generate', 'grading.manage_scale',
            'timetable.create', 'timetable.edit', 'timetable.view', 'attendance.mark', 'attendance.edit', 'attendance.view',
            'platform.manage_settings', 'school.manage_settings', 'template.manage', 'platform.view_logs', 'platform.view_stats',
            'billing.view', 'billing.update', 'billing.cancel', 'billing.generate_invoice',
            'user.impersonate', 'user.reset_password', 'school.purge_data', 'data.backup', 'data.restore',
        ];

        // Ensure at least one school exists
        if (\App\Models\School::count() === 0) {
            \App\Models\School::create([
                'name' => 'Demo School',
                'email' => 'demo@school.com',
                'address' => '123 Demo St',
                'phone' => '0123456789',
                'logo' => null,
                'school_level' => 'primary',
            ]);
        }
        $schools = \App\Models\School::all();

        foreach ($schools as $school) {
            foreach ($defaultPermissions as $title) {
                Permission::firstOrCreate([
                    'title' => $title,
                    'school_id' => $school->id,
                ]);
            }

            $superAdminRole = Role::where([
                'name' => 'super_admin',
                'school_id' => $school->id,
            ])->first();

            if ($superAdminRole) {
                foreach (Permission::where('school_id', $school->id)->get() as $permission) {
                    // Use DB::table to insert with school_id for role-permission relationships
                    DB::table('controls')->insertOrIgnore([
                        'role_id' => $superAdminRole->id,
                        'permission_id' => $permission->id,
                        'school_id' => $school->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

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
                        // Use DB::table to insert with school_id for job-title-permission relationships
                        DB::table('accesses')->insertOrIgnore([
                            'job_title_id' => $teacherJob->id,
                            'permission_id' => $permission->id,
                            'school_id' => $school->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }
}
