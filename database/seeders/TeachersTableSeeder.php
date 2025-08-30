<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TeachersTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $schoolId = 1;
        $jobTitleId = DB::table('job_titles')->where('school_id', $schoolId)->where('name', 'teacher')->value('id');
        $teachers = [
            [
                'first_name' => 'Youssef',
                'last_name' => 'Ait Taleb',
                'email' => 'youssef.aittaleb@teacher.com',
                'password' => Hash::make('password123'),
                'phone' => '0610000001',
                'CIN' => 'CIN001',
                'address' => '789 Oak St',
                'school_id' => $schoolId,
                'job_title_id' => $jobTitleId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'first_name' => 'Samira',
                'last_name' => 'Bennis',
                'email' => 'samira.bennis@teacher.com',
                'password' => Hash::make('password123'),
                'phone' => '0610000002',
                'CIN' => 'CIN002',
                'address' => '321 Pine St',
                'school_id' => $schoolId,
                'job_title_id' => $jobTitleId,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ];
        DB::table('staffs')->insert($teachers);
    }
}
