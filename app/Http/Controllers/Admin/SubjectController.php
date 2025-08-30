<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\Staff;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $subjects = Subject::where('school_id', $school->id)
                          ->with(['classroom'])
                          ->get();
        
        return view('admin.subjects.index', compact('subjects', 'school'));
    }

    public function create()
    {
        $school = Auth::user()->school;
        $classes = Classroom::where('school_id', $school->id)->get();
        return view('admin.subjects.create', compact('school', 'classes'));
    }

    public function store(Request $request)
    {
        $school = Auth::user()->school;
        $request->validate([
            'name' => 'required|string|max:255',
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        $subject = Subject::create([
            'name' => $request->name,
            'classroom_id' => $request->classroom_id,
            'school_id' => $school->id,
        ]);

        return redirect()->route('admin.subjects.index')
                        ->with('success', 'Subject created successfully.');
    }

    public function show(Subject $subject)
    {
        $this->authorizeSubject($subject);
        $subject->load(['classroom']);
        
        return view('admin.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        $this->authorizeSubject($subject);
        $school = Auth::user()->school;
        $classrooms = Classroom::where('school_id', $school->id)->get();
        $teachers = Staff::where('staffs.school_id', $school->id)
                         ->join('job_titles', 'staffs.job_title_id', '=', 'job_titles.id')
                         ->where('job_titles.name', 'Teacher')
                         ->select('staffs.*')
                         ->get();
        $currentTeacherId = DB::table('teaches')
            ->where('subject_id', $subject->id)
            ->value('staff_id');
        
        return view('admin.subjects.edit', compact('subject', 'school', 'classrooms', 'teachers', 'currentTeacherId'));
    }

    public function update(Request $request, Subject $subject)
    {
        $this->authorizeSubject($subject);
        $request->validate([
            'name' => 'required|string|max:255',
            'classroom_id' => 'required|exists:classrooms,id',
            'staff_id' => 'nullable|exists:staffs,id'
        ]);

        $subject->update($request->only(['name', 'classroom_id']));

        // Log update
        DB::table('activity_logs')->insert([
            'school_id' => Auth::user()->school_id,
            'user_id' => Auth::id(),
            'description' => 'Updated subject #' . $subject->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign teacher to subject via teaches
        DB::table('teaches')->where('subject_id', $subject->id)->delete();
        if ($request->filled('staff_id')) {
            DB::table('teaches')->insert([
                'staff_id' => $request->staff_id,
                'subject_id' => $subject->id,
                'school_id' => Auth::user()->school_id,
                'teaching_date' => now()->toDateString(),
                'is_current' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('admin.subjects.index')
                        ->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $this->authorizeSubject($subject);
        $deletedId = $subject->id;
        $payload = $subject->toArray();
        $subject->delete();

        // Log deletion
        DB::table('activity_logs')->insert([
            'school_id' => Auth::user()->school_id,
            'user_id' => Auth::id(),
            'description' => 'Deleted subject #' . $deletedId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.subjects.index')
                        ->with('success', 'Subject deleted successfully.');
    }

    private function authorizeSubject(Subject $subject)
    {
        if ($subject->school_id !== Auth::user()->school_id) {
            abort(403, 'Unauthorized action.');
        }
    }
} 