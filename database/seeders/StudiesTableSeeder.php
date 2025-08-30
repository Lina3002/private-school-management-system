<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudiesTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $schoolId = 1;

        // Example: get student IDs from the students table (assuming at least 2 students exist)
        $studentIds = DB::table('students')->take(2)->pluck('id');

        foreach ($studentIds as $studentId) {
            DB::table('studies')->insert([
                'student_id' => $studentId,
                'school_id' => $schoolId,
                'enrollment_year' => date('Y'),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
