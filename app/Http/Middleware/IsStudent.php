<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IsStudent
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
        // Check if user is logged in as a student
        if (!session('student_id') || session('user_type') !== 'student') {
            return redirect('/login')->withErrors(['email' => 'Access denied. Please log in as a student.']);
        }

        // Verify the student still exists and is active
        $student = DB::table('students')->where('id', session('student_id'))->first();
        if (!$student) {
            // Clear invalid session
            session()->forget(['student_id', 'user_type', 'user_name', 'user_email', 'school_id']);
            return redirect('/login')->withErrors(['email' => 'Student account not found. Please log in again.']);
        }

        return $next($request);
    }
} 