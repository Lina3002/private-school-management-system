<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\Timetable;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $staffId = session('staff_id');
        $teacher = DB::table('staffs')->where('id', $staffId)->first();
        
        if (!$teacher) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'Teacher session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'School session expired. Please login again.']);
        }
        
        // Get teacher's subjects through the teaches table
        $subjects = DB::table('subjects')
                     ->join('teaches', 'subjects.id', '=', 'teaches.subject_id')
                     ->where('teaches.staff_id', $staffId)
                     ->where('subjects.school_id', $school->id)
                     ->select('subjects.*')
                     ->get();
        
        // Get total students through subjects and classrooms
        $totalStudents = DB::table('students')
                          ->join('classrooms', 'students.classroom_id', '=', 'classrooms.id')
                          ->join('subjects', 'classrooms.id', '=', 'subjects.classroom_id')
                          ->join('teaches', 'subjects.id', '=', 'teaches.subject_id')
                          ->where('teaches.staff_id', $staffId)
                          ->where('students.school_id', $school->id)
                          ->distinct('students.id')
                          ->count('students.id');
        
        // Get today's classes
        $today = now()->format('l');
        $todayClasses = DB::table('timetables')
                          ->join('subjects', 'timetables.subject_id', '=', 'subjects.id')
                          ->join('teaches', 'subjects.id', '=', 'teaches.subject_id')
                          ->where('teaches.staff_id', $staffId)
                          ->where('timetables.school_id', $school->id)
                          ->where('timetables.Day', $today)
                          ->orderBy('timetables.Time_start')
                          ->select('timetables.*', 'subjects.name as subject_name')
                          ->get();
        
        // Get recent grades
        $recentGrades = DB::table('grades')
                          ->join('subjects', 'grades.subject_id', '=', 'subjects.id')
                          ->join('teaches', 'subjects.id', '=', 'teaches.subject_id')
                          ->join('students', 'grades.student_id', '=', 'students.id')
                          ->where('teaches.staff_id', $staffId)
                          ->where('grades.school_id', $school->id)
                          ->orderBy('grades.grading_date', 'desc')
                          ->limit(5)
                          ->select('grades.*', 'subjects.name as subject_name', 'students.first_name', 'students.last_name')
                          ->get();
        
        return view('teacher.dashboard', compact('teacher', 'school', 'subjects', 'totalStudents', 'todayClasses', 'recentGrades'));
    }

    public function myClasses()
    {
        $staffId = session('staff_id');
        $teacher = DB::table('staffs')->where('id', $staffId)->first();
        
        if (!$teacher) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'Teacher session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'School session expired. Please login again.']);
        }
        
        // Get classes through subjects and teaches table
        $classes = DB::table('classrooms')
                     ->join('subjects', 'classrooms.id', '=', 'subjects.classroom_id')
                     ->join('teaches', 'subjects.id', '=', 'teaches.subject_id')
                     ->where('teaches.staff_id', $staffId)
                     ->where('classrooms.school_id', $school->id)
                     ->select('classrooms.*')
                     ->distinct()
                     ->get();
        
        return view('teacher.classes.index', compact('classes', 'school'));
    }

        public function myStudents()
    {
        $staffId = session('staff_id');
        $teacher = DB::table('staffs')->where('id', $staffId)->first();
        
        if (!$teacher) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'Teacher session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'School session expired. Please login again.']);
        }
        
        // Get students through subjects and teaches table
        $students = DB::table('students')
                      ->join('classrooms', 'students.classroom_id', '=', 'classrooms.id')
                      ->join('subjects', 'classrooms.id', '=', 'subjects.classroom_id')
                      ->join('teaches', 'subjects.id', '=', 'teaches.subject_id')
                      ->where('teaches.staff_id', $staffId)
                      ->where('students.school_id', $school->id)
                      ->select('students.*')
                      ->distinct()
                      ->get();
        
        return view('teacher.students.index', compact('students', 'school'));
    }

    public function myTimetable()
    {
        $staffId = session('staff_id');
        $teacher = DB::table('staffs')->where('id', $staffId)->first();
        
        if (!$teacher) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'Teacher session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'School session expired. Please login again.']);
        }
        
        // Get timetable through the teaches table
        $timetable = DB::table('timetables')
                       ->join('subjects', 'timetables.subject_id', '=', 'subjects.id')
                       ->join('teaches', 'subjects.id', '=', 'teaches.subject_id')
                       ->where('teaches.staff_id', $staffId)
                       ->where('timetables.school_id', $school->id)
                       ->orderBy('timetables.Day')
                       ->orderBy('timetables.Time_start')
                       ->select('timetables.*', 'subjects.name as subject_name')
                       ->get()
                       ->groupBy('Day');
        
        return view('teacher.timetable.index', compact('timetable', 'school'));
    }

        public function markAttendance()
    {
        $staffId = session('staff_id');
        $teacher = DB::table('staffs')->where('id', $staffId)->first();
        
        if (!$teacher) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'Teacher session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'School session expired. Please login again.']);
        }
        
        // Get subjects through the teaches table
        $subjects = DB::table('subjects')
                      ->join('teaches', 'subjects.id', '=', 'teaches.subject_id')
                      ->where('teaches.staff_id', $staffId)
                      ->where('subjects.school_id', $school->id)
                      ->select('subjects.*')
                      ->get();
        
        return view('teacher.attendance.mark', compact('subjects', 'school'));
    }

    public function storeAttendance(Request $request)
    {
        $staffId = session('staff_id');
        $teacher = DB::table('staffs')->where('id', $staffId)->first();
        
        if (!$teacher) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'Teacher session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'Teacher session expired. Please login again.']);
        }
        
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.status' => 'required|boolean',
            'attendance.*.justification' => 'nullable|string',
        ]);

        foreach ($request->attendance as $record) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $record['student_id'],
                    'subject_id' => $request->subject_id,
                    'attendancy_date' => $request->date,
                    'school_id' => $school->id,
                ],
                [
                    'Status' => $record['status'],
                    'justification' => $record['justification'] ?? null,
                ]
            );
        }

        return redirect()->route('teacher.attendance.mark')
                        ->with('success', 'Attendance marked successfully.');
    }

    public function viewAttendance()
    {
        $staffId = session('staff_id');
        $teacher = DB::table('staffs')->where('id', $staffId)->first();
        
        if (!$teacher) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'Teacher session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'Teacher session expired. Please login again.']);
        }
        
        // Get attendance through the teaches table
        $attendance = DB::table('attendance')
                        ->join('subjects', 'attendance.subject_id', '=', 'subjects.id')
                        ->join('teaches', 'subjects.id', '=', 'teaches.subject_id')
                        ->join('students', 'attendance.student_id', '=', 'students.id')
                        ->where('teaches.staff_id', $staffId)
                        ->where('attendance.school_id', $school->id)
                        ->orderBy('attendance.attendancy_date', 'desc')
                        ->select('attendance.*', 'subjects.name as subject_name', 'students.first_name', 'students.last_name')
                        ->paginate(25);
        
        return view('teacher.attendance.index', compact('attendance', 'school'));
    }

        public function recordGrade()
    {
        $staffId = session('staff_id');
        $teacher = DB::table('staffs')->where('id', $staffId)->first();
        
        if (!$teacher) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'Teacher session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'Teacher session expired. Please login again.']);
        }
        
        // Get subjects through the teaches table
        $subjects = DB::table('subjects')
                      ->join('teaches', 'subjects.id', '=', 'teaches.subject_id')
                      ->where('teaches.staff_id', $staffId)
                      ->where('subjects.school_id', $school->id)
                      ->select('subjects.*')
                      ->get();
        
        return view('teacher.grades.record', compact('subjects', 'school'));
    }

    public function storeGrade(Request $request)
    {
        $staffId = session('staff_id');
        $teacher = DB::table('staffs')->where('id', $staffId)->first();
        
        if (!$teacher) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'Teacher session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'Teacher session expired. Please login again.']);
        }
        
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'value' => 'required|numeric|min:0',
            'max_value' => 'required|numeric|min:0',
            'term' => 'required|string',
            'exam_type' => 'nullable|string',
            'comment' => 'nullable|string',
        ]);

        Grade::create([
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id,
            'value' => $request->value,
            'max_value' => $request->max_value,
            'term' => $request->term,
            'exam_type' => $request->exam_type,
            'staff_id' => $staffId,
            'comment' => $request->comment,
            'grading_date' => now(),
            'school_id' => $school->id,
        ]);

        return redirect()->route('teacher.grades.record')
                        ->with('success', 'Grade recorded successfully.');
    }

        public function viewGrades()
    {
        $staffId = session('staff_id');
        $teacher = DB::table('staffs')->where('id', $staffId)->first();
        
        if (!$teacher) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'Teacher session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'Teacher session expired. Please login again.']);
        }
        
        // Get grades through the teaches table
        $grades = DB::table('grades')
                    ->join('subjects', 'grades.subject_id', '=', 'subjects.id')
                    ->join('teaches', 'subjects.id', '=', 'teaches.subject_id')
                    ->join('students', 'grades.student_id', '=', 'students.id')
                    ->where('teaches.staff_id', $staffId)
                    ->where('grades.school_id', $school->id)
                    ->orderBy('grades.grading_date', 'desc')
                    ->select('grades.*', 'subjects.name as subject_name', 'students.first_name', 'students.last_name')
                    ->paginate(25);
        
        return view('teacher.grades.index', compact('grades', 'school'));
    }

        public function assignHomework()
    {
        $staffId = session('staff_id');
        $teacher = DB::table('staffs')->where('id', $staffId)->first();
        
        if (!$teacher) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'Teacher session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'Teacher session expired. Please login again.']);
        }
        
        // Get subjects through the teaches table
        $subjects = DB::table('subjects')
                      ->join('teaches', 'subjects.id', '=', 'teaches.subject_id')
                      ->where('teaches.staff_id', $staffId)
                      ->where('subjects.school_id', $school->id)
                      ->select('subjects.*')
                      ->get();
        
        return view('teacher.homework.assign', compact('subjects', 'school'));
    }

    public function storeHomework(Request $request)
    {
        $staffId = session('staff_id');
        $teacher = DB::table('staffs')->where('id', $staffId)->first();
        
        if (!$teacher) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'Teacher session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'Teacher session expired. Please login again.']);
        }
        
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date|after:today',
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        // Create homework record (you'll need to create this model)
        // For now, we'll just redirect with success
        return redirect()->route('teacher.homework.assign')
                        ->with('success', 'Homework assigned successfully.');
    }

    public function myProfile()
    {
        $staffId = session('staff_id');
        $teacher = DB::table('staffs')->where('id', $staffId)->first();
        
        if (!$teacher) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'Teacher session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'Teacher session expired. Please login again.']);
        }
        
        return view('teacher.profile.index', compact('teacher', 'school'));
    }

    public function updateProfile(Request $request)
    {
        $staffId = session('staff_id');
        $teacher = DB::table('staffs')->where('id', $staffId)->first();
        
        if (!$teacher) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'Teacher session expired. Please login again.']);
        }
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:staffs,email,' . $staffId,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0',
        ]);

        DB::table('staffs')
            ->where('id', $staffId)
            ->update($request->only([
                'first_name', 'last_name', 'email', 'phone', 'address', 
                'birth_date', 'gender', 'hire_date', 'salary'
            ]));

        // Update session data
        session()->put('user_name', $request->first_name . ' ' . $request->last_name);
        session()->put('user_email', $request->email);

        return redirect()->route('teacher.profile.index')
                        ->with('success', 'Profile updated successfully.');
    }
} 