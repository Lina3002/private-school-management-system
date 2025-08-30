<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JobTitlesTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $titles = ['teacher', 'secretary', 'bus_assistant', 'accountant', 'nurse', 'security', 'librarian', 'maintenance', 'counselor', 'principal'];
        $schools = \App\Models\School::all();
        foreach ($schools as $school) {
            foreach ($titles as $title) {
                DB::table('job_titles')->insert([
                    'name' => $title,
                    'school_id' => $school->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
