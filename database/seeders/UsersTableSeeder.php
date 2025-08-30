<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Role;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $schoolId = 1;
        $roles = Role::where('school_id', $schoolId)->orWhereNull('school_id')->pluck('id', 'name');


        // Only Admin and Manager
        DB::table('users')->insert([
            [
                'first_name' => 'School',
                'last_name' => 'Admin',
                'email' => 'admin1@school.com',
                'password' => Hash::make('password123'),
                'school_id' => $schoolId,
                'role_id' => $roles['admin'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'first_name' => 'School',
                'last_name' => 'Manager',
                'email' => 'manager1@school.com',
                'password' => Hash::make('password123'),
                'school_id' => $schoolId,
                'role_id' => $roles['manager'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

    }
}
