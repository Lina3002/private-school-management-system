<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IsStaff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in as a staff member
        if (!session('staff_id') || session('user_type') !== 'staff') {
            return redirect('/login')->withErrors(['email' => 'Access denied. Please log in as a staff member.']);
        }

        // Verify the staff member still exists and is active
        $staff = DB::table('staffs')->where('id', session('staff_id'))->first();
        if (!$staff) {
            // Clear invalid session
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect('/login')->withErrors(['email' => 'Staff account not found. Please log in again.']);
        }

        // Exclude teachers and drivers (they have their own middleware)
        $jobTitle = DB::table('job_titles')->where('id', $staff->job_title_id)->value('name');
        if (in_array($jobTitle, ['teacher', 'driver'])) {
            return redirect('/login')->withErrors(['email' => 'Access denied. Please use the appropriate login portal.']);
        }

        return $next($request);
    }
}
