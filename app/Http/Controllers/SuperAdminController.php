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

    public function logs(Request $request)
    {
        $query = ActivityLog::query()->with('user');
        if ($request->filled('user')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user . '%');
            });
        }
        if ($request->filled('action')) {
            $query->where('description', 'like', '%' . $request->action . '%');
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }
        $logs = $query->latest()->paginate(25);
        return view('superadmin.logs', compact('logs'));
    }

    public function billing(Request $request)
    {
        $schools = \App\Models\School::all();
        $query = \App\Models\Payment::query()->with('school');
        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }
        if ($request->filled('from')) {
            $query->whereDate('date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('date', '<=', $request->to);
        }
        $payments = $query->latest()->get();
        $stats = [
            'total_revenue' => $payments->sum('amount'),
            'total_profit' => $payments->where('type', 'profit')->sum('amount'),
            'total_payments' => $payments->count(),
            'total_outstanding' => $payments->where('payment_status', 'pending')->sum('amount'),
        ];
        // Chart data: payments by day
        $chartSource = clone $query;
        $paymentsByDay = \App\Models\Payment::selectRaw('DATE(date) as date, SUM(amount) as total')
    ->where(function($q) use ($request) {
        if ($request->filled('school_id')) {
            $q->where('school_id', $request->school_id);
        }
        if ($request->filled('from')) {
            $q->whereDate('date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $q->whereDate('date', '<=', $request->to);
        }
    })
    ->groupBy('date')
    ->orderBy('date')
    ->get();
        $chartLabels = $paymentsByDay->pluck('date')->toArray();
        $chartData = $paymentsByDay->pluck('total')->toArray();
        return view('superadmin.billing', compact('schools', 'stats', 'payments', 'chartLabels', 'chartData'));
    }
}
