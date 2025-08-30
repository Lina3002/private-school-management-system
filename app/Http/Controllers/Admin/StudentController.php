<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\StudentCreated;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $students = Student::where('school_id', $school->id)
                          ->with(['classroom'])
                          ->get();
        
        return view('admin.students.index', compact('students', 'school'));
    }

    public function create()
    {
        $school = Auth::user()->school;
        $classes = Classroom::where('school_id', $school->id)->get();
        return view('admin.students.create', compact('school', 'classes'));
    }

    public function store(Request $request)
    {
        $school = Auth::user()->school;
        $request->validate([
            'massar_code' => 'required|string|max:10|unique:students,massar_code',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'email' => 'required|email|unique:students,email',
            'password' => 'required|string|min:6',
            'birth_date' => 'required|date',
            'driving_service' => 'nullable|in:0,1',
            'address' => 'nullable|string',
            'emergency_phone' => 'nullable|string|max:20',
            'city_of_birth' => 'nullable|string',
            'country_of_birth' => 'nullable|string',
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('students/photos', 'public');
        }

        $student = Student::create([
            'massar_code' => $request->massar_code,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'photo' => $photoPath,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'birth_date' => $request->birth_date,
            'driving_service' => $request->driving_service === '1',
            'address' => $request->address ?? '',
            'emergency_phone' => $request->emergency_phone ?? '',
            'city_of_birth' => $request->city_of_birth ?? '',
            'country_of_birth' => $request->country_of_birth ?? '',
            'school_id' => $school->id,
            'classroom_id' => $request->classroom_id,
        ]);

        // Send welcome email with temporary credentials
        try {
            Mail::to($student->email)->send(new StudentCreated($student, $request->password, $school));
        } catch (\Exception $e) {
            // Log the error but don't fail the student creation
            \Log::error('Failed to send welcome email to student: ' . $e->getMessage());
        }

        return redirect()->route('admin.students.index')
                        ->with('success', 'Student created successfully. Welcome email sent to ' . $student->email);
    }

    public function show(Student $student)
    {
        $this->authorizeStudent($student);
        $student->load(['classroom', 'grades', 'attendances']);
        
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $this->authorizeStudent($student);
        $school = Auth::user()->school;
        $classrooms = Classroom::where('school_id', $school->id)->get();
        
        return view('admin.students.edit', compact('student', 'school', 'classrooms'));
    }

    public function update(Request $request, Student $student)
    {
        $this->authorizeStudent($student);
        $request->validate([
            'massar_code' => 'required|string|max:10|unique:students,massar_code,' . $student->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'birth_date' => 'required|date',
            'driving_service' => 'nullable|in:0,1',
            'address' => 'nullable|string',
            'emergency_phone' => 'nullable|string|max:20',
            'city_of_birth' => 'nullable|string',
            'country_of_birth' => 'nullable|string',
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        $updateData = $request->only([
            'massar_code', 'first_name', 'last_name', 'gender', 
            'email', 'birth_date', 'address', 
            'emergency_phone', 'city_of_birth', 'country_of_birth'
        ]);

        // Handle driving_service boolean conversion
        $updateData['driving_service'] = $request->driving_service === '1';

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                Storage::disk('public')->delete($student->photo);
            }
            $updateData['photo'] = $request->file('photo')->store('students/photos', 'public');
        }

        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }

        $student->update($updateData);

        if ($request->has('classroom_id')) {
            $student->update(['classroom_id' => $request->classroom_id]);
        }

        return redirect()->route('admin.students.index')
                        ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $this->authorizeStudent($student);
        $student->delete();

        return redirect()->route('admin.students.index')
                        ->with('success', 'Student deleted successfully.');
    }

    public function grades(Student $student)
    {
        $this->authorizeStudent($student);
        $grades = Grade::where('student_id', $student->id)
                      ->where('school_id', Auth::user()->school_id)
                      ->with(['subject'])
                      ->orderBy('grading_date', 'desc')
                      ->get();
        
        return view('admin.students.grades', compact('student', 'grades'));
    }

    public function assignClass(Request $request, Student $student)
    {
        $this->authorizeStudent($student);
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id'
        ]);

        $student->update(['classroom_id' => $request->classroom_id]);

        // Populate studies junction: assign all subjects of the class to the student
        $subjectIds = \App\Models\Subject::where('classroom_id', $request->classroom_id)
            ->where('school_id', \Illuminate\Support\Facades\Auth::user()->school_id)
            ->pluck('id');
        if ($subjectIds->count() > 0) {
            $rows = $subjectIds->map(function($sid) use ($student) {
                return [
                    'student_id' => $student->id,
                    'subject_id' => $sid,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->all();
            // Remove previous studies for this student, then insert new
            \Illuminate\Support\Facades\DB::table('studies')->where('student_id', $student->id)->delete();
            \Illuminate\Support\Facades\DB::table('studies')->insert($rows);
        }

        return redirect()->back()->with('success', 'Student assigned to class successfully.');
    }

    private function authorizeStudent(Student $student)
    {
        if ($student->school_id !== Auth::user()->school_id) {
            abort(403, 'Unauthorized action.');
        }
    }
} 