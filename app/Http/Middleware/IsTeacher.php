<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IsTeacher
{
    public function handle(Request $request, Closure $next)
    {
        // Check if staff is logged in via session
        if (!session('staff_id') || session('user_type') !== 'staff' || session('job_title') !== 'teacher') {
            return redirect()->route('login')->withErrors(['email' => 'Access denied. Teacher login required.']);
        }

        // Verify staff still exists and is a teacher
        $staff = DB::table('staffs')
            ->join('job_titles', 'staffs.job_title_id', '=', 'job_titles.id')
            ->where('staffs.id', session('staff_id'))
            ->where('job_titles.name', 'teacher')
            ->first();
        if (!$staff) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect()->route('login')->withErrors(['email' => 'Teacher account not found. Please log in again.']);
        }

        return $next($request);
    }
} 