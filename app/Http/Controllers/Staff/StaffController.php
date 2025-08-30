<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    public function dashboard()
    {
        $staffId = session('staff_id');
        $schoolId = session('school_id');

        if (!$staffId || !$schoolId) {
            return redirect('/login')->withErrors(['email' => 'Session expired. Please log in again.']);
        }

        // Get staff information with job title
        $staff = DB::table('staffs')
            ->join('job_titles', 'staffs.job_title_id', '=', 'job_titles.id')
            ->where('staffs.id', $staffId)
            ->select('staffs.*', 'job_titles.name as job_title_name')
            ->first();
        if (!$staff) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect('/login')->withErrors(['email' => 'Staff account not found. Please log in again.']);
        }

        // Get school information
        $school = DB::table('schools')->where('id', $schoolId)->first();
        if (!$school) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect('/login')->withErrors(['email' => 'School not found. Please log in again.']);
        }

        // Get basic statistics
        $totalStudents = DB::table('students')->where('school_id', $schoolId)->count();
        $totalTeachers = DB::table('staffs')
            ->join('job_titles', 'staffs.job_title_id', '=', 'job_titles.id')
            ->where('staffs.school_id', $schoolId)
            ->where('job_titles.name', 'teacher')
            ->count();
        $totalClasses = DB::table('classrooms')->where('school_id', $schoolId)->count();

        return view('staff.dashboard', compact('staff', 'school', 'totalStudents', 'totalTeachers', 'totalClasses'));
    }

    public function myProfile()
    {
        $staffId = session('staff_id');
        $schoolId = session('school_id');

        if (!$staffId || !$schoolId) {
            return redirect('/login')->withErrors(['email' => 'Session expired. Please log in again.']);
        }

        $staff = DB::table('staffs')
            ->join('job_titles', 'staffs.job_title_id', '=', 'job_titles.id')
            ->where('staffs.id', $staffId)
            ->select('staffs.*', 'job_titles.name as job_title_name')
            ->first();
        if (!$staff) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect('/login')->withErrors(['email' => 'Staff account not found. Please log in again.']);
        }

        $school = DB::table('schools')->where('id', $schoolId)->first();
        if (!$school) {
            session()->forget(['staff_id', 'user_type', 'user_name', 'user_email', 'school_id', 'job_title']);
            return redirect('/login')->withErrors(['email' => 'School not found. Please log in again.']);
        }

        return view('staff.profile.index', compact('staff', 'school'));
    }

    public function updateProfile(Request $request)
    {
        $staffId = session('staff_id');
        $schoolId = session('school_id');

        if (!$staffId || !$schoolId) {
            return redirect('/login')->withErrors(['email' => 'Session expired. Please log in again.']);
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:staffs,email,' . $staffId,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        // Update staff information
        DB::table('staffs')->where('id', $staffId)->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'updated_at' => now(),
        ]);

        // Update session data
        session(['user_name' => $request->first_name . ' ' . $request->last_name]);
        session(['user_email' => $request->email]);

        return redirect()->route('staff.profile.index')->with('success', 'Profile updated successfully!');
    }
}
