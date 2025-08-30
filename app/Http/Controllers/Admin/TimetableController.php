<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timetable;
use App\Models\Subject;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;

class TimetableController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $timetables = Timetable::where('school_id', $school->id)
                              ->with(['subject', 'staff'])
                              ->orderBy('Day')
                              ->orderBy('Time_start')
                              ->get();
        
        return view('admin.timetables.index', compact('timetables', 'school'));
    }

    public function create()
    {
        $school = Auth::user()->school;
        $subjects = Subject::where('school_id', $school->id)->get();
        $staff = Staff::where('school_id', $school->id)->get();
        return view('admin.timetables.create', compact('school', 'subjects', 'staff'));
    }

    public function store(Request $request)
    {
        $school = Auth::user()->school;
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'staff_id' => 'required|exists:staffs,id',
            'Day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'Type' => 'required|in:Teacher,Student',
            'Time_start' => 'required|date_format:H:i',
            'Time_end' => 'required|date_format:H:i|after:Time_start',
        ]);

        // Convert full day names to abbreviated ones for database storage
        $dayMapping = [
            'Monday' => 'Mon',
            'Tuesday' => 'Tue',
            'Wednesday' => 'Wed',
            'Thursday' => 'Thu',
            'Friday' => 'Fri',
            'Saturday' => 'Sat',
            'Sunday' => 'Sun'
        ];
        
        $abbreviatedDay = $dayMapping[$request->Day] ?? $request->Day;

        // Check for conflicts
        $conflict = Timetable::where('school_id', $school->id)
                            ->where('Day', $abbreviatedDay)
                            ->where(function($query) use ($request) {
                                $query->where(function($q) use ($request) {
                                    $q->where('Time_start', '<=', $request->Time_start)
                                      ->where('Time_end', '>', $request->Time_start);
                                })->orWhere(function($q) use ($request) {
                                    $q->where('Time_start', '<', $request->Time_end)
                                      ->where('Time_end', '>=', $request->Time_end);
                                });
                            })
                            ->where(function($query) use ($request) {
                                $query->where('subject_id', $request->subject_id)
                                      ->orWhere('staff_id', $request->staff_id);
                            })
                            ->first();

        if ($conflict) {
            return back()->withErrors(['conflict' => 'There is a scheduling conflict. Please choose a different time or day.']);
        }

        $timetable = Timetable::create([
            'subject_id' => $request->subject_id,
            'staff_id' => $request->staff_id,
            'Day' => $abbreviatedDay,
            'Type' => $request->Type,
            'Time_start' => $request->Time_start,
            'Time_end' => $request->Time_end,
            'school_id' => $school->id,
        ]);

        return redirect()->route('admin.timetables.index')
                        ->with('success', 'Timetable entry created successfully.');
    }

    public function show(Timetable $timetable)
    {
        $this->authorizeTimetable($timetable);
        $timetable->load(['subject', 'staff']);
        
        return view('admin.timetables.show', compact('timetable'));
    }

    public function edit(Timetable $timetable)
    {
        $this->authorizeTimetable($timetable);
        $school = Auth::user()->school;
        $subjects = Subject::where('school_id', $school->id)->get();
        $staff = Staff::where('school_id', $school->id)->get();
        
        return view('admin.timetables.edit', compact('timetable', 'school', 'subjects', 'staff'));
    }

    public function update(Request $request, Timetable $timetable)
    {
        $this->authorizeTimetable($timetable);
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'staff_id' => 'required|exists:staffs,id',
            'Day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'Type' => 'required|in:Teacher,Student',
            'Time_start' => 'required|date_format:H:i',
            'Time_end' => 'required|date_format:H:i|after:Time_start',
        ]);

        // Convert full day names to abbreviated ones for database storage
        $dayMapping = [
            'Monday' => 'Mon',
            'Tuesday' => 'Tue',
            'Wednesday' => 'Wed',
            'Thursday' => 'Thu',
            'Friday' => 'Fri',
            'Saturday' => 'Sat',
            'Sunday' => 'Sun'
        ];
        
        $abbreviatedDay = $dayMapping[$request->Day] ?? $request->Day;

        $updateData = $request->only(['subject_id', 'staff_id', 'Type', 'Time_start', 'Time_end']);
        $updateData['Day'] = $abbreviatedDay;
        
        $timetable->update($updateData);

        return redirect()->route('admin.timetables.index')
                        ->with('success', 'Timetable entry updated successfully.');
    }

    public function destroy(Timetable $timetable)
    {
        $this->authorizeTimetable($timetable);
        $timetable->delete();

        return redirect()->route('admin.timetables.index')
                        ->with('success', 'Timetable entry deleted successfully.');
    }

    public function bySubject(Subject $subject)
    {
        $this->authorizeSubject($subject);
        $timetables = Timetable::where('subject_id', $subject->id)
                              ->where('school_id', Auth::user()->school_id)
                              ->with(['staff'])
                              ->orderBy('Day')
                              ->orderBy('Time_start')
                              ->get();
        
        return view('admin.timetables.by-subject', compact('subject', 'timetables'));
    }

    public function byStaff(Staff $staff)
    {
        $this->authorizeStaff($staff);
        $timetables = Timetable::where('staff_id', $staff->id)
                              ->where('school_id', Auth::user()->school_id)
                              ->with(['subject', 'classroom'])
                              ->orderBy('Day')
                              ->orderBy('Time_start')
                              ->get();
        
        return view('admin.timetables.by-staff', compact('staff', 'timetables'));
    }

    public function byClassroom($classroomId)
    {
        $school = Auth::user()->school;
        $classroom = \App\Models\Classroom::where('id', $classroomId)
                                         ->where('school_id', $school->id)
                                         ->firstOrFail();
        
        $timetables = Timetable::where('classroom_id', $classroomId)
                              ->where('school_id', $school->id)
                              ->with(['subject', 'staff'])
                              ->orderBy('Day')
                              ->orderBy('Time_start')
                              ->get();
        
        return view('admin.timetables.by-classroom', compact('classroom', 'timetables'));
    }

    public function visualTimetable(Request $request)
    {
        $school = Auth::user()->school;
        $type = $request->get('type', 'classroom'); // classroom or teacher
        $entityId = $request->get('entity_id');
        
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
        $timeSlots = [
            '08:00', '09:00', '10:00', '11:00', '12:00',
            '14:00', '15:00', '16:00', '17:00'
        ];
        
        $timetables = collect();
        $entity = null;
        
        if ($type === 'teacher' && $entityId) {
            $entity = Staff::where('id', $entityId)
                          ->where('school_id', $school->id)
                          ->firstOrFail();
            $timetables = Timetable::where('staff_id', $entityId)
                                  ->where('school_id', $school->id)
                                  ->with(['subject', 'classroom'])
                                  ->get();
        } elseif ($type === 'classroom' && $entityId) {
            $entity = \App\Models\Classroom::where('id', $entityId)
                                         ->where('school_id', $school->id)
                                         ->firstOrFail();
            $timetables = Timetable::where('classroom_id', $entityId)
                                  ->where('school_id', $school->id)
                                  ->with(['subject', 'staff'])
                                  ->get();
        }
        
        // Get all teachers and classrooms for dropdown
        $teachers = Staff::where('school_id', $school->id)->get();
        $classrooms = \App\Models\Classroom::where('school_id', $school->id)->get();
        
        return view('admin.timetables.visual', compact(
            'timetables', 'days', 'timeSlots', 'entity', 'type', 
            'teachers', 'classrooms'
        ));
    }

    public function generate(Request $request)
    {
        $school = Auth::user()->school;
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Simple timetable generation logic
        $subject = Subject::where('school_id', $school->id)->findOrFail($request->subject_id);
        $staff = Staff::where('school_id', $school->id)->inRandomOrder()->first();
        
        // Generate basic timetable structure
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
        $timeSlots = [
            '08:00' => '09:00',
            '09:00' => '10:00',
            '10:00' => '11:00',
            '11:00' => '12:00',
            '14:00' => '15:00',
            '15:00' => '16:00',
        ];

        foreach ($days as $day) {
            foreach ($timeSlots as $start => $end) {
                if ($staff) {
                    Timetable::create([
                        'subject_id' => $subject->id,
                        'staff_id' => $staff->id,
                        'Day' => $day,
                        'Type' => 'Teacher',
                        'Time_start' => $start,
                        'Time_end' => $end,
                        'school_id' => $school->id,
                    ]);
                }
            }
        }

        return redirect()->route('admin.timetables.by-subject', $subject)
                        ->with('success', 'Timetable generated successfully.');
    }

    private function authorizeTimetable(Timetable $timetable)
    {
        if ($timetable->school_id !== Auth::user()->school_id) {
            abort(403, 'Unauthorized action.');
        }
    }

    private function authorizeSubject(Subject $subject)
    {
        if ($subject->school_id !== Auth::user()->school_id) {
            abort(403, 'Unauthorized action.');
        }
    }

    private function authorizeStaff(Staff $staff)
    {
        if ($staff->school_id !== Auth::user()->school_id) {
            abort(403, 'Unauthorized action.');
        }
    }
} 