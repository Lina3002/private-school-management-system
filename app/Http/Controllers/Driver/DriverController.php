<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transport;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;

class DriverController extends Controller
{
    public function dashboard()
    {
        $driverId = session('staff_id');
        if (!$driverId) {
            abort(403, 'Driver not authenticated');
        }
        $driver = Staff::with('school')->find($driverId);
        if (!$driver) {
            abort(403, 'Driver not found');
        }
        $school = $driver->school;
        if (!$school) {
            abort(500, 'Driver is not assigned to a school. Please contact admin.');
        }
        
        // Get driver's assigned routes
        $routes = Transport::where('school_id', $school->id)
                          ->where('driver_id', $driver->id)
                          ->with(['students'])
                          ->get();
        
        // Get total students to pick up
        $totalStudents = $routes->sum(function($route) {
            return $route->students->count();
        });
        
        // Get today's pickup schedule
        $todayPickups = collect();
        foreach ($routes as $route) {
            foreach ($route->students as $student) {
                $todayPickups->push([
                    'student' => $student,
                    'route' => $route,
                    'pickup_time' => '08:00', // You can customize this
                    'dropoff_time' => '15:00', // You can customize this
                ]);
            }
        }
        
        return view('driver.dashboard', compact('driver', 'school', 'routes', 'totalStudents', 'todayPickups'));
    }

    public function myRoutes()
    {
        $driverId = session('staff_id');
        if (!$driverId) {
            abort(403, 'Driver not authenticated');
        }
        $driver = Staff::with('school')->find($driverId);
        if (!$driver) {
            abort(403, 'Driver not found');
        }
        $school = $driver->school;
        if (!$school) {
            abort(500, 'Driver is not assigned to a school. Please contact admin.');
        }
        
        $routes = Transport::where('school_id', $school->id)
                          ->where('driver_id', $driver->id)
                          ->with(['students'])
                          ->get();
        
        return view('driver.routes.index', compact('routes', 'school'));
    }

    public function routeDetails($routeId)
    {
        $driverId = session('staff_id');
        if (!$driverId) {
            abort(403, 'Driver not authenticated');
        }
        $driver = Staff::with('school')->find($driverId);
        if (!$driver) {
            abort(403, 'Driver not found');
        }
        $school = $driver->school;
        if (!$school) {
            abort(500, 'Driver is not assigned to a school. Please contact admin.');
        }
        
        $route = Transport::where('id', $routeId)
                         ->where('school_id', $school->id)
                         ->where('driver_id', $driver->id)
                         ->with(['students.classroom'])
                         ->firstOrFail();
        
        // Group students by pickup location (you can customize this logic)
        $studentsByLocation = $route->students->groupBy('address');
        
        return view('driver.routes.show', compact('route', 'studentsByLocation', 'school'));
    }

    public function myStudents()
    {
        $driverId = session('staff_id');
        if (!$driverId) {
            abort(403, 'Driver not authenticated');
        }
        $driver = Staff::with('school')->find($driverId);
        if (!$driver) {
            abort(403, 'Driver not found');
        }
        $school = $driver->school;
        if (!$school) {
            abort(500, 'Driver is not assigned to a school. Please contact admin.');
        }
        
        $students = Student::where('school_id', $school->id)
            ->whereHas('transport', function($query) use ($driver) {
                $query->where('driver_id', $driver->id);
            })
            ->with(['classroom', 'transport'])
            ->get();
        
        return view('driver.students.index', compact('students', 'school'));
    }

    public function markTransportAttendance()
    {
        $driverId = session('staff_id');
        if (!$driverId) {
            abort(403, 'Driver not authenticated');
        }
        $driver = Staff::with('school')->find($driverId);
        if (!$driver) {
            abort(403, 'Driver not found');
        }
        $school = $driver->school;
        if (!$school) {
            abort(500, 'Driver is not assigned to a school. Please contact admin.');
        }
        
        $routes = Transport::where('school_id', $school->id)
                          ->where('driver_id', $driver->id)
                          ->with(['students'])
                          ->get();
        
        return view('driver.attendance.mark', compact('routes', 'school'));
    }

