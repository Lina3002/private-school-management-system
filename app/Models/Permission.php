<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'school_id'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'controls', 'permission_id', 'role_id');
    }

    public function jobTitles()
    {
        return $this->belongsToMany(JobTitle::class, 'accesses', 'permission_id', 'job_title_id');
    }
}
