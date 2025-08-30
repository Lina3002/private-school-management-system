@extends('layouts.staff')

@section('title', 'Staff Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-header">
            <h1 class="page-title">Welcome, {{ $staff->first_name }}!</h1>
            <p class="page-subtitle">Staff Dashboard - {{ $school->name }}</p>
        </div>
    </div>
</div>

<div class="row">
    <!-- Statistics Cards -->
    <div class="col-md-4">
        <div class="card card-stats">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center text-warning">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="numbers">
                            <p class="card-category">Total Students</p>
                            <h4 class="card-title">{{ number_format($totalStudents) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-stats">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center text-info">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="numbers">
                            <p class="card-category">Total Teachers</p>
                            <h4 class="card-title">{{ number_format($totalTeachers) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-stats">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center text-success">
                            <i class="fas fa-school"></i>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="numbers">
                            <p class="card-category">Total Classes</p>
                            <h4 class="card-title">{{ number_format($totalClasses) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Staff Information</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Name:</strong> {{ $staff->first_name }} {{ $staff->last_name }}</p>
                        <p><strong>Email:</strong> {{ $staff->email }}</p>
                        <p><strong>Job Title:</strong> {{ ucfirst($staff->job_title_name) }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Phone:</strong> {{ $staff->phone ?? 'Not provided' }}</p>
                        <p><strong>Address:</strong> {{ $staff->address ?? 'Not provided' }}</p>
                        <p><strong>School:</strong> {{ $school->name }}</p>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('staff.profile.index') }}" class="btn btn-primary">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Quick Actions</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('staff.profile.index') }}" class="btn btn-outline-primary btn-block">
                            <i class="fas fa-user me-2"></i>Update Profile
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="#" class="btn btn-outline-info btn-block">
                            <i class="fas fa-calendar me-2"></i>View Schedule
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <a href="#" class="btn btn-outline-success btn-block">
                            <i class="fas fa-file-alt me-2"></i>Documents
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="#" class="btn btn-outline-warning btn-block">
                            <i class="fas fa-bell me-2"></i>Notifications
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Recent Activity</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Welcome to your staff dashboard! This is where you can manage your profile and access school information.
                </div>
                <p>As a staff member, you have access to:</p>
                <ul>
                    <li>View school statistics and information</li>
                    <li>Update your personal profile</li>
                    <li>Access school documents and resources</li>
                    <li>View your work schedule</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
