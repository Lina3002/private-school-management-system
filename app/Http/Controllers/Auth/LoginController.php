<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ParentModel;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        \Log::info('Login attempt', ['email' => $request->input('email')]);

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // First try to authenticate with users table (admin, superadmin, manager)
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            \Log::info('Login successful - User', ['email' => $request->input('email'), 'user_id' => Auth::id()]);

            $user = Auth::user();
            if (method_exists($user, 'hasRole')) {
                if ($user->hasRole('super_admin')) {
                    return redirect()->route('superadmin.dashboard');
                } elseif ($user->hasRole(['admin', 'manager'])) {
                    return redirect()->route('admin.dashboard');
                }
            }

            return redirect()->intended('/');
        }

        // Try to authenticate with staffs table (teachers, drivers, etc.)
        $staff = DB::table('staffs')
                   ->join('job_titles', 'staffs.job_title_id', '=', 'job_titles.id')
                   ->where('staffs.email', $credentials['email'])
                   ->select('staffs.*', 'job_titles.name as job_title_name')
                   ->first();
        
        if ($staff && Hash::check($credentials['password'], $staff->password)) {
            // Store staff info in session
            $request->session()->put('staff_id', $staff->id);
            $request->session()->put('user_type', 'staff');
            $request->session()->put('user_name', $staff->first_name . ' ' . $staff->last_name);
            $request->session()->put('user_email', $staff->email);
            $request->session()->put('school_id', $staff->school_id);
            $request->session()->put('job_title', $staff->job_title_name);

            \Log::info('Login successful - Staff', ['email' => $request->input('email'), 'staff_id' => $staff->id]);
            
            // Redirect based on job title
            if ($staff->job_title_name === 'teacher') {
                return redirect()->route('teacher.dashboard');
            } elseif ($staff->job_title_name === 'driver') {
                return redirect()->route('driver.dashboard');
            } else {
                return redirect()->route('staff.dashboard');
            }
        }

        // Try to authenticate with parents table
        $parent = ParentModel::where('email', $credentials['email'])->first();
        if ($parent && Hash::check($credentials['password'], $parent->password)) {
            // Store parent info in session
            $request->session()->put('parent_id', $parent->id);
            $request->session()->put('user_type', 'parent');
            $request->session()->put('user_name', $parent->first_name . ' ' . $parent->last_name);
            $request->session()->put('user_email', $parent->email);
            $request->session()->put('school_id', $parent->school_id);

            \Log::info('Login successful - Parent', ['email' => $request->input('email'), 'parent_id' => $parent->id]);
            return redirect()->route('parent.dashboard');
        }

        // Try to authenticate with students table
        $student = Student::where('email', $credentials['email'])->first();
        if ($student && Hash::check($credentials['password'], $student->password)) {
            // Store student info in session
            $request->session()->put('student_id', $student->id);
            $request->session()->put('user_type', 'student');
            $request->session()->put('user_name', $student->first_name . ' ' . $student->last_name);
            $request->session()->put('user_email', $student->email);
            $request->session()->put('school_id', $student->school_id);

            \Log::info('Login successful - Student', ['email' => $request->input('email'), 'student_id' => $student->id]);
            return redirect()->route('student.dashboard');
        }

        \Log::warning('Login failed', ['email' => $request->input('email')]);
        return back()->withErrors(['email' => trans('auth.failed')])->withInput($request->only('email', 'remember'));
    }

    public function logout(Request $request)
    {
        // Clear all authentication data
        Auth::logout();
        $request->session()->forget([
            'parent_id', 'staff_id', 'student_id', 
            'user_type', 'user_name', 'user_email', 
            'school_id', 'job_title'
        ]);
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
