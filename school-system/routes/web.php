<?php

use Illuminate\Support\Facades\Route;


// Authentication Routes
use App\Http\Controllers\Auth\LoginController;


// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Password Reset Routes (controllers missing, routes disabled)
Route::get('password/reset', function() {
    return 'Password reset is currently disabled. Please contact the administrator.';
})->name('password.request');
// Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
// Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
// Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Home Route
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    if (auth()->user()->role && auth()->user()->role->name === 'super_admin') {
        return redirect()->route('superadmin.dashboard');
    }
    // Redirect all other users to login (or change to another route as needed)
    return redirect()->route('login');
})->name('home');

// Superadmin routes
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\SuperAdmin\SchoolController;

use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;

Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    // Roles & Permissions Management
    Route::get('/roles-permissions', [\App\Http\Controllers\RolesPermissionsController::class, 'index'])->name('roles_permissions.index');
Route::get('/roles-permissions/role/create', [\App\Http\Controllers\RolesPermissionsController::class, 'createRole'])->name('roles_permissions.role.create');
Route::get('/roles-permissions/role/{role}/edit', [\App\Http\Controllers\RolesPermissionsController::class, 'editRole'])->name('roles_permissions.role.edit');
Route::get('/roles-permissions/permission/create', [\App\Http\Controllers\RolesPermissionsController::class, 'createPermission'])->name('roles_permissions.permission.create');
Route::get('/roles-permissions/permission/{permission}/edit', [\App\Http\Controllers\RolesPermissionsController::class, 'editPermission'])->name('roles_permissions.permission.edit');
Route::get('/roles-permissions/job-title/create', [\App\Http\Controllers\RolesPermissionsController::class, 'createJobTitle'])->name('roles_permissions.job_title.create');
Route::get('/roles-permissions/job-title/{jobTitle}/edit', [\App\Http\Controllers\RolesPermissionsController::class, 'editJobTitle'])->name('roles_permissions.job_title.edit');
    Route::post('/roles-permissions/role', [\App\Http\Controllers\RolesPermissionsController::class, 'storeRole'])->name('roles_permissions.role.store');
    Route::put('/roles-permissions/role/{role}', [\App\Http\Controllers\RolesPermissionsController::class, 'updateRole'])->name('roles_permissions.role.update');
    Route::delete('/roles-permissions/role/{role}', [\App\Http\Controllers\RolesPermissionsController::class, 'destroyRole'])->name('roles_permissions.role.destroy');

    Route::post('/roles-permissions/job-title', [\App\Http\Controllers\RolesPermissionsController::class, 'storeJobTitle'])->name('roles_permissions.job_title.store');
    Route::put('/roles-permissions/job-title/{jobTitle}', [\App\Http\Controllers\RolesPermissionsController::class, 'updateJobTitle'])->name('roles_permissions.job_title.update');
    Route::delete('/roles-permissions/job-title/{jobTitle}', [\App\Http\Controllers\RolesPermissionsController::class, 'destroyJobTitle'])->name('roles_permissions.job_title.destroy');

    Route::post('/roles-permissions/permission', [\App\Http\Controllers\RolesPermissionsController::class, 'storePermission'])->name('roles_permissions.permission.store');
    Route::put('/roles-permissions/permission/{permission}', [\App\Http\Controllers\RolesPermissionsController::class, 'updatePermission'])->name('roles_permissions.permission.update');
    Route::delete('/roles-permissions/permission/{permission}', [\App\Http\Controllers\RolesPermissionsController::class, 'destroyPermission'])->name('roles_permissions.permission.destroy');

    // Assignment routes
    Route::post('/roles-permissions/assign-role-permission', [\App\Http\Controllers\RolesPermissionsController::class, 'assignRolePermission'])->name('roles_permissions.assign_role_permission');
    Route::post('/roles-permissions/assign-jobtitle-permission', [\App\Http\Controllers\RolesPermissionsController::class, 'assignJobTitlePermission'])->name('roles_permissions.assign_jobtitle_permission');

    Route::get('/superadmin/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('schools', SchoolController::class);
    Route::resource('users', SuperAdminUserController::class);
    // Route::resource('roles', RoleController::class); // Removed: replaced by RolesPermissionsController
    Route::resource('settings', \App\Http\Controllers\SettingsController::class)->except(['show', 'create', 'edit']);
    Route::get('/logs', [SuperAdminController::class, 'logs'])->name('logs');
    Route::get('/billing', [SuperAdminController::class, 'billing'])->name('billing');
});