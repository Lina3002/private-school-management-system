<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timetable extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'Day',
        'Type',
        'Time_start',
        'Time_end',
        'creation_date',
        'subject_id',
        'staff_id',
        'school_id',
    ];

    protected $casts = [
        'Time_start' => 'datetime',
        'Time_end' => 'datetime',
        'creation_date' => 'datetime',
    ];

    // Relationships
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
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

    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeByStaff($query, $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    public function scopeByDay($query, $day)
    {
        return $query->where('Day', $day);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('Type', $type);
    }

    public function scopeByTimeRange($query, $startTime, $endTime)
    {
        return $query->where('Time_start', '>=', $startTime)
                    ->where('Time_end', '<=', $endTime);
    }

    // Accessors
    public function getDurationAttribute()
    {
        if ($this->Time_start && $this->Time_end) {
            return $this->Time_start->diffInMinutes($this->Time_end);
        }
        return null;
    }

    public function getFormattedStartTimeAttribute()
    {
        return $this->Time_start ? $this->Time_start->format('H:i') : null;
    }

    public function getFormattedEndTimeAttribute()
    {
        return $this->Time_end ? $this->Time_end->format('H:i') : null;
    }

    public function getDayNameAttribute()
    {
        $dayMapping = [
            'Mon' => 'Monday',
            'Tue' => 'Tuesday',
            'Wed' => 'Wednesday',
            'Thu' => 'Thursday',
            'Fri' => 'Friday',
            'Sat' => 'Saturday',
            'Sun' => 'Sunday'
        ];
        
        return $dayMapping[$this->Day] ?? ucfirst(strtolower($this->Day));
    }

    public function getTimeSlotAttribute()
    {
        return "{$this->formatted_start_time} - {$this->formatted_end_time}";
    }

    // Methods
    public function hasConflict()
    {
        return static::where('school_id', $this->school_id)
                    ->where('Day', $this->Day)
                    ->where('id', '!=', $this->id)
                    ->where(function($query) {
                        $query->where(function($q) {
                            $q->where('Time_start', '<=', $this->Time_start)
                              ->where('Time_end', '>', $this->Time_start);
                        })->orWhere(function($q) {
                            $q->where('Time_start', '<', $this->Time_end)
                              ->where('Time_end', '>=', $this->Time_end);
                        });
                    })
                    ->where(function($query) {
                        $query->where('subject_id', $this->subject_id)
                              ->orWhere('staff_id', $this->staff_id);
                    })
                    ->exists();
    }
} 