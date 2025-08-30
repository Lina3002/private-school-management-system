<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use App\Models\ParentModel;
use App\Models\Student;

class CheckDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check database contents';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Database Check ===');
        
        // Check Users
        $this->info('Users: ' . User::count());
        $users = User::with('role')->get();
        foreach ($users as $user) {
            $roleName = $user->role ? $user->role->name : 'No role';
            $this->line("  - {$user->email} (Role: {$roleName})");
        }
        
        // Check Roles
        $this->info('Roles: ' . Role::count());
        $roles = Role::all();
        foreach ($roles as $role) {
            $this->line("  - {$role->name}");
        }
        
        // Check Parents
        $this->info('Parents: ' . ParentModel::count());
        $parents = ParentModel::all();
        foreach ($parents as $parent) {
            $this->line("  - {$parent->email} ({$parent->first_name} {$parent->last_name})");
        }
        
        // Check Students
        $this->info('Students: ' . Student::count());
        $students = Student::all();
        foreach ($students as $student) {
            $this->line("  - {$student->email} ({$student->first_name} {$student->last_name})");
        }
        
        $this->info('=== End Database Check ===');
        
        return 0;
    }
}
