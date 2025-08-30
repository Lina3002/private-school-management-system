<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\Timetable;
use App\Models\ParentModel;
use App\Models\School;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParentController extends Controller
{
    public function dashboard()
    {
        $parentId = session('parent_id');
        $parent = DB::table('parents')->where('id', $parentId)->first();
        
        if (!$parent) {
            // Clear invalid session and redirect to login
            session()->forget(['parent_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'Parent session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            // Clear invalid session and redirect to login
            session()->forget(['parent_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'School session expired. Please login again.']);
        }
        
        // Get parent's children with classroom information
        $children = DB::table('students')
                      ->leftJoin('classrooms', 'students.classroom_id', '=', 'classrooms.id')
                      ->where('students.parent_id', $parent->id)
                      ->where('students.school_id', $school->id)
                      ->select('students.*', 'classrooms.name as classroom_name')
                      ->get();
        
        $childrenIds = $children->pluck('id')->toArray();
        
        // Get recent grades for all children
        $recentGrades = DB::table('grades')
                          ->join('students', 'grades.student_id', '=', 'students.id')
                          ->join('subjects', 'grades.subject_id', '=', 'subjects.id')
                          ->whereIn('grades.student_id', $childrenIds)
                          ->where('grades.school_id', $school->id)
                          ->orderBy('grades.grading_date', 'desc')
                          ->limit(10)
                          ->select('grades.*', 'students.first_name', 'students.last_name', 'subjects.name as subject_name')
                          ->get();
        
        // Get attendance summary for all children
        $attendanceSummary = DB::table('attendances')
                              ->whereIn('student_id', $childrenIds)
                              ->where('school_id', $school->id)
                              ->selectRaw('
                                  COUNT(*) as total,
                                  SUM(CASE WHEN Status = 1 THEN 1 ELSE 0 END) as present,
                                  SUM(CASE WHEN Status = 0 THEN 1 ELSE 0 END) as absent
                              ')
                              ->first();
        
        return view('parent.dashboard', compact('parent', 'school', 'children', 'recentGrades', 'attendanceSummary'));
    }

    public function myChildren()
    {
        $parentId = session('parent_id');
        $parent = DB::table('parents')->where('id', $parentId)->first();
        
        if (!$parent) {
            session()->forget(['parent_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'Parent session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['parent_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'School session expired. Please login again.']);
        }
        
        $children = DB::table('students')
                      ->leftJoin('classrooms', 'students.classroom_id', '=', 'classrooms.id')
                      ->where('students.parent_id', $parent->id)
                      ->where('students.school_id', $school->id)
                      ->select('students.*', 'classrooms.name as classroom_name')
                      ->get();
        
        return view('parent.children.index', compact('children', 'school'));
    }

    public function childGrades($childId)
    {
        $parentId = session('parent_id');
        $parent = ParentModel::findOrFail($parentId);
        $school = School::findOrFail(session('school_id'));
        
        // Verify the child belongs to this parent
        $child = DB::table('students')
                   ->where('id', $childId)
                   ->where('parent_id', $parent->id)
                   ->where('school_id', $school->id)
                   ->first();
        
        if (!$child) {
            return redirect()->route('parent.children.index')->withErrors(['error' => 'Child not found.']);
        }
        
        $grades = DB::table('grades')
                    ->join('subjects', 'grades.subject_id', '=', 'subjects.id')
                    ->where('grades.student_id', $child->id)
                    ->where('grades.school_id', $school->id)
                    ->orderBy('grades.grading_date', 'desc')
                    ->select('grades.*', 'subjects.name as subject_name')
                    ->paginate(25);
        
        // Calculate GPA
        $gpa = $grades->avg('value');
        
        // Get grades by subject
        $gradesBySubject = DB::table('grades')
                            ->join('subjects', 'grades.subject_id', '=', 'subjects.id')
                            ->where('grades.student_id', $child->id)
                            ->where('grades.school_id', $school->id)
                            ->select('grades.*', 'subjects.name as subject_name')
                            ->get()
                            ->groupBy('subject_name');
        
        return view('parent.children.grades', compact('child', 'grades', 'gpa', 'gradesBySubject', 'school'));
    }

    public function childAttendance($childId)
    {
        $parentId = session('parent_id');
        $parent = DB::table('parents')->where('id', $parentId)->first();
        
        if (!$parent) {
            session()->forget(['parent_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'Parent session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['parent_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'School session expired. Please login again.']);
        }
        
        // Verify the child belongs to this parent
        $child = DB::table('students')
                   ->where('id', $childId)
                   ->where('parent_id', $parent->id)
                   ->where('school_id', $school->id)
                   ->first();
        
        if (!$child) {
            return redirect()->route('parent.children.index')->withErrors(['error' => 'Child not found.']);
        }
        
        $attendance = DB::table('attendances')
                        ->join('subjects', 'attendances.subject_id', '=', 'subjects.id')
                        ->where('attendances.student_id', $child->id)
                        ->where('attendances.school_id', $school->id)
                        ->orderBy('attendances.attendancy_date', 'desc')
                        ->select('attendances.*', 'subjects.name as subject_name')
                        ->paginate(25);
        
        // Get attendance by subject
        $attendanceBySubject = DB::table('attendances')
                                ->join('subjects', 'attendances.subject_id', '=', 'subjects.id')
                                ->where('attendances.student_id', $child->id)
                                ->where('attendances.school_id', $school->id)
                                ->select('attendances.*', 'subjects.name as subject_name')
                                ->get()
                                ->groupBy('subject_name');
        
        // Get attendance by month
        $attendanceByMonth = DB::table('attendances')
                              ->where('student_id', $child->id)
                              ->where('school_id', $school->id)
                              ->get()
                              ->groupBy(function($item) {
                                  return \Carbon\Carbon::parse($item->attendancy_date)->format('Y-m');
                              });
        
        return view('parent.children.attendance', compact('child', 'attendance', 'attendanceBySubject', 'attendanceByMonth', 'school'));
    }

    public function childTimetable($childId)
    {
        $parentId = session('parent_id');
        $parent = DB::table('parents')->where('id', $parentId)->first();
        
        if (!$parent) {
            session()->forget(['parent_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'Parent session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['parent_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'School session expired. Please login again.']);
        }
        
        // Verify the child belongs to this parent
        $child = DB::table('students')
                   ->where('id', $childId)
                   ->where('parent_id', $parent->id)
                   ->where('school_id', $school->id)
                   ->first();
        
        if (!$child) {
            return redirect()->route('parent.children.index')->withErrors(['error' => 'Child not found.']);
        }
        
        $timetable = DB::table('timetables')
                        ->join('subjects', 'timetables.subject_id', '=', 'subjects.id')
                        ->join('classrooms', 'subjects.classroom_id', '=', 'classrooms.id')
                        ->where('timetables.school_id', $school->id)
                        ->where('classrooms.id', $child->classroom_id)
                        ->orderBy('timetables.Day')
                        ->orderBy('timetables.Time_start')
                        ->select('timetables.*', 'subjects.name as subject_name')
                        ->get()
                        ->groupBy('Day');
        
        return view('parent.children.timetable', compact('child', 'timetable', 'school'));
    }

    public function childHomework($childId)
    {
        $parentId = session('parent_id');
        $parent = DB::table('parents')->where('id', $parentId)->first();
        
        if (!$parent) {
            session()->forget(['parent_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'Parent session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['parent_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'School session expired. Please login again.']);
        }
        
        // Verify the child belongs to this parent
        $child = DB::table('students')
                   ->where('id', $childId)
                   ->where('parent_id', $parent->id)
                   ->where('school_id', $school->id)
                   ->first();
        
        if (!$child) {
            return redirect()->route('parent.children.index')->withErrors(['error' => 'Child not found.']);
        }
        
        // Placeholder for homework - you'll need to create this model
        $homework = collect();
        
        return view('parent.children.homework', compact('child', 'homework', 'school'));
    }

    public function childProfile($childId)
    {
        $parentId = session('parent_id');
        $parent = DB::table('parents')->where('id', $parentId)->first();
        
        if (!$parent) {
            session()->forget(['parent_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'Parent session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['parent_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'School session expired. Please login again.']);
        }
        
        // Verify the child belongs to this parent
        $child = DB::table('students')
                   ->where('id', $childId)
                   ->where('parent_id', $parent->id)
                   ->where('school_id', $school->id)
                   ->first();
        
        if (!$child) {
            return redirect()->route('parent.children.index')->withErrors(['error' => 'Child not found.']);
        }
        
        return view('parent.children.profile', compact('child', 'school'));
    }

    public function myProfile()
    {
        $parentId = session('parent_id');
        $parent = DB::table('parents')->where('id', $parentId)->first();
        
        if (!$parent) {
            session()->forget(['parent_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'Parent session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['parent_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'School session expired. Please login again.']);
        }
        
        return view('parent.profile.index', compact('parent', 'school'));
    }

    public function updateProfile(Request $request)
    {
        $parentId = session('parent_id');
        $parent = DB::table('parents')->where('id', $parentId)->first();
        
        if (!$parent) {
            session()->forget(['parent_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'Parent session expired. Please login again.']);
        }
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:parents,email,' . $parentId,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        DB::table('parents')
            ->where('id', $parentId)
            ->update($request->only(['first_name', 'last_name', 'email', 'phone', 'address']));

        // Update session data
        session(['user_name' => $request->first_name . ' ' . $request->last_name]);
        session(['user_email' => $request->email]);

        return redirect()->route('parent.profile.index')
                        ->with('success', 'Profile updated successfully.');
    }

    public function childrenTimetable()
    {
        $parentId = session('parent_id');
        $parent = DB::table('parents')->where('id', $parentId)->first();
        
        if (!$parent) {
            session()->forget(['parent_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'Parent session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['parent_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'School session expired. Please login again.']);
        }
        
        // Get all children of this parent with classroom information
        $children = DB::table('students')
                      ->leftJoin('classrooms', 'students.classroom_id', '=', 'classrooms.id')
                      ->where('students.parent_id', $parent->id)
                      ->where('school_id', $school->id)
                      ->select('students.*', 'classrooms.name as classroom_name')
                      ->get();
        
        // Get timetables for all children
        $timetables = collect();
        foreach ($children as $child) {
            $childTimetable = DB::table('timetables')
                                ->join('subjects', 'timetables.subject_id', '=', 'subjects.id')
                                ->join('classrooms', 'subjects.classroom_id', '=', 'classrooms.id')
                                ->where('timetables.school_id', $school->id)
                                ->where('classrooms.id', $child->classroom_id)
                                ->orderBy('timetables.Day')
                                ->orderBy('timetables.Time_start')
                                ->select('timetables.*', 'subjects.name as subject_name')
                                ->get()
                                ->groupBy('Day');
            
            $timetables->put($child->id, $childTimetable);
        }
        
        return view('parent.children.timetable-overview', compact('children', 'timetables', 'school'));
    }

    public function childrenHomework()
    {
        $parentId = session('parent_id');
        $parent = DB::table('parents')->where('id', $parentId)->first();
        
        if (!$parent) {
            session()->forget(['parent_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'Parent session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['parent_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'School session expired. Please login again.']);
        }
        
        // Get all children of this parent with classroom information
        $children = DB::table('students')
                      ->leftJoin('classrooms', 'students.classroom_id', '=', 'classrooms.id')
                      ->where('students.parent_id', $parent->id)
                      ->where('school_id', $school->id)
                      ->select('students.*', 'classrooms.name as classroom_name')
                      ->get();
        
        // Placeholder for homework - you'll need to create this model
        $homework = collect();
        
        return view('parent.children.homework-overview', compact('children', 'homework', 'school'));
    }

    public function childrenGrades()
    {
        $parentId = session('parent_id');
        $parent = DB::table('parents')->where('id', $parentId)->first();
        
        if (!$parent) {
            session()->forget(['parent_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'Parent session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['parent_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'School session expired. Please login again.']);
        }
        
        // Get all children of this parent with classroom information
        $children = DB::table('students')
                      ->leftJoin('classrooms', 'students.classroom_id', '=', 'classrooms.id')
                      ->where('students.parent_id', $parent->id)
                      ->where('school_id', $school->id)
                      ->select('students.*', 'classrooms.name as classroom_name')
                      ->get();
        
        $childrenIds = $children->pluck('id')->toArray();
        
        // Get recent grades for all children
        $recentGrades = DB::table('grades')
                          ->join('students', 'grades.student_id', '=', 'students.id')
                          ->join('subjects', 'grades.subject_id', '=', 'subjects.id')
                          ->whereIn('grades.student_id', $childrenIds)
                          ->where('grades.school_id', $school->id)
                          ->orderBy('grades.grading_date', 'desc')
                          ->limit(20)
                          ->select('grades.*', 'students.first_name', 'students.last_name', 'subjects.name as subject_name')
                          ->get();
        
        // Calculate GPA for each child
        $childrenGPA = collect();
        foreach ($children as $child) {
            $childGrades = DB::table('grades')
                            ->where('student_id', $child->id)
                            ->where('school_id', $school->id)
                            ->get();
            $gpa = $childGrades->avg('value');
            $childrenGPA->put($child->id, $gpa);
        }
        
        return view('parent.children.grades-overview', compact('children', 'recentGrades', 'childrenGPA', 'school'));
    }

    public function childrenAttendance()
    {
        $parentId = session('parent_id');
        $parent = DB::table('parents')->where('id', $parentId)->first();
        
        if (!$parent) {
            session()->forget(['parent_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'Parent session expired. Please login again.']);
        }
        
        $school = DB::table('schools')->where('id', session('school_id'))->first();
        if (!$school) {
            session()->forget(['parent_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect()->route('login')->withErrors(['email' => 'School session expired. Please login again.']);
        }
        
        // Get all children of this parent with classroom information
        $children = DB::table('students')
                      ->leftJoin('classrooms', 'students.classroom_id', '=', 'classrooms.id')
                      ->where('students.parent_id', $parent->id)
                      ->where('school_id', $school->id)
                      ->select('students.*', 'classrooms.name as classroom_name')
                      ->get();
        
        // Get attendance summary for all children
        $attendanceSummary = collect();
        foreach ($children as $child) {
            $childAttendance = DB::table('attendances')
                                ->where('student_id', $child->id)
                                ->where('school_id', $school->id)
                                ->selectRaw('
                                    COUNT(*) as total,
                                    SUM(CASE WHEN Status = 1 THEN 1 ELSE 0 END) as present,
                                    SUM(CASE WHEN Status = 0 THEN 1 ELSE 0 END) as absent
                                ')
                                ->first();
            
            $attendanceSummary->put($child->id, $childAttendance);
        }
        
        return view('parent.children.attendance-overview', compact('children', 'attendanceSummary', 'school'));
    }
} 