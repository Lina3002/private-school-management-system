<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $school = Auth::user()->school;
        
        // Get dashboard statistics
        $totalStudents = Student::where('school_id', $school->id)->count();
        $totalClasses = Classroom::where('school_id', $school->id)->count();
        $totalSubjects = Subject::where('school_id', $school->id)->count();
        $totalStaff = Staff::where('school_id', $school->id)->count();
        
        return view('admin.dashboard', compact(
            'school',
            'totalStudents',
            'totalClasses',
            'totalSubjects',
            'totalStaff'
        ));
    }

    public function billing()
    {
        $school = Auth::user()->school;
        $from = request('from');
        $to = request('to');

        $paymentsQuery = DB::table('payments')->where('school_id', $school->id);
        if ($from) { $paymentsQuery->whereDate('date', '>=', $from); }
        if ($to) { $paymentsQuery->whereDate('date', '<=', $to); }

        $payments = $paymentsQuery->orderBy('date', 'desc')->limit(100)->get();
        $stats = [
            'total_revenue' => (float) $paymentsQuery->clone()->sum('amount'),
            'total_profit' => (float) $paymentsQuery->clone()->where('type', 'profit')->sum('amount'),
            'total_outstanding' => (float) $paymentsQuery->clone()->where('payment_status', 'pending')->sum('amount'),
            'total_payments' => (int) $paymentsQuery->clone()->count(),
        ];

        $chartDataRows = DB::table('payments')
            ->select(DB::raw('DATE(date) as d'), DB::raw('SUM(amount) as total'))
            ->where('school_id', $school->id)
            ->when($from, fn($q)=>$q->whereDate('date','>=',$from))
            ->when($to, fn($q)=>$q->whereDate('date','<=',$to))
            ->groupBy(DB::raw('DATE(date)'))
            ->orderBy('d')
            ->get();
        $chartLabels = $chartDataRows->pluck('d');
        $chartData = $chartDataRows->pluck('total');

        return view('admin.billing', compact('school','payments','stats','chartLabels','chartData'));
    }

    public function logs()
    {
        $school = Auth::user()->school;
        $logs = DB::table('activity_logs')
            ->where('activity_logs.school_id', $school->id)
            ->orderBy('created_at','desc')
            ->limit(200)
            ->get()
            ->map(function($log){
                // Best-effort actor name resolution across known tables
                $name = DB::table('users')->where('id', $log->user_id)->value(DB::raw("CONCAT(first_name,' ',last_name)"));
                if (!$name) { $name = DB::table('staffs')->where('id', $log->user_id)->value(DB::raw("CONCAT(first_name,' ',last_name)")); }
                if (!$name) { $name = DB::table('parents')->where('id', $log->user_id)->value(DB::raw("CONCAT(first_name,' ',last_name)")); }
                if (!$name) { $name = DB::table('students')->where('id', $log->user_id)->value(DB::raw("CONCAT(first_name,' ',last_name)")); }
                $log->actor_name = $name ?: ('User #'.$log->user_id);
                return $log;
            });
        return view('admin.logs', compact('school','logs'));
    }
    
    public function profile()
    {
        $user = Auth::user();
        $school = $user->school;
        return view('admin.profile.index', compact('user', 'school'));
    }
    
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($request->only(['first_name', 'last_name', 'email', 'phone']));

        return redirect()->route('admin.profile.index')
                        ->with('success', 'Profile updated successfully.');
    }

    public function updateSchool(Request $request)
    {
        $school = Auth::user()->school;
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'school_level' => 'nullable|string|max:50',
        ]);
        $school->update($request->only(['name','email','phone','address','school_level']));

        // Log
        DB::table('activity_logs')->insert([
            'school_id' => $school->id,
            'user_id' => Auth::id(),
            'description' => 'Updated school info: '.$school->name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.settings.index')->with('success', 'School information updated.');
    }
}
