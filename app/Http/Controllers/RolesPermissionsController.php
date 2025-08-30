<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\JobTitle;
use App\Models\Permission;
use App\Models\School;

class RolesPermissionsController extends Controller
{
    // --- EDIT PAGES ---
    public function editRole($roleId)
    {
        $role = \App\Models\Role::with(['permissions', 'school'])->findOrFail($roleId);
        $schools = \App\Models\School::withTrashed()->get();
        $permissions = \App\Models\Permission::with('school')->get();
        return view('roles_permissions.edit_role', compact('role', 'schools', 'permissions'));
    }
    public function editJobTitle($jobTitleId)
    {
        $jobTitle = \App\Models\JobTitle::with(['permissions', 'school'])->findOrFail($jobTitleId);
        $schools = \App\Models\School::withTrashed()->get();
        $permissions = \App\Models\Permission::with('school')->get();
        return view('roles_permissions.edit_job_title', compact('jobTitle', 'schools', 'permissions'));
    }
    public function editPermission($permissionId)
    {
        $permission = \App\Models\Permission::with('school')->findOrFail($permissionId);
        $schools = \App\Models\School::withTrashed()->get();
        return view('roles_permissions.edit_permission', compact('permission', 'schools'));
    }
    // Show create role page
    public function createRole() {
        $schools = \App\Models\School::withTrashed()->get();
        return view('roles_permissions.create_role', compact('schools'));
    }
    // Show create permission page
    public function createPermission() {
        $schools = \App\Models\School::withTrashed()->get();
        return view('roles_permissions.create_permission', compact('schools'));
    }
    // Show create job title page
    public function createJobTitle() {
        $schools = \App\Models\School::withTrashed()->get();
        return view('roles_permissions.create_job_title', compact('schools'));
    }
    public function index()
    {
        $roles = Role::with(['permissions', 'school'])->get();
        $jobTitles = JobTitle::with(['permissions', 'school'])->get();
        $permissions = Permission::with('school')->get();
        $schools = School::all();
        return view('roles_permissions.index', compact('roles', 'jobTitles', 'permissions', 'schools'));
    }
    // --- JOB TITLE CRUD ---
    public function storeJobTitle(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'school_id' => 'required|exists:schools,id',
        ]);
        $jobTitle = \App\Models\JobTitle::create($validated);
        return redirect()->route('superadmin.roles_permissions.index')->with('success', 'Job title created successfully.');
    }

    public function updateJobTitle(Request $request, $jobTitleId)
    {
        $jobTitle = \App\Models\JobTitle::findOrFail($jobTitleId);
        $validated = $request->validate([
            'name' => 'required|string',
            'school_id' => 'required|exists:schools,id',
        ]);
        $jobTitle->update($validated);
        return redirect()->route('superadmin.roles_permissions.index')->with('success', 'Job title updated successfully.');
    }

    public function destroyJobTitle($jobTitleId)
    {
        $jobTitle = \App\Models\JobTitle::findOrFail($jobTitleId);
        $jobTitle->delete();
        return redirect()->route('superadmin.roles_permissions.index')->with('success', 'Job title deleted successfully.');
    }

    // --- PERMISSION CRUD ---
    public function storePermission(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'school_id' => 'required|exists:schools,id',
        ]);
        $permission = \App\Models\Permission::create($validated);
        return redirect()->route('superadmin.roles_permissions.index')->with('success', 'Permission created successfully.');
    }

    public function updatePermission(Request $request, $permissionId)
    {
        $permission = \App\Models\Permission::findOrFail($permissionId);
        $validated = $request->validate([
            'title' => 'required|string',
            'school_id' => 'required|exists:schools,id',
        ]);
        $permission->update($validated);
        return redirect()->route('superadmin.roles_permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function destroyPermission($permissionId)
    {
        $permission = \App\Models\Permission::findOrFail($permissionId);
        $permission->delete();
        return redirect()->route('superadmin.roles_permissions.index')->with('success', 'Permission deleted successfully.');
    }

    // --- ASSIGNMENT LOGIC ---
    public function assignRolePermission(Request $request)
    {
        \Log::info('assignRolePermission called', ['request' => $request->all()]);
        try {
            $validated = $request->validate([
                'role_id' => 'required|exists:roles,id',
                'permission_id' => 'required|exists:permissions,id',
                'school_id' => 'required|exists:schools,id',
                'assign' => 'required|boolean',
            ]);
            \Log::info('assignRolePermission validated', ['validated' => $validated]);
            $exists = \DB::table('controls')->where([
                'role_id' => $validated['role_id'],
                'permission_id' => $validated['permission_id'],
                'school_id' => $validated['school_id'],
            ])->exists();
            \Log::info('assignRolePermission exists', ['exists' => $exists]);
            if ($validated['assign'] && !$exists) {
                \DB::table('controls')->insert([
                    'role_id' => $validated['role_id'],
                    'permission_id' => $validated['permission_id'],
                    'school_id' => $validated['school_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                \Log::info('Inserted into controls', ['role_id' => $validated['role_id'], 'permission_id' => $validated['permission_id'], 'school_id' => $validated['school_id']]);
            } elseif (!$validated['assign'] && $exists) {
                \DB::table('controls')->where([
                    'role_id' => $validated['role_id'],
                    'permission_id' => $validated['permission_id'],
                    'school_id' => $validated['school_id'],
                ])->delete();
                \Log::info('Deleted from controls', ['role_id' => $validated['role_id'], 'permission_id' => $validated['permission_id'], 'school_id' => $validated['school_id']]);
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('assignRolePermission error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function assignJobTitlePermission(Request $request)
    {
        \Log::info('assignJobTitlePermission called', ['request' => $request->all()]);
        try {
            $validated = $request->validate([
                'job_title_id' => 'required|exists:job_titles,id',
                'permission_id' => 'required|exists:permissions,id',
                'school_id' => 'required|exists:schools,id',
                'assign' => 'required|boolean',
            ]);
            \Log::info('assignJobTitlePermission validated', ['validated' => $validated]);
            $exists = \DB::table('accesses')->where([
                'job_title_id' => $validated['job_title_id'],
                'permission_id' => $validated['permission_id'],
                'school_id' => $validated['school_id'],
            ])->exists();
            \Log::info('assignJobTitlePermission exists', ['exists' => $exists]);
            if ($validated['assign'] && !$exists) {
                \DB::table('accesses')->insert([
                    'job_title_id' => $validated['job_title_id'],
                    'permission_id' => $validated['permission_id'],
                    'school_id' => $validated['school_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                \Log::info('Inserted into accesses', ['job_title_id' => $validated['job_title_id'], 'permission_id' => $validated['permission_id'], 'school_id' => $validated['school_id']]);
            } elseif (!$validated['assign'] && $exists) {
                \DB::table('accesses')->where([
                    'job_title_id' => $validated['job_title_id'],
                    'permission_id' => $validated['permission_id'],
                    'school_id' => $validated['school_id'],
                ])->delete();
                \Log::info('Deleted from accesses', ['job_title_id' => $validated['job_title_id'], 'permission_id' => $validated['permission_id'], 'school_id' => $validated['school_id']]);
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('assignJobTitlePermission error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // --- ROLE CRUD ---
    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'in:admin,manager'],
            'school_id' => 'required|exists:schools,id',
        ], [
            'name.in' => 'The role name must be admin or manager.'
        ]);
        $role = \App\Models\Role::create($validated);
        return redirect()->route('superadmin.roles_permissions.index')->with('success', 'Role created successfully.');
    }

    public function updateRole(Request $request, $roleId)
    {
        $role = \App\Models\Role::findOrFail($roleId);
        $validated = $request->validate([
            'name' => ['required', 'in:admin,manager'],
            'school_id' => 'required|exists:schools,id',
        ], [
            'name.in' => 'The role name must be admin or manager.'
        ]);
        $role->update($validated);
        return redirect()->route('superadmin.roles_permissions.index')->with('success', 'Role updated successfully.');
    }

    public function destroyRole($roleId)
    {
        $role = \App\Models\Role::findOrFail($roleId);
        $role->delete();
        return redirect()->route('superadmin.roles_permissions.index')->with('success', 'Role deleted successfully.');
    }
}
