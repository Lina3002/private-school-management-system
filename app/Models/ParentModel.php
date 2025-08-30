<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    protected $table = 'parents';
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'school_id'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
