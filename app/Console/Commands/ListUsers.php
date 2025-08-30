<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ListUsers extends Command
{
    protected $signature = 'users:list';
    protected $description = 'List all users and their roles';

    public function handle()
    {
        $users = User::with('role')->get();
        
        $this->info('Users in the system:');
        $this->table(
            ['ID', 'Name', 'Email', 'Role', 'School ID'],
            $users->map(function($user) {
                return [
                    $user->id,
                    $user->first_name . ' ' . $user->last_name,
                    $user->email,
                    $user->role ? $user->role->name : 'No Role',
                    $user->school_id
                ];
            })
        );
    }
} 