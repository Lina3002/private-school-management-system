<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\JobTitle;

class StaffsTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $schoolId = 1;
        $staffUsers = User::where('school_id', $schoolId)
            ->whereIn('email', [
                'teacher1@school.com',
                'secretary1@school.com',
                'busassistant1@school.com',
                'driver1@school.com',
            ])->get();
        $jobTitles = JobTitle::where('school_id', $schoolId)->pluck('id', 'name');
        $staffData = [
            [
                'user_email' => 'teacher1@school.com',
                'job_title' => 'teacher',
            ],
            [
                'user_email' => 'secretary1@school.com',
                'job_title' => 'secretary',
            ],
            [
                'user_email' => 'busassistant1@school.com',
                'job_title' => 'bus_assistant',
            ],
            [
                'user_email' => 'driver1@school.com',
                'job_title' => 'driver',
            ]
        ];
        foreach ($staffData as $staff) {
            $user = $staffUsers->firstWhere('email', $staff['user_email']);
            if ($user && isset($jobTitles[$staff['job_title']])) {
                DB::table('staffs')->insert([
                    'user_id' => $user->id,
                    'job_title_id' => $jobTitles[$staff['job_title']],
                    'school_id' => $schoolId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
