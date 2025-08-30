<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobTitle extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'school_id'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'accesses', 'job_title_id', 'permission_id');
    }

    public function staffs()
    {
        return $this->hasMany(Staff::class);
    }
}
