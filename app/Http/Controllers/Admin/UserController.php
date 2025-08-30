<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Staff;
use App\Models\JobTitle;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $users = User::where('school_id', $school->id)
                    ->whereHas('role', function($query) {
                        $query->where('name', '!=', 'super_admin');
                    })
                    ->with('role')
                    ->get();
        $staffs = Staff::where('school_id', $school->id)
                      ->with('jobTitle')
                      ->get();
        return view('admin.users.index', compact('users', 'staffs', 'school'));
    }

    public function create()
    {
        $school = Auth::user()->school;
        $jobTitles = JobTitle::where('school_id', $school->id)->get();
        $roles = Role::where('school_id', $school->id)
                    ->whereIn('name', ['admin', 'manager'])
                    ->get();
        return view('admin.users.create', compact('school', 'jobTitles', 'roles'));
    }

    public function store(Request $request)
    {
        $school = Auth::user()->school;
        Log::info('Admin invite attempt', [
            'admin_id' => Auth::id(),
            'school_id' => $school?->id,
            'payload' => $request->except(['_token'])
        ]);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email',
            'account_type' => ['required', Rule::in(['user','staff'])],
        ]);

        $accountType = $request->input('account_type');
        $tempPassword = str()->random(10);

        try {
            DB::beginTransaction();

            if ($accountType === 'user') {
                // Managing user: admin or manager
                $request->validate([
                    'role_id' => [
                        'required',
                        Rule::exists('roles','id')->where(fn($q) => $q->where('school_id', $school->id)),
                    ],
                    'email' => 'unique:users,email'
                ]);

                $role = Role::findOrFail($request->role_id);
                // Enforce one admin/manager per school
                if (in_array($role->name, ['admin','manager'])) {
                    $exists = User::where('school_id', $school->id)
                                  ->where('role_id', $role->id)
                                  ->whereNull('deleted_at')
                                  ->exists();
                    if ($exists) {
                        DB::rollBack();
                        return back()->withErrors(['role_id' => 'This school already has a '.$role->name.'.'])->withInput();
                    }
                }

                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name'  => $request->last_name,
                    'email'      => $request->email,
                    'school_id'  => $school->id,
                    'role_id'    => $request->role_id,
                    'password'   => Hash::make($tempPassword),
                ]);

                Log::info('Managing user created', ['user_id' => $user->id]);

                try {
                    Mail::to($user->email)->send(new \App\Mail\UserCreated($user, $tempPassword));
                    Log::info('UserCreated mail sent', ['user_id' => $user->id]);
                } catch (\Throwable $e) {
                    Log::error('Failed sending UserCreated mail', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            } else {
                // Staff account
                $request->validate([
                    'job_title_id' => [
                        'required',
                        Rule::exists('job_titles','id')->where(fn($q) => $q->where('school_id', $school->id)),
                    ],
                    'email' => 'unique:staffs,email'
                ]);

                $staff = Staff::create([
                    'first_name'   => $request->first_name,
                    'last_name'    => $request->last_name,
                    'email'        => $request->email,
                    'school_id'    => $school->id,
                    'job_title_id' => $request->job_title_id,
                    'password'     => Hash::make($tempPassword),
                    'phone'        => $request->phone ?? '',
                    'CIN'          => $request->CIN ?? '',
                    'address'      => $request->address ?? '',
                ]);

                Log::info('Staff created', ['staff_id' => $staff->id]);

                try {
                    Mail::to($staff->email)->send(new \App\Mail\StaffCreated($staff, $tempPassword));
                    Log::info('StaffCreated mail sent', ['staff_id' => $staff->id]);
                } catch (\Throwable $e) {
                    Log::error('Failed sending StaffCreated mail', [
                        'staff_id' => $staff->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Invite failed - transaction rolled back', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['invite' => 'Invite failed: '.$e->getMessage()])->withInput();
        }

        return redirect()->route('admin.users.index')->with('success', 'Account created and credentials emailed (check logs if mail didn\'t arrive).');
    }

    public function edit($id)
    {
        $school = Auth::user()->school;
        $user = User::where('school_id', $school->id)->findOrFail($id);
        $roles = Role::where('school_id', $school->id)
                    ->whereIn('name', ['admin', 'manager'])
                    ->get();
        return view('admin.users.edit', compact('user', 'school', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $school = Auth::user()->school;
        $user = User::where('school_id', $school->id)->findOrFail($id);
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role_id' => 'required|exists:roles,id',
        ]);
        
        $user->update($request->only(['first_name', 'last_name', 'email', 'role_id']));
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $school = Auth::user()->school;
        $user = User::where('school_id', $school->id)->findOrFail($id);
        
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own account.');
        }
        
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    // --- Staff inline management ---
    public function editStaff($staffId)
    {
        $school = Auth::user()->school;
        $staff = Staff::where('school_id', $school->id)->findOrFail($staffId);
        $jobTitles = JobTitle::where('school_id', $school->id)->get();
        return view('admin.users.edit-staff', compact('staff','jobTitles','school'));
    }

    public function updateStaff(Request $request, $staffId)
    {
        $school = Auth::user()->school;
        $staff = Staff::where('school_id', $school->id)->findOrFail($staffId);
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:staffs,email,' . $staff->id,
            'job_title_id' => 'required|exists:job_titles,id,school_id,' . $school->id,
        ]);
        
        $staff->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'job_title_id' => $request->job_title_id,
        ]);
        
        return redirect()->route('admin.users.index')->with('success', 'Staff account updated successfully.');
    }

    public function destroyStaff($staffId)
    {
        $school = Auth::user()->school;
        $staff = Staff::where('school_id', $school->id)->findOrFail($staffId);
        $staff->delete();
        return redirect()->route('admin.users.index')->with('success', 'Staff account deleted successfully.');
    }
}
