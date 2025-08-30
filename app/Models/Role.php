<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'school_id'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Permissions many-to-many (controls pivot)
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'controls', 'role_id', 'permission_id')->wherePivotNull('deleted_at');
    }
}


