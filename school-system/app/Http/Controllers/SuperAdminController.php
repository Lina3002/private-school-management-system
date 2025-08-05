<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\User;
use App\Models\Role;
use App\Models\Staff;
use App\Models\ActivityLog;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $schools = School::withCount(['students', 'teachers', 'staffs'])
            ->get()
            ->map(function ($school) {
                $school->profit = $school->calculateProfit();
                return $school;
            });

        $stats = [
            'total_schools' => $schools->count(),
            'total_students' => $schools->sum('students_count'),
            'total_teachers' => $schools->sum('teachers_count'),
            'total_staff' => $schools->sum('staffs_count'),
            'total_profits' => $schools->sum('profit'),
            'total_payments' => \App\Models\Payment::count(),
        ];

        // Chart: Payments by day (last 30 days)
        $paymentsByDay = \App\Models\Payment::selectRaw('DATE(date) as date, SUM(amount) as total')
            ->where('date', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        $chartLabels = $paymentsByDay->pluck('date')->toArray();
        $chartData = $paymentsByDay->pluck('total')->toArray();

        $recentActivity = ActivityLog::latest()->limit(10)->get();

        return view('superadmin.dashboard', compact('schools', 'stats', 'recentActivity', 'chartLabels', 'chartData'));
    }

    public function settings()
    {
        return view('superadmin.settings');
    }

    public function logs()
    {
        return view('superadmin.logs');
    }

    public function billing()
    {
        return view('superadmin.billing');
    }
}
