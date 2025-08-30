<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'email', 'address', 'phone', 'logo', 'school_level'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function staffs()
    {
        return $this->hasMany(Staff::class);
    }

    public function jobTitles()
    {
        return $this->hasMany(JobTitle::class);
    }

    // Relationship: students
    public function students()
    {
        return $this->hasMany(\App\Models\User::class)->whereHas('role', function($q) {
            $q->where('name', 'student');
        });
    }

    // Relationship: teachers
    public function teachers()
    {
        return $this->hasMany(\App\Models\User::class)->whereHas('role', function($q) {
            $q->where('name', 'teacher');
        });
    }


    // Calculate school profit from payments table
    public function calculateProfit()
    {
        return $this->payments()->sum('amount') ?? 0;
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class);
    }
}

