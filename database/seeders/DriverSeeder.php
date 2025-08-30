<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\JobTitle;

class DriverSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $schoolId = 1;
        $school = \App\Models\School::find($schoolId) ?: \App\Models\School::first();
        if ($school) {
            $schoolId = $school->id;
        }

        // Ensure the job title exists
        $driverJob = JobTitle::firstOrCreate(
            ['name' => 'driver', 'school_id' => $schoolId],
            ['created_at' => $now, 'updated_at' => $now]
        );

        // Insert into staffs table directly with job_title 'driver'
        $result = DB::table('staffs')->updateOrInsert(
            [
                'email' => 'driver_test@school.com',
                'school_id' => $schoolId,
                'job_title_id' => $driverJob->id
            ],
            [
                'first_name' => 'Test',
                'last_name' => 'Driver',
                'password' => Hash::make('password123'),
                'phone' => '0600000000',
                'CIN' => 'DRV1234',
                'address' => 'Test Address',
                'created_at' => $now,
                'updated_at' => $now
            ]
        );
        $driver = \App\Models\Staff::where('email', 'driver_test@school.com')->first();
        \Log::info('Seeded driver staff:', $driver ? $driver->toArray() : ['not found']);
    }
}
