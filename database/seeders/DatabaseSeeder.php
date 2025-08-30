<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SuperAdminSeeder::class,        // Create school and super admin first
            PermissionSeeder::class,        // Create permissions
            RolesTableSeeder::class,        // Create roles (now school exists)
            JobTitlesTableSeeder::class,    // Create job titles
            UsersTableSeeder::class,        // Create admin and manager users
            StaffsTableSeeder::class,       // Create staff
            MoroccanEducationLevelsSeeder::class, // Create Moroccan education levels
            StudentsTableSeeder::class,     // Create students
            ParentsTableSeeder::class,      // Create parents
            TeachersTableSeeder::class,     // Create teachers
            PaymentSeeder::class,           // Create payments
        ]);
    }
}
