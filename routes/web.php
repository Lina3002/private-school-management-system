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

// Admin routes
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\GradeController;
use App\Http\Controllers\Admin\TimetableController;
use App\Http\Controllers\Admin\TransportController;
use App\Http\Controllers\Admin\AttendanceController;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::put('/school', [AdminController::class, 'updateSchool'])->name('school.update');
    
    // User Management
    Route::resource('users', AdminUserController::class);
    
    // Class Management
    Route::resource('classes', ClassController::class);
    Route::post('/classes/{class}/assign-students', [ClassController::class, 'assignStudents'])->name('classes.assign-students');
    
    // Subject Management
    Route::resource('subjects', SubjectController::class);
    
    // Student Management
    Route::resource('students', StudentController::class);
    Route::get('/students/{student}/grades', [StudentController::class, 'grades'])->name('students.grades');
    Route::post('/students/{student}/assign-class', [StudentController::class, 'assignClass'])->name('students.assign-class');
    
    // Grade Management
    Route::resource('grades', GradeController::class);
    Route::get('/grades/subject/{subject}', [GradeController::class, 'bySubject'])->name('grades.by-subject');
    Route::get('/grades/student/{student}', [GradeController::class, 'byStudent'])->name('grades.by-student');
    Route::post('/grades/bulk-update', [GradeController::class, 'bulkUpdate'])->name('grades.bulk-update');
    Route::get('/grades/export-csv', [GradeController::class, 'exportCsv'])->name('grades.export-csv');
    Route::get('/grades/export-student/{student}', [GradeController::class, 'exportStudentGrades'])->name('grades.export-student');
    
    // Timetable Management
    Route::resource('timetables', TimetableController::class);
    Route::get('/timetables/subject/{subject}', [TimetableController::class, 'bySubject'])->name('timetables.by-subject');
    Route::get('/timetables/staff/{staff}', [TimetableController::class, 'byStaff'])->name('timetables.by-staff');
    Route::get('/timetables/classroom/{classroom}', [TimetableController::class, 'byClassroom'])->name('timetables.by-classroom');
    Route::get('/timetables/visual', [TimetableController::class, 'visualTimetable'])->name('timetables.visual');
    // Route removed per requirement to avoid auto generation UI
    
    // Transportation Management
    Route::resource('transport', TransportController::class);
    Route::get('/transport/routes', [TransportController::class, 'routes'])->name('transport.routes');
    Route::get('/transport/assignments', [TransportController::class, 'assignments'])->name('transport.assignments');
    Route::post('/transport/assign-student', [TransportController::class, 'assignStudent'])->name('transport.assign-student');
    
    // Attendance Management
    Route::resource('attendance', AttendanceController::class);
    Route::get('/attendance/subject/{subject}', [AttendanceController::class, 'bySubject'])->name('attendance.by-subject');
    Route::get('/attendance/classroom/{classroom}', [AttendanceController::class, 'byClassroom'])->name('attendance.by-classroom');
    Route::post('/attendance/mark', [AttendanceController::class, 'mark'])->name('attendance.mark');
    Route::post('/attendance/bulk-mark', [AttendanceController::class, 'bulkMark'])->name('attendance.bulk-mark');
    Route::post('/attendance/mark-class', [AttendanceController::class, 'markClassAttendance'])->name('attendance.mark-class');
    Route::get('/attendance/get-class/{classroom}', [AttendanceController::class, 'getClassAttendance'])->name('attendance.get-class');
    
    // Existing routes
    Route::get('/billing', [AdminController::class, 'billing'])->name('billing');
    Route::get('/logs', [AdminController::class, 'logs'])->name('logs');
    Route::resource('settings', AdminSettingsController::class)->except(['show', 'create', 'edit']);
    
    // Profile Management
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile.index');
    Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');

    // Staff inline management (edit, delete)
    Route::get('/staff/{staff}/edit', [App\Http\Controllers\Admin\UserController::class, 'editStaff'])->name('staff.edit');
    Route::put('/staff/{staff}', [App\Http\Controllers\Admin\UserController::class, 'updateStaff'])->name('staff.update');
    Route::delete('/staff/{staff}', [App\Http\Controllers\Admin\UserController::class, 'destroyStaff'])->name('staff.destroy');
});

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

// Teacher routes
use App\Http\Controllers\Teacher\TeacherController;

