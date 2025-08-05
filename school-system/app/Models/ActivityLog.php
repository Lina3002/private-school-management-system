<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'description',
        'subject_type',
        'subject_id',
        'properties',
        'ip_address',
    ];

    // Optionally, link to the user who did the action
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
