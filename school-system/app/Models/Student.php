<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'massar_code',
        'first_name',
        'last_name',
        'gender',
        'photo',
        'email',
        'password',
        'birth_date',
        'driving_service',
        'address',
        'emergency_phone',
        'city_of_birth',
        'country_of_birth',
        'school_id',
    ];
    protected $table = 'students';

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
