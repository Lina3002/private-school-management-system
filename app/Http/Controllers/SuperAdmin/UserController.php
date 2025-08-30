<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\School;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // List all users
    public function index(Request $request)
    {
        $schools = School::all();
        $roles = Role::where('name', '!=', 'super_admin')->get(); // Exclude superadmin from filter

        // Get all users except superadmin
        $users = User::with(['school', 'role'])
            ->whereHas('role', function($q) {
                $q->where('name', '!=', 'super_admin');
            })
            ->when($request->school_id, fn($q) => $q->where('school_id', $request->school_id))
            ->when($request->role_id, fn($q) => $q->where('role_id', $request->role_id))
            ->get();

        // Get staff accounts (teachers, bus assistants, drivers, etc.)
        $staffs = \App\Models\Staff::with(['jobTitle', 'school'])
            ->when($request->school_id, fn($q) => $q->where('school_id', $request->school_id))
            ->get();

        // Get students
        $students = \App\Models\Student::with(['school'])
            ->when($request->school_id, fn($q) => $q->where('school_id', $request->school_id))
            ->get();

        // Get parents
        $parents = \App\Models\ParentModel::with(['school'])
            ->when($request->school_id, fn($q) => $q->where('school_id', $request->school_id))
            ->get();

        // Merge all users into a single collection for the view
        $allUsers = collect();
        foreach ($users as $user) {
            $user->type = $user->role->name ?? 'user';
            $user->full_name = $user->first_name . ' ' . $user->last_name;
            $user->account_email = $user->email;
            $user->account_school = $user->school->name ?? '-';
            $allUsers->push($user);
        }
        foreach ($staffs as $staff) {
            $staff->type = $staff->jobTitle->name ?? 'staff';
            $staff->full_name = $staff->first_name . ' ' . $staff->last_name;
            $staff->account_email = $staff->email;
            $staff->account_school = $staff->school->name ?? '-';
            $allUsers->push($staff);
        }
        foreach ($students as $student) {
            $student->type = 'student';
            $student->full_name = $student->first_name . ' ' . $student->last_name;
            $student->account_email = $student->email;
            $student->account_school = $student->school->name ?? '-';
            $allUsers->push($student);
        }
        foreach ($parents as $parent) {
            $parent->type = 'parent';
            $parent->full_name = $parent->first_name . ' ' . $parent->last_name;
            $parent->account_email = $parent->email;
            $parent->account_school = $parent->school->name ?? '-';
            $allUsers->push($parent);
        }

        // Optionally sort or paginate if needed
        // $allUsers = $allUsers->sortByDesc('created_at');

        // Prepare unique user types for filter
        $userTypes = $allUsers->pluck('type')->unique()->values();

        // Filter by user type if requested
        if ($request->filled('type')) {
            $allUsers = $allUsers->filter(function($user) use ($request) {
                return $user->type === $request->type;
            })->values();
        }

        return view('users.index', [
            'users' => $allUsers,
            'schools' => $schools,
            'roles' => $roles,
            'userTypes' => $userTypes
        ]);
    }

    // Show create user form
    public function create()
    {
        $schools = \App\Models\School::query()->get(); // Load all schools
        $roles = \App\Models\Role::all();
        $jobTitles = \App\Models\JobTitle::all()->unique('name')->values(); // Only unique job titles
        return view('users.create', compact('schools', 'roles', 'jobTitles'));
    }

    // Store new user
    public function store(Request $request)
    {
        $accountType = $request->input('account_type');
        $tempPassword = str()->random(10);
        if ($accountType === 'managing') {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'school_id' => 'required|exists:schools,id',
                'role_id' => [
                    'required',
                    Rule::exists('roles', 'id'),
                ],
            ]);

            $role = Role::find($request->role_id);
            if (in_array($role->name, ['admin', 'manager'])) {
                $exists = User::where('school_id', $request->school_id)
                    ->where('role_id', $role->id)
                    ->whereNull('deleted_at')
                    ->exists();
                if ($exists) {
                    return back()->withErrors(['role_id' => 'This school already has a ' . $role->name . '.'])->withInput();
                }
            }

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'school_id' => $request->school_id,
                'role_id' => $request->role_id,
                'password' => Hash::make($tempPassword),
            ]);

            // Send notification email
            Mail::to($user->email)->send(new \App\Mail\UserCreated($user, $tempPassword));
        } elseif ($accountType === 'staff') {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:staffs,email',
                'school_id' => 'required|exists:schools,id',
                'job_title_id' => 'required|exists:job_titles,id',
            ]);

            $staff = \App\Models\Staff::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'school_id' => $request->school_id,
                'job_title_id' => $request->job_title_id,
                'password' => Hash::make($tempPassword),
                'phone' => $request->phone ?? '',
                'CIN' => $request->CIN ?? '',
                'address' => $request->address ?? '',
            ]);

            // Send notification email
            Mail::to($staff->email)->send(new \App\Mail\StaffCreated($staff, $tempPassword));
        } else {
            return back()->withErrors(['account_type' => 'Invalid account type'])->withInput();
        }

        return redirect()->route('superadmin.users.index')->with('success', 'Account created and credentials emailed.');
    }

    // Show edit user form
    public function edit(User $user)
    {
        $schools = School::all();
        $roles = Role::all();
        return view('users.edit', compact('user', 'schools', 'roles'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required','email', Rule::unique('users','email')->ignore($user->id)],
            'school_id' => 'required|exists:schools,id',
            'role_id' => [
                'required',
                Rule::exists('roles', 'id'),
            ],
        ]);

        // Enforce one admin/manager per school
        $role = Role::find($request->role_id);
        if (in_array($role->name, ['admin', 'manager'])) {
            $exists = User::where('school_id', $request->school_id)
                ->where('role_id', $role->id)
                ->where('id', '!=', $user->id)
                ->whereNull('deleted_at')
                ->exists();
            if ($exists) {
                return back()->withErrors(['role_id' => 'This school already has a ' . $role->name . '.'])->withInput();
            }
        }

        $user->update($request->only(['first_name', 'last_name', 'email', 'school_id', 'role_id']));
        return redirect()->route('superadmin.users.index')->with('success', 'User updated successfully.');
    }

    // Soft delete user
    public function destroy(User $user)
    {
        // Prevent deleting last teacher/admin/manager for school
        $roleName = $user->role->name;
        $count = User::where('school_id', $user->school_id)
            ->where('role_id', $user->role_id)
            ->whereNull('deleted_at')
            ->count();
        if (($roleName === 'teacher' && $count <= 1) || ($roleName === 'admin' && $count <= 1) || ($roleName === 'manager' && $count <= 1)) {
            return back()->withErrors(['delete' => 'Cannot delete the only ' . $roleName . ' for this school.']);
        }
        $user->delete();
        return redirect()->route('superadmin.users.index')->with('success', 'User deleted successfully.');
    }
}
