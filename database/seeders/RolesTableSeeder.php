<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        DB::table('roles')->insert([
            ['name' => 'super_admin', 'school_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'admin', 'school_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'manager', 'school_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'teacher', 'school_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'student', 'school_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'parent', 'school_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'staff', 'school_id' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