    public function storeTransportAttendance(Request $request)
    {
        $driverId = session('staff_id');
        if (!$driverId) {
            abort(403, 'Driver not authenticated');
        }
        $driver = Staff::with('school')->find($driverId);
        if (!$driver) {
            abort(403, 'Driver not found');
        }
        $school = $driver->school;
        if (!$school) {
            abort(500, 'Driver is not assigned to a school. Please contact admin.');
        }
        
        $request->validate([
            'route_id' => 'required|exists:transports,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.status' => 'required|in:present,absent,late',
            'attendance.*.pickup_time' => 'nullable|date_format:H:i',
            'attendance.*.dropoff_time' => 'nullable|date_format:H:i',
            'attendance.*.notes' => 'nullable|string',
        ]);

        // Create transport attendance records
        foreach ($request->attendance as $record) {
            // You'll need to create a TransportAttendance model
            // For now, we'll just log the action
            \Log::info('Transport attendance marked', [
                'driver_id' => $driver->id,
                'student_id' => $record['student_id'],
                'route_id' => $request->route_id,
                'date' => $request->date,
                'status' => $record['status'],
                'pickup_time' => $record['pickup_time'] ?? null,
                'dropoff_time' => $record['dropoff_time'] ?? null,
                'notes' => $record['notes'] ?? null,
            ]);
        }

        return redirect()->route('driver.attendance.mark')
                        ->with('success', 'Transport attendance marked successfully.');
    }

    public function viewTransportAttendance()
    {
        $driverId = session('staff_id');
        if (!$driverId) {
            abort(403, 'Driver not authenticated');
        }
        $driver = Staff::with('school')->find($driverId);
        if (!$driver) {
            abort(403, 'Driver not found');
        }
        $school = $driver->school;
        if (!$school) {
            abort(500, 'Driver is not assigned to a school. Please contact admin.');
        }
        
        // Placeholder for transport attendance - you'll need to create this model
        $attendance = collect();
        
        return view('driver.attendance.index', compact('attendance', 'school'));
    }

    public function routeMap($routeId)
    {
        $driverId = session('staff_id');
        if (!$driverId) {
            abort(403, 'Driver not authenticated');
        }
        $driver = Staff::with('school')->find($driverId);
        if (!$driver) {
            abort(403, 'Driver not found');
        }
        $school = $driver->school;
        if (!$school) {
            abort(500, 'Driver is not assigned to a school. Please contact admin.');
        }
        
        $route = Transport::where('id', $routeId)
                         ->where('school_id', $school->id)
                         ->where('driver_id', $driver->id)
                         ->with(['students'])
                         ->firstOrFail();
        
        // Get coordinates for map (you can customize this)
        $coordinates = [
            'school' => ['lat' => 33.9716, 'lng' => -6.8498], // Example: Rabat, Morocco
            'stops' => $route->students->map(function($student) {
                return [
                    'name' => $student->full_name,
                    'address' => $student->address,
                    'lat' => rand(33, 34) + (rand(0, 100) / 100), // Random coordinates
                    'lng' => -6.8 + (rand(0, 100) / 100),
                ];
            })->toArray()
        ];
        
        return view('driver.routes.map', compact('route', 'coordinates', 'school'));
    }

    public function myProfile()
    {
        $driverId = session('staff_id');
        if (!$driverId) {
            abort(403, 'Driver not authenticated');
        }
        $driver = Staff::with('school')->find($driverId);
        if (!$driver) {
            abort(403, 'Driver not found');
        }
        $school = $driver->school;
        if (!$school) {
            abort(500, 'Driver is not assigned to a school. Please contact admin.');
        }
        
        return view('driver.profile.index', compact('driver', 'school'));
    }

    public function updateProfile(Request $request)
    {
        $driverId = session('staff_id');
        $driver = Staff::find($driverId);
        $driver = Auth::guard('staff')->user();
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $driver->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'license_number' => 'nullable|string|max:50',
        ]);

        $driver->update($request->only(['first_name', 'last_name', 'email', 'phone', 'address', 'license_number']));

        return redirect()->route('driver.profile.index')
                        ->with('success', 'Profile updated successfully.');
    }
} 