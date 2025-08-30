<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IsDriver
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
        // Check if user is logged in as a driver
        if (!session('staff_id') || session('user_type') !== 'staff' || session('job_title') !== 'driver') {
            return redirect('/login')->withErrors(['email' => 'Access denied. Please log in as a driver.']);
        }

        // Verify the driver still exists and is active
        $driver = DB::table('staffs')
            ->join('job_titles', 'staffs.job_title_id', '=', 'job_titles.id')
            ->where('staffs.id', session('staff_id'))
            ->where('job_titles.name', 'driver')
            ->first();
        if (!$driver) {
            // Clear invalid session
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect('/login')->withErrors(['email' => 'Driver account not found. Please log in again.']);
        }

        return $next($request);
    }
} 