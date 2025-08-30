<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

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
        'classroom_id',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'driving_service' => 'boolean',
    ];

    // Relationships
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function transport()
    {
        return $this->belongsToMany(Transport::class, 'rides');
    }

    // Scopes
    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeByClassroom($query, $classroomId)
    {
        return $query->where('classroom_id', $classroomId);
    }

    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getInitialsAttribute()
    {
        return strtoupper(substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1));
    }

    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }

    public function getIsEnrolledAttribute()
    {
        return !is_null($this->classroom_id);
    }

    public function getAverageGradeAttribute()
    {
        $grades = $this->grades;
        if ($grades->isEmpty()) {
            return null;
        }
        
        return round($grades->avg('value'), 2);
    }

    public function getAttendancePercentageAttribute()
    {
        $attendances = $this->attendances;
        if ($attendances->isEmpty()) {
            return null;
        }
        
        $present = $attendances->where('status', 'present')->count();
        $total = $attendances->count();
        
        return round(($present / $total) * 100, 2);
    }
}
