<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'subject_id',
        'student_id',
        'school_id',
        'attendancy_date',
        'Status',
        'justification',
    ];

    protected $casts = [
        'attendancy_date' => 'datetime',
        'Status' => 'boolean',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Scopes
    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('attendancy_date', $date);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('attendancy_date', [$startDate, $endDate]);
    }

    public function scopePresent($query)
    {
        return $query->where('Status', true);
    }

    public function scopeAbsent($query)
    {
        return $query->where('Status', false);
    }

    // Accessors
    public function getStatusColorAttribute()
    {
        return $this->Status ? 'success' : 'danger';
    }

    public function getStatusIconAttribute()
    {
        return $this->Status ? 'fa-check-circle' : 'fa-times-circle';
    }

    public function getFormattedDateAttribute()
    {
        return $this->attendancy_date ? $this->attendancy_date->format('M d, Y') : null;
    }

    public function getDayOfWeekAttribute()
    {
        return $this->attendancy_date ? $this->attendancy_date->format('l') : null;
    }

    public function getStatusTextAttribute()
    {
        return $this->Status ? 'Present' : 'Absent';
    }

    // Methods
    public function isPresent()
    {
        return $this->Status === true;
    }

    public function isAbsent()
    {
        return $this->Status === false;
    }

    public function markPresent()
    {
        $this->update(['Status' => true]);
    }

    public function markAbsent()
    {
        $this->update(['Status' => false]);
    }
} 