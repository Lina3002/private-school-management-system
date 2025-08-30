<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\Timetable;
use App\Models\Subject;
use App\Models\Student;
use App\Models\School;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function dashboard()
    {
        $studentId = session('student_id');
        $student = DB::table('students')->where('id', $studentId)->first();
        
        if (!$student) {
            session()->forget(['student_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'Student session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['student_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'School session expired. Please login again.']);
        }
        
        // Get student's grades
        $grades = DB::table('grades')
                    ->join('subjects', 'grades.subject_id', '=', 'subjects.id')
                    ->where('grades.student_id', $student->id)
                    ->where('grades.school_id', $school->id)
                    ->orderBy('grades.grading_date', 'desc')
                    ->limit(5)
                    ->select('grades.*', 'subjects.name as subject_name')
                    ->get();
        
        // Get attendance summary
        $attendanceSummary = DB::table('attendances')
                              ->where('student_id', $student->id)
                              ->where('school_id', $school->id)
                              ->selectRaw('
                                  COUNT(*) as total,
                                  SUM(CASE WHEN Status = 1 THEN 1 ELSE 0 END) as present,
                                  SUM(CASE WHEN Status = 0 THEN 1 ELSE 0 END) as absent
                              ')
                              ->first();
        
        // Get today's classes
        $today = now()->format('l');
        $todayClasses = DB::table('timetables')
                          ->join('subjects', 'timetables.subject_id', '=', 'subjects.id')
                          ->join('classrooms', 'subjects.classroom_id', '=', 'classrooms.id')
                          ->where('timetables.school_id', $school->id)
                          ->where('classrooms.id', $student->classroom_id)
                          ->where('timetables.Day', $today)
                          ->orderBy('timetables.Time_start')
                          ->select('timetables.*', 'subjects.name as subject_name')
                          ->get();
        
        // Get upcoming homework (placeholder)
        $upcomingHomework = collect(); // You'll need to create a homework model
        
        return view('student.dashboard', compact('student', 'school', 'grades', 'attendanceSummary', 'todayClasses', 'upcomingHomework'));
    }

    public function myGrades()
    {
        $studentId = session('student_id');
        $student = DB::table('students')->where('id', $studentId)->first();
        
        if (!$student) {
            session()->forget(['student_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'Student session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['student_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'School session expired. Please login again.']);
        }
        
        $grades = DB::table('grades')
                    ->join('subjects', 'grades.subject_id', '=', 'subjects.id')
                    ->where('grades.student_id', $student->id)
                    ->where('grades.school_id', $school->id)
                    ->orderBy('grades.grading_date', 'desc')
                    ->select('grades.*', 'subjects.name as subject_name')
                    ->paginate(25);
        
        // Calculate GPA
        $gpa = $grades->avg('value');
        
        return view('student.grades', compact('grades', 'gpa', 'school'));
    }

    public function myAttendance()
    {
        $studentId = session('student_id');
        $student = DB::table('students')->where('id', $studentId)->first();
        
        if (!$student) {
            session()->forget(['student_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'Student session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['student_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'Student session expired. Please login again.']);
        }
        
        $attendance = DB::table('attendances')
                        ->join('subjects', 'attendances.subject_id', '=', 'subjects.id')
                        ->where('attendances.student_id', $student->id)
                        ->where('attendances.school_id', $school->id)
                        ->orderBy('attendances.attendancy_date', 'desc')
                        ->select('attendances.*', 'subjects.name as subject_name')
                        ->paginate(25);
        
        // Get attendance by subject
        $attendanceBySubject = DB::table('attendances')
                                ->join('subjects', 'attendances.subject_id', '=', 'subjects.id')
                                ->where('attendances.student_id', $student->id)
                                ->where('attendances.school_id', $school->id)
                                ->select('attendances.*', 'subjects.name as subject_name')
                                ->get()
                                ->groupBy('subject_name');
        
        return view('student.attendance', compact('attendance', 'attendanceBySubject', 'school'));
    }

    public function myTimetable()
    {
        $studentId = session('student_id');
        $student = DB::table('students')->where('id', $studentId)->first();
        
        if (!$student) {
            session()->forget(['student_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'Student session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['student_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'Student session expired. Please login again.']);
        }
        
        $timetable = DB::table('timetables')
                        ->join('subjects', 'timetables.subject_id', '=', 'subjects.id')
                        ->join('classrooms', 'subjects.classroom_id', '=', 'classrooms.id')
                        ->where('timetables.school_id', $school->id)
                        ->where('classrooms.id', $student->classroom_id)
                        ->orderBy('timetables.Day')
                        ->orderBy('timetables.Time_start')
                        ->select('timetables.*', 'subjects.name as subject_name')
                        ->get()
                        ->groupBy('Day');
        
        return view('student.timetable', compact('timetable', 'school'));
    }

    public function myClasses()
    {
        $studentId = session('student_id');
        $student = DB::table('students')->where('id', $studentId)->first();
        
        if (!$student) {
            session()->forget(['student_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'Student session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['student_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'School session expired. Please login again.']);
        }
        
        $classroom = DB::table('classrooms')->where('id', $student->classroom_id)->first();
        $subjects = DB::table('subjects')
                      ->where('classroom_id', $student->classroom_id)
                      ->where('school_id', $school->id)
                      ->get();
        
        $classmates = DB::table('students')
                        ->where('classroom_id', $student->classroom_id)
                        ->where('school_id', $school->id)
                        ->where('id', '!=', $student->id)
                        ->get();
        
        return view('student.classes', compact('classroom', 'subjects', 'classmates', 'school'));
    }

    public function myHomework()
    {
        $studentId = session('student_id');
        $student = DB::table('students')->where('id', $studentId)->first();
        
        if (!$student) {
            session()->forget(['student_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'Student session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['student_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'School session expired. Please login again.']);
        }
        
        // Placeholder for homework - you'll need to create this model
        $homework = collect();
        
        return view('student.homework', compact('homework', 'school'));
    }

    public function myProfile()
    {
        $studentId = session('student_id');
        $student = DB::table('students')->where('id', $studentId)->first();
        
        if (!$student) {
            session()->forget(['student_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'Student session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['student_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'School session expired. Please login again.']);
        }
        
        return view('student.profile', compact('student', 'school'));
    }

    public function updateProfile(Request $request)
    {
        $studentId = session('student_id');
        $student = DB::table('students')->where('id', $studentId)->first();
        
        if (!$student) {
            session()->forget(['student_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'Student session expired. Please login again.']);
        }
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $studentId,
            'address' => 'nullable|string',
            'emergency_phone' => 'nullable|string|max:20',
        ]);

        DB::table('students')
            ->where('id', $studentId)
            ->update($request->only(['first_name', 'last_name', 'email', 'address', 'emergency_phone']));

        // Update session data
        session(['user_name' => $request->first_name . ' ' . $request->last_name]);
        session(['user_email' => $request->email]);

        return redirect()->route('student.profile.index')
                        ->with('success', 'Profile updated successfully.');
    }
} 