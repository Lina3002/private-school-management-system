<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $classes = Classroom::where('school_id', $school->id)
                           ->with(['subjects', 'students'])
                           ->get();
        
        return view('admin.classes.index', compact('classes', 'school'));
    }

    public function create()
    {
        $school = Auth::user()->school;
        return view('admin.classes.create', compact('school'));
    }

    public function store(Request $request)
    {
        $school = Auth::user()->school;
        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:50',
            'capacity' => 'nullable|integer|min:1|max:50',
            'academic_year' => 'nullable|string|max:20',
        ]);

        $class = Classroom::create([
            'name' => $request->name,
            'level' => $request->level,
            'capacity' => $request->capacity ?? 30,
            'academic_year' => $request->academic_year ?? '2024-2025',
            'school_id' => $school->id,
        ]);

        return redirect()->route('admin.classes.index')
                        ->with('success', 'Class created successfully.');
    }

    public function show(Classroom $class)
    {
        $this->authorizeClass($class);
        $class->load(['subjects', 'students']);
        
        return view('admin.classes.show', compact('class'));
    }

    public function edit(Classroom $class)
    {
        $this->authorizeClass($class);
        $school = Auth::user()->school;
        
        return view('admin.classes.edit', compact('class', 'school'));
    }

    public function update(Request $request, Classroom $class)
    {
        $this->authorizeClass($class);
        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:50',
            'capacity' => 'nullable|integer|min:1|max:50',
            'academic_year' => 'nullable|string|max:20',
        ]);

        $class->update($request->only(['name', 'level', 'capacity', 'academic_year']));

        return redirect()->route('admin.classes.index')
                        ->with('success', 'Class updated successfully.');
    }

    public function destroy(Classroom $class)
    {
        $this->authorizeClass($class);
        $class->delete();

        return redirect()->route('admin.classes.index')
                        ->with('success', 'Class deleted successfully.');
    }

    public function assignStudents(Request $request, Classroom $class)
    {
        $this->authorizeClass($class);
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id'
        ]);

        // Update students to assign them to this class
        Student::whereIn('id', $request->student_ids)
               ->where('school_id', Auth::user()->school_id)
               ->update(['classroom_id' => $class->id]);

        return redirect()->back()->with('success', 'Students assigned successfully.');
    }

    private function authorizeClass(Classroom $class)
    {
        if ($class->school_id !== Auth::user()->school_id) {
            abort(403, 'Unauthorized action.');
        }
    }
} 