<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudentsTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $schoolId = 1;
        $students = [];
        for ($i = 1; $i <= 2; $i++) {
            $students[] = [
                'massar_code' => sprintf('MAS%06d', rand(1, 999999)), // always 9 chars, unique enough for demo
                'first_name' => $i === 1 ? 'Ali' : 'Sara',
                'last_name' => $i === 1 ? 'Benali' : 'El Amrani',
                'gender' => $i === 1 ? 'male' : 'female',
                'photo' => '',
                'email' => strtolower(($i === 1 ? 'ali.benali' : 'sara.elamrani')) . '+'.uniqid().'@student.com',
                'password' => Hash::make('password123'),
                'birth_date' => $i === 1 ? '2012-03-14' : '2011-06-10',
                'driving_service' => $i === 2,
                'address' => $i === 1 ? '123 Main St' : '456 Elm St',
                'emergency_phone' => '070000000' . $i,
                'city_of_birth' => $i === 1 ? 'Casablanca' : 'Rabat',
                'country_of_birth' => 'Morocco',
                'school_id' => $schoolId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('students')->insert($students);
    }
}
