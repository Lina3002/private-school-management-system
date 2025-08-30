<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class TestLogin extends Command
{
    protected $signature = 'test:login {email} {password}';
    protected $description = 'Test login credentials and show user information';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $this->info("Testing login for: {$email}");
        $this->line('');

        // Check if user exists
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("❌ User with email '{$email}' not found!");
            $this->line('');
            $this->info("Available users in database:");
            $users = User::with('role')->get();
            foreach ($users as $u) {
                $this->line("- {$u->email} (Role: " . ($u->role ? $u->role->name : 'None') . ")");
            }
            return 1;
        }

        $this->info("✅ User found!");
        $this->line("ID: {$user->id}");
        $this->line("Name: {$user->first_name} {$user->last_name}");
        $this->line("Email: {$user->email}");
        $this->line("School ID: {$user->school_id}");
        $this->line("Role ID: {$user->role_id}");
        $this->line("Role: " . ($user->role ? $user->role->name : 'None'));
        $this->line("Created: {$user->created_at}");
        $this->line("");

        // Check password
        if (Hash::check($password, $user->password)) {
            $this->info("✅ Password is correct!");
        } else {
            $this->error("❌ Password is incorrect!");
            $this->line("Stored hash: " . $user->password);
            $this->line("Input password: {$password}");
        }

        $this->line("");
        $this->info("Testing Auth::attempt...");
        
        // Test actual authentication
        if (auth()->attempt(['email' => $email, 'password' => $password])) {
            $this->info("✅ Auth::attempt successful!");
            auth()->logout();
        } else {
            $this->error("❌ Auth::attempt failed!");
        }

        return 0;
    }
} 