Route::middleware(['teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
            Route::get('/classes', [TeacherController::class, 'myClasses'])->name('classes.index');
            Route::get('/students', [TeacherController::class, 'myStudents'])->name('students.index');
            Route::get('/timetable', [TeacherController::class, 'myTimetable'])->name('timetable.index');
    
    // Attendance
    Route::get('/attendance/mark', [TeacherController::class, 'markAttendance'])->name('attendance.mark');
    Route::post('/attendance/store', [TeacherController::class, 'storeAttendance'])->name('attendance.store');
    Route::get('/attendance', [TeacherController::class, 'viewAttendance'])->name('attendance.index');
    
    // Grades
    Route::get('/grades/record', [TeacherController::class, 'recordGrade'])->name('grades.record');
    Route::post('/grades/store', [TeacherController::class, 'storeGrade'])->name('grades.store');
    Route::get('/grades', [TeacherController::class, 'viewGrades'])->name('grades.index');
    
    // Homework
    Route::get('/homework/assign', [TeacherController::class, 'assignHomework'])->name('homework.assign');
    Route::post('/homework/store', [TeacherController::class, 'storeHomework'])->name('homework.store');
    
    // Profile
    Route::get('/profile', [TeacherController::class, 'myProfile'])->name('profile.index');
    Route::put('/profile', [TeacherController::class, 'updateProfile'])->name('profile.update');
});

// Student routes
use App\Http\Controllers\Student\StudentController as StudentDashboardController;

Route::middleware(['student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'dashboard'])->name('dashboard');
Route::get('/grades', [StudentDashboardController::class, 'myGrades'])->name('grades');
Route::get('/attendance', [StudentDashboardController::class, 'myAttendance'])->name('attendance');
Route::get('/timetable', [StudentDashboardController::class, 'myTimetable'])->name('timetable');
Route::get('/classes', [StudentDashboardController::class, 'myClasses'])->name('classes');
Route::get('/homework', [StudentDashboardController::class, 'myHomework'])->name('homework');
Route::get('/profile', [StudentDashboardController::class, 'myProfile'])->name('profile.index');
Route::put('/profile', [StudentDashboardController::class, 'updateProfile'])->name('profile.update');
});

// Parent routes
use App\Http\Controllers\Parent\ParentController;

Route::middleware(['parent'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', [ParentController::class, 'dashboard'])->name('dashboard');
    Route::get('/children', [ParentController::class, 'myChildren'])->name('children.index');
    
    // Child-specific routes
    Route::get('/children/{child}/grades', [ParentController::class, 'childGrades'])->name('children.grades');
    Route::get('/children/{child}/attendance', [ParentController::class, 'childAttendance'])->name('children.attendance');
    Route::get('/children/{child}/timetable', [ParentController::class, 'childTimetable'])->name('children.timetable');
    Route::get('/children/{child}/homework', [ParentController::class, 'childHomework'])->name('children.homework');
    Route::get('/children/{child}/profile', [ParentController::class, 'childProfile'])->name('children.profile');
    
    // General children routes (for overview pages)
    Route::get('/children/timetable', [ParentController::class, 'childrenTimetable'])->name('children.timetable-overview');
    Route::get('/children/homework', [ParentController::class, 'childrenHomework'])->name('children.homework-overview');
    Route::get('/children/grades', [ParentController::class, 'childrenGrades'])->name('children.grades-overview');
    Route::get('/children/attendance', [ParentController::class, 'childrenAttendance'])->name('children.attendance-overview');
    
    Route::get('/profile', [ParentController::class, 'myProfile'])->name('profile.index');
    Route::put('/profile', [ParentController::class, 'updateProfile'])->name('profile.update');
});

// Staff routes (for non-teacher, non-driver staff)
Route::middleware(['staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Staff\StaffController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [\App\Http\Controllers\Staff\StaffController::class, 'myProfile'])->name('profile.index');
    Route::put('/profile', [\App\Http\Controllers\Staff\StaffController::class, 'updateProfile'])->name('profile.update');
});

// Driver routes
use App\Http\Controllers\Driver\DriverController;

Route::middleware(['driver'])->prefix('driver')->name('driver.')->group(function () {
    Route::get('/dashboard', [DriverController::class, 'dashboard'])->name('dashboard');
    Route::get('/routes', [DriverController::class, 'myRoutes'])->name('routes');
    Route::get('/routes/{route}', [DriverController::class, 'routeDetails'])->name('routes.show');
    Route::get('/routes/{route}/map', [DriverController::class, 'routeMap'])->name('routes.map');
    Route::get('/students', [DriverController::class, 'myStudents'])->name('students');
    
    // Transport attendance
    Route::get('/attendance/mark', [DriverController::class, 'markTransportAttendance'])->name('attendance.mark');
    Route::post('/attendance/store', [DriverController::class, 'storeTransportAttendance'])->name('attendance.store');
    Route::get('/attendance', [DriverController::class, 'viewTransportAttendance'])->name('attendance.index');
    
    Route::get('/profile', [DriverController::class, 'myProfile'])->name('profile.index');
    Route::put('/profile', [DriverController::class, 'updateProfile'])->name('profile.update');
});