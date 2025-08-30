<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Staff;

class ListStaff extends Command
{
    protected $signature = 'staff:list';
    protected $description = 'List all staff members and their details';

    public function handle()
    {
        $staffs = Staff::with('school', 'jobTitle')->get();
        
        $this->info('Staff members in the system:');
        $this->table(
            ['ID', 'Name', 'Email', 'Job Title', 'School ID'],
            $staffs->map(function($staff) {
                return [
                    $staff->id,
                    $staff->first_name . ' ' . $staff->last_name,
                    $staff->email,
                    $staff->jobTitle ? $staff->jobTitle->name : 'No Job Title',
                    $staff->school_id
                ];
            })
        );
    }
} 