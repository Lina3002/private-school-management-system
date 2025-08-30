<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $attendanceRecords = Attendance::where('school_id', $school->id)
                               ->with(['student', 'subject'])
                               ->orderBy('attendancy_date', 'desc')
                               ->paginate(25);
        
        return view('admin.attendance.index', compact('attendanceRecords', 'school'));
    }

    public function create()
    {
        $school = Auth::user()->school;
        $subjects = Subject::where('school_id', $school->id)->get();
        $students = Student::where('school_id', $school->id)->get();
        
        return view('admin.attendance.create', compact('school', 'subjects', 'students'));
    }

    public function store(Request $request)
    {
        $school = Auth::user()->school;
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'attendancy_date' => 'required|date',
            'Status' => 'required|boolean',
            'justification' => 'nullable|string',
        ]);

        $attendance = Attendance::create([
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id,
            'attendancy_date' => $request->attendancy_date,
            'Status' => $request->Status,
            'justification' => $request->justification,
            'school_id' => $school->id,
        ]);

        return redirect()->route('admin.attendance.index')
                        ->with('success', 'Attendance marked successfully.');
    }

    public function show(Attendance $attendance)
    {
        $this->authorizeAttendance($attendance);
        $attendance->load(['student', 'subject']);
        
        return view('admin.attendance.show', compact('attendance'));
    }

    public function edit(Attendance $attendance)
    {
        $this->authorizeAttendance($attendance);
        $school = Auth::user()->school;
        $subjects = Subject::where('school_id', $school->id)->get();
        $students = Student::where('school_id', $school->id)->get();
        
        return view('admin.attendance.edit', compact('attendance', 'school', 'subjects', 'students'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $this->authorizeAttendance($attendance);
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'attendancy_date' => 'required|date',
            'Status' => 'required|boolean',
            'justification' => 'nullable|string',
        ]);

        $attendance->update($request->only(['student_id', 'subject_id', 'attendancy_date', 'Status', 'justification']));

        return redirect()->route('admin.attendance.index')
                        ->with('success', 'Attendance updated successfully.');
    }

    public function destroy(Attendance $attendance)
    {
        $this->authorizeAttendance($attendance);
        $attendance->delete();

        return redirect()->route('admin.attendance.index')
                        ->with('success', 'Attendance record deleted successfully.');
    }

    public function bySubject(Subject $subject)
    {
        $this->authorizeSubject($subject);
        $date = request('date', now()->format('Y-m-d'));
        
        $students = Student::where('school_id', Auth::user()->school_id)->get();
        $attendance = Attendance::where('subject_id', $subject->id)
                               ->whereDate('attendancy_date', $date)
                               ->where('school_id', Auth::user()->school_id)
                               ->pluck('Status', 'student_id')
                               ->toArray();
        
        return view('admin.attendance.by-subject', compact('subject', 'students', 'attendance', 'date'));
    }

    public function mark(Request $request)
    {
        $school = Auth::user()->school;
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'attendancy_date' => 'required|date',
            'Status' => 'required|boolean',
            'justification' => 'nullable|string',
        ]);

        // Check if attendance already exists for this student, subject, and date
        $existingAttendance = Attendance::where('student_id', $request->student_id)
                                      ->where('subject_id', $request->subject_id)
                                      ->whereDate('attendancy_date', $request->attendancy_date)
                                      ->where('school_id', $school->id)
                                      ->first();

        if ($existingAttendance) {
            $existingAttendance->update([
                'Status' => $request->Status,
                'justification' => $request->justification,
            ]);
        } else {
            Attendance::create([
                'student_id' => $request->student_id,
                'subject_id' => $request->subject_id,
                'attendancy_date' => $request->attendancy_date,
                'Status' => $request->Status,
                'justification' => $request->justification,
                'school_id' => $school->id,
            ]);
        }

        return redirect()->back()->with('success', 'Attendance marked successfully.');
    }

    public function bulkMark(Request $request)
    {
        $school = Auth::user()->school;
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'attendancy_date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.Status' => 'required|boolean',
            'attendance.*.justification' => 'nullable|string',
        ]);

        foreach ($request->attendance as $attendanceData) {
            $existingAttendance = Attendance::where('student_id', $attendanceData['student_id'])
                                          ->where('subject_id', $request->subject_id)
                                          ->whereDate('attendancy_date', $request->attendancy_date)
                                          ->where('school_id', $school->id)
                                          ->first();

            if ($existingAttendance) {
                $existingAttendance->update([
                    'Status' => $attendanceData['Status'],
                    'justification' => $attendanceData['justification'] ?? $existingAttendance->justification,
                ]);
            } else {
                Attendance::create([
                    'student_id' => $attendanceData['student_id'],
                    'subject_id' => $request->subject_id,
                    'attendancy_date' => $request->attendancy_date,
                    'Status' => $attendanceData['Status'],
                    'justification' => $attendanceData['justification'] ?? null,
                    'school_id' => $school->id,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Attendance marked for all students successfully.');
    }

    public function byClassroom($classroomId, Request $request)
    {
        $school = Auth::user()->school;
        $classroom = \App\Models\Classroom::where('id', $classroomId)
                                         ->where('school_id', $school->id)
                                         ->firstOrFail();
        
        $date = $request->get('date', date('Y-m-d'));
        $students = Student::where('classroom_id', $classroomId)
                          ->where('school_id', $school->id)
                          ->orderBy('first_name')
                          ->get();
        
        // Get existing attendance for this date and classroom
        $existingAttendance = Attendance::where('attendancy_date', $date)
                                       ->whereIn('student_id', $students->pluck('id'))
                                       ->where('school_id', $school->id)
                                       ->get()
                                       ->keyBy('student_id');
        
        return view('admin.attendance.by-classroom', compact('classroom', 'students', 'date', 'existingAttendance'));
    }

    public function markClassAttendance(Request $request)
    {
        $school = Auth::user()->school;
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'date' => 'required|date',
            'attendance_data' => 'required|array',
            'attendance_data.*.student_id' => 'required|exists:students,id',
            'attendance_data.*.status' => 'required|boolean',
            'attendance_data.*.justification' => 'nullable|string',
        ]);

        // Get the first subject for this classroom (you might want to make this more specific)
        $subject = \App\Models\Subject::where('classroom_id', $request->classroom_id)
                                     ->where('school_id', $school->id)
                                     ->first();

        if (!$subject) {
            return response()->json(['success' => false, 'message' => 'No subject found for this class.'], 400);
        }

        foreach ($request->attendance_data as $data) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $data['student_id'],
                    'subject_id' => $subject->id,
                    'attendancy_date' => $request->date,
                    'school_id' => $school->id,
                ],
                [
                    'Status' => $data['status'],
                    'justification' => $data['justification'] ?? null,
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'Class attendance marked successfully.']);
    }

    public function getClassAttendance($classroomId, Request $request)
    {
        $school = Auth::user()->school;
        $date = $request->get('date', date('Y-m-d'));
        
        $students = Student::where('classroom_id', $classroomId)
                          ->where('school_id', $school->id)
                          ->orderBy('first_name')
                          ->get();
        
        $attendance = Attendance::where('attendancy_date', $date)
                               ->whereIn('student_id', $students->pluck('id'))
                               ->where('school_id', $school->id)
                               ->get()
                               ->keyBy('student_id');
        
        return response()->json([
            'students' => $students,
            'attendance' => $attendance
        ]);
    }

    private function authorizeAttendance(Attendance $attendance)
    {
        if ($attendance->school_id !== Auth::user()->school_id) {
            abort(403, 'Unauthorized action.');
        }
    }

    private function authorizeSubject(Subject $subject)
    {
        if ($subject->school_id !== Auth::user()->school_id) {
            abort(403, 'Unauthorized action.');
        }
    }
} 