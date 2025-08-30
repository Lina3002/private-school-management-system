<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\School;

class NewSuperAdminSeeder extends Seeder
{
    public function run()
    {
        // Get the first school or create one if needed
        $school = School::first() ?: School::create([
            'name' => 'Demo School',
            'email' => 'demo@school.com',
            'address' => '123 Demo St',
            'phone' => '0123456789',
            'logo' => null,
            'school_level' => 'primary',
        ]);

        // Get or create super_admin role for this school
        $role = Role::firstOrCreate([
            'name' => 'super_admin',
            'school_id' => $school->id,
        ]);

        // Create a new superadmin user
        $user = User::updateOrCreate([
            'email' => 'superadmin2@school.com',
        ], [
            'first_name' => 'Super',
            'last_name' => 'Admin2',
            'password' => Hash::make('superadmin456'), // Change password after first login!
            'school_id' => $school->id,
            'role_id' => $role->id,
        ]);
    }
}
