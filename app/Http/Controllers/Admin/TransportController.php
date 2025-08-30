<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transport;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class TransportController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $transportRoutes = Transport::where('school_id', $school->id)
                                  ->with(['students'])
                                  ->get();
        
        return view('admin.transport.index', compact('transportRoutes', 'school'));
    }

    public function create()
    {
        $school = Auth::user()->school;
        return view('admin.transport.create', compact('school'));
    }

    public function store(Request $request)
    {
        $school = Auth::user()->school;
        $request->validate([
            'Bus_number' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1|max:100',
        ]);

        $transport = Transport::create([
            'Bus_number' => $request->Bus_number,
            'capacity' => $request->capacity,
            'school_id' => $school->id,
        ]);

        return redirect()->route('admin.transport.index')
                        ->with('success', 'Transport route created successfully.');
    }

    public function show(Transport $transport)
    {
        $this->authorizeTransport($transport);
        $transport->load(['students']);
        
        return view('admin.transport.show', compact('transport'));
    }

    public function edit(Transport $transport)
    {
        $this->authorizeTransport($transport);
        $school = Auth::user()->school;
        
        return view('admin.transport.edit', compact('transport', 'school'));
    }

    public function update(Request $request, Transport $transport)
    {
        $this->authorizeTransport($transport);
        $request->validate([
            'Bus_number' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1|max:100',
        ]);

        $transport->update($request->only(['Bus_number', 'capacity']));

        return redirect()->route('admin.transport.index')
                        ->with('success', 'Transport route updated successfully.');
    }

    public function destroy(Transport $transport)
    {
        $this->authorizeTransport($transport);
        $transport->delete();

        return redirect()->route('admin.transport.index')
                        ->with('success', 'Transport route deleted successfully.');
    }

    public function routes()
    {
        $school = Auth::user()->school;
        $routes = Transport::where('school_id', $school->id)
                          ->with(['students'])
                          ->get();
        
        return view('admin.transport.routes', compact('routes', 'school'));
    }

    public function assignments()
    {
        $school = Auth::user()->school;
        $assignments = Transport::where('school_id', $school->id)
                               ->with(['students'])
                               ->get();
        
        return view('admin.transport.assignments', compact('assignments', 'school'));
    }

    public function assignStudent(Request $request, Transport $transport)
    {
        $this->authorizeTransport($transport);
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id'
        ]);

        // Check capacity
        $currentStudents = $transport->students()->count();
        $newStudents = count($request->student_ids);
        
        if (($currentStudents + $newStudents) > $transport->capacity) {
            return back()->withErrors(['capacity' => 'Transport route is at capacity. Cannot assign more students.']);
        }

        $transport->students()->syncWithoutDetaching($request->student_ids);

        return redirect()->back()->with('success', 'Students assigned to transport route successfully.');
    }

    private function authorizeTransport(Transport $transport)
    {
        if ($transport->school_id !== Auth::user()->school_id) {
            abort(403, 'Unauthorized action.');
        }
    }
} 