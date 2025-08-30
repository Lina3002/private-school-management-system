<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class GradeController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $grades = Grade::where('school_id', $school->id)
                      ->with(['student', 'subject', 'staff'])
                      ->orderBy('grading_date', 'desc')
                      ->paginate(25);
        
        return view('admin.grades.index', compact('grades', 'school'));
    }

    public function create()
    {
        $school = Auth::user()->school;
        $students = Student::where('school_id', $school->id)->with('classroom')->get();
        $subjects = Subject::where('school_id', $school->id)->with('classroom')->get();
        $staff = Staff::where('school_id', $school->id)->get();
        return view('admin.grades.create', compact('school', 'students', 'subjects', 'staff'));
    }

    public function store(Request $request)
    {
        $school = Auth::user()->school;
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'value' => 'required|numeric|min:0|max:20',
            'min_value' => 'required|integer|min:0|max:20',
            'max_value' => 'required|integer|min:0|max:20',
            'term' => 'required|in:semester 1,semester 2',
            'exam_type' => 'required|in:exam 1,exam 2,exam 3,final,oral,activity,homework,quiz',
            'staff_id' => 'required|exists:staffs,id',
            'comment' => 'nullable|string',
            'grading_date' => 'required|date',
        ]);

        $grade = Grade::create([
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id,
            'value' => $request->value,
            'min_value' => $request->min_value,
            'max_value' => $request->max_value,
            'term' => $request->term,
            'exam_type' => $request->exam_type,
            'staff_id' => $request->staff_id,
            'comment' => $request->comment,
            'grading_date' => $request->grading_date,
            'school_id' => $school->id,
        ]);

        return redirect()->route('admin.grades.index')
                        ->with('success', 'Grade created successfully.');
    }

    public function show(Grade $grade)
    {
        $this->authorizeGrade($grade);
        $grade->load(['student', 'subject', 'staff']);
        
        return view('admin.grades.show', compact('grade'));
    }

    public function edit(Grade $grade)
    {
        $this->authorizeGrade($grade);
        $school = Auth::user()->school;
        $students = Student::where('school_id', $school->id)->get();
        $subjects = Subject::where('school_id', $school->id)->get();
        $staff = Staff::where('school_id', $school->id)->get();
        
        return view('admin.grades.edit', compact('grade', 'school', 'students', 'subjects', 'staff'));
    }

    public function update(Request $request, Grade $grade)
    {
        $this->authorizeGrade($grade);
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'value' => 'required|numeric|min:0|max:20',
            'min_value' => 'required|integer|min:0|max:20',
            'max_value' => 'required|integer|min:0|max:20',
            'term' => 'required|in:semester 1,semester 2',
            'exam_type' => 'required|in:exam 1,exam 2,exam 3,final,oral,activity,homework,quiz',
            'staff_id' => 'required|exists:staffs,id',
            'comment' => 'nullable|string',
            'grading_date' => 'required|date',
        ]);

        $grade->update($request->only([
            'student_id', 'subject_id', 'value', 'min_value', 'max_value', 
            'term', 'exam_type', 'staff_id', 'comment', 'grading_date'
        ]));

        return redirect()->route('admin.grades.index')
                        ->with('success', 'Grade updated successfully.');
    }

    public function destroy(Grade $grade)
    {
        $this->authorizeGrade($grade);
        $grade->delete();

        return redirect()->route('admin.grades.index')
                        ->with('success', 'Grade deleted successfully.');
    }

    public function bySubject(Subject $subject)
    {
        $this->authorizeSubject($subject);
        $grades = Grade::where('subject_id', $subject->id)
                      ->where('school_id', Auth::user()->school_id)
                      ->with(['student', 'staff'])
                      ->orderBy('grading_date', 'desc')
                      ->get();
        
        return view('admin.grades.by-subject', compact('subject', 'grades'));
    }

    public function byStudent(Student $student)
    {
        $this->authorizeStudent($student);
        $grades = Grade::where('student_id', $student->id)
                      ->where('school_id', Auth::user()->school_id)
                      ->with(['subject', 'staff'])
                      ->orderBy('grading_date', 'desc')
                      ->get();
        
        return view('admin.grades.by-student', compact('student', 'grades'));
    }

    public function bulkUpdate(Request $request)
    {
        $school = Auth::user()->school;
        $request->validate([
            'grades' => 'required|array',
            'grades.*.id' => 'required|exists:grades,id',
            'grades.*.value' => 'required|numeric|min:0|max:20',
            'grades.*.comment' => 'nullable|string',
        ]);

        foreach ($request->grades as $gradeData) {
            $grade = Grade::find($gradeData['id']);
            if ($grade && $grade->school_id === $school->id) {
                $grade->update([
                    'value' => $gradeData['value'],
                    'comment' => $gradeData['comment'] ?? $grade->comment,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Grades updated successfully.');
    }

    // CSV Export Methods
    public function exportCsv(Request $request)
    {
        $school = Auth::user()->school;
        $term = $request->get('term', 'semester 1');
        $studentId = $request->get('student_id');
        
        $query = Grade::where('school_id', $school->id)
                     ->where('term', $term)
                     ->with(['student', 'subject', 'staff']);
        
        if ($studentId) {
            $query->where('student_id', $studentId);
        }
        
        $grades = $query->orderBy('student_id')
                       ->orderBy('subject_id')
                       ->orderBy('grading_date')
                       ->get();
        
        $filename = $studentId ? 
            "grades_student_{$studentId}_{$term}_" . date('Y-m-d') . ".csv" :
            "grades_{$term}_" . date('Y-m-d') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($grades) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Student Name',
                'Massar Code',
                'Class',
                'Subject',
                'Grade Value',
                'Max Grade',
                'Percentage',
                'Term',
                'Exam Type',
                'Grading Date',
                'Graded By',
                'Comments'
            ]);
            
            // CSV Data
            foreach ($grades as $grade) {
                $percentage = $grade->max_value ? round(($grade->value / $grade->max_value) * 100, 1) : 0;
                
                fputcsv($file, [
                    $grade->student->full_name ?? 'N/A',
                    $grade->student->massar_code ?? 'N/A',
                    $grade->student->classroom->display_name ?? 'N/A',
                    $grade->subject->name ?? 'N/A',
                    $grade->value,
                    $grade->max_value,
                    $percentage . '%',
                    $grade->term,
                    $grade->exam_type,
                    $grade->grading_date ? $grade->grading_date->format('Y-m-d') : 'N/A',
                    $grade->staff ? ($grade->staff->first_name . ' ' . $grade->staff->last_name) : 'N/A',
                    $grade->comment ?? ''
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }

    public function exportStudentGrades(Student $student, Request $request)
    {
        $this->authorizeStudent($student);
        $term = $request->get('term', 'semester 1');
        
        $grades = Grade::where('student_id', $student->id)
                      ->where('term', $term)
                      ->where('school_id', Auth::user()->school_id)
                      ->with(['subject', 'staff'])
                      ->orderBy('subject_id')
                      ->orderBy('grading_date')
                      ->get();
        
        $filename = "grades_{$student->massar_code}_{$term}_" . date('Y-m-d') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($grades, $student) {
            $file = fopen('php://output', 'w');
            
            // Student Info
            fputcsv($file, ['Student Information']);
            fputcsv($file, ['Name', $student->full_name]);
            fputcsv($file, ['Massar Code', $student->massar_code]);
            fputcsv($file, ['Class', $student->classroom->display_name ?? 'N/A']);
            fputcsv($file, ['']);
            
            // Grades Headers
            fputcsv($file, [
                'Subject',
                'Grade Value',
                'Max Grade',
                'Percentage',
                'Term',
                'Exam Type',
                'Grading Date',
                'Graded By',
                'Comments'
            ]);
            
            // Grades Data
            foreach ($grades as $grade) {
                $percentage = $grade->max_value ? round(($grade->value / $grade->max_value) * 100, 1) : 0;
                
                fputcsv($file, [
                    $grade->subject->name ?? 'N/A',
                    $grade->value,
                    $grade->max_value,
                    $percentage . '%',
                    $grade->term,
                    $grade->exam_type,
                    $grade->grading_date ? $grade->grading_date->format('Y-m-d') : 'N/A',
                    $grade->staff ? ($grade->staff->first_name . ' ' . $grade->staff->last_name) : 'N/A',
                    $grade->comment ?? ''
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }

    private function authorizeGrade(Grade $grade)
    {
        if ($grade->school_id !== Auth::user()->school_id) {
            abort(403, 'Unauthorized action.');
        }
    }

    private function authorizeSubject(Subject $subject)
    {
        if ($subject->school_id !== Auth::user()->school_id) {
            abort(403, 'Unauthorized action.');
        }
    }

    private function authorizeStudent(Student $student)
    {
        if ($student->school_id !== Auth::user()->school_id) {
            abort(403, 'Unauthorized action.');
        }
    }
} 