<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ParentsTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $schoolId = 1;
        $parents = [
            [
                'first_name' => 'Mohamed',
                'last_name' => 'Benali',
                'email' => 'mohamed.benali@parent.com',
                'password' => Hash::make('password123'),
                'school_id' => $schoolId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'first_name' => 'Fatima',
                'last_name' => 'El Amrani',
                'email' => 'fatima.elamrani@parent.com',
                'password' => Hash::make('password123'),
                'school_id' => $schoolId,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ];
        DB::table('parents')->insert($parents);
    }
}
