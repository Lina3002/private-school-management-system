<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'Bus_number',
        'capacity',
        'school_id',
    ];

    protected $casts = [
        'capacity' => 'integer',
    ];

    // Relationships
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'rides');
    }

    // Scopes
    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeByCapacity($query, $minCapacity)
    {
        return $query->where('capacity', '>=', $minCapacity);
    }

    public function scopeAvailable($query)
    {
        return $query->whereRaw('capacity > (SELECT COUNT(*) FROM student_transport WHERE transport_id = transports.id)');
    }

    // Accessors
    public function getCurrentEnrollmentAttribute()
    {
        return $this->students()->count();
    }

    public function getAvailableSeatsAttribute()
    {
        return $this->capacity - $this->current_enrollment;
    }

    public function getIsFullAttribute()
    {
        return $this->current_enrollment >= $this->capacity;
    }

    public function getRouteSummaryAttribute()
    {
        return "Bus {$this->Bus_number} - {$this->current_enrollment}/{$this->capacity} students";
    }

    // Methods
    public function canAssignStudent()
    {
        return $this->current_enrollment < $this->capacity;
    }

    public function assignStudent($studentId)
    {
        if ($this->canAssignStudent()) {
            $this->students()->attach($studentId);
            return true;
        }
        return false;
    }

    public function removeStudent($studentId)
    {
        $this->students()->detach($studentId);
    }
} 