<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'subject_id',
        'value',
        'min_value',
        'max_value',
        'term',
        'exam_type',
        'school_id',
        'staff_id',
        'comment',
        'grading_date',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_value' => 'integer',
        'max_value' => 'integer',
        'grading_date' => 'datetime',
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

    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeByTerm($query, $term)
    {
        return $query->where('term', $term);
    }

    public function scopeByExamType($query, $examType)
    {
        return $query->where('exam_type', $examType);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('grading_date', [$startDate, $endDate]);
    }

    // Accessors
    public function getGradeLetterAttribute()
    {
        $percentage = ($this->value / $this->max_value) * 100;
        if ($percentage >= 90) return 'A';
        if ($percentage >= 80) return 'B';
        if ($percentage >= 70) return 'C';
        if ($percentage >= 60) return 'D';
        return 'F';
    }

    public function getGradeColorAttribute()
    {
        $percentage = ($this->value / $this->max_value) * 100;
        if ($percentage >= 90) return 'success';
        if ($percentage >= 80) return 'info';
        if ($percentage >= 70) return 'warning';
        if ($percentage >= 60) return 'secondary';
        return 'danger';
    }

    public function getIsPassingAttribute()
    {
        $percentage = ($this->value / $this->max_value) * 100;
        return $percentage >= 60;
    }

    public function getFormattedGradeAttribute()
    {
        return number_format($this->value, 1);
    }

    public function getPercentageAttribute()
    {
        return round(($this->value / $this->max_value) * 100, 2);
    }

    public function getFormattedDateAttribute()
    {
        return $this->grading_date ? $this->grading_date->format('M d, Y') : 'N/A';
    }
} 