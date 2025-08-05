<?php

use Illuminate\Support\Facades\Route;


// Authentication Routes
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// User Management Routes (only for admin/manager)
use App\Http\Controllers\UserController;
Route::middleware(['auth', 'can:manage-users'])->group(function () {
    Route::get('/dashboard/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/dashboard/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/dashboard/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/dashboard/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/dashboard/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/dashboard/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// Password Reset Routes
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Home Route
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    if (auth()->user()->role && auth()->user()->role->name === 'super_admin') {
        return redirect()->route('superadmin.dashboard');
    }
    return redirect('/dashboard');
})->name('home');

// Superadmin routes
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\SuperAdmin\SchoolController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;

Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('schools', SchoolController::class);
    Route::resource('users', SuperAdminUserController::class);
    Route::resource('roles', RoleController::class);
    Route::get('/settings', [SuperAdminController::class, 'settings'])->name('settings');
    Route::get('/logs', [SuperAdminController::class, 'logs'])->name('logs');
    Route::get('/billing', [SuperAdminController::class, 'billing'])->name('billing');
});