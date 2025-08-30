@extends('layouts.student')

@section('content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-user icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    My Profile
                    <div class="page-title-subheading">View and update your personal information.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="row">
        <div class="col-md-4">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-user icon-gradient bg-ripe-malin"></i>
                        Profile Picture
                    </div>
                </div>
                <div class="card-body text-center">
                    <img width="150" class="rounded-circle mb-3" 
                         src="{{ asset('kero/assets/images/avatars/' . rand(1, 10) . '.jpg') }}" 
                         alt="Profile Picture">
                    <h5>{{ $student->first_name }} {{ $student->last_name }}</h5>
                    <p class="text-muted">{{ $student->massar_code }}</p>
                    <p class="text-muted">{{ $student->role->name ?? 'Student' }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-info icon-gradient bg-ripe-malin"></i>
                        Personal Information
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" value="{{ $student->first_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" value="{{ $student->last_name }}" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="{{ $student->email }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Massar Code</label>
                                <input type="text" class="form-control" value="{{ $student->massar_code }}" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Gender</label>
                                <input type="text" class="form-control" 
                                       value="{{ $student->gender == 'M' ? 'Male' : ($student->gender == 'F' ? 'Female' : 'Not specified') }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Birth Date</label>
                                <input type="text" class="form-control" 
                                       value="{{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('M d, Y') : 'Not specified' }}" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">City of Birth</label>
                                <input type="text" class="form-control" value="{{ $student->city_of_birth ?? 'Not specified' }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Country of Birth</label>
                                <input type="text" class="form-control" value="{{ $student->country_of_birth ?? 'Not specified' }}" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" rows="3" readonly>{{ $student->address ?? 'No address provided' }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Academic Information -->
    <div class="row">
        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-home icon-gradient bg-ripe-malin"></i>
                        Academic Information
                    </div>
                </div>
                <div class="card-body">
                    <div class="widget-content p-0 mb-3">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="widget-heading">Current Class</div>
                            </div>
                            <div class="widget-content-right">
                                <div class="widget-numbers text-primary">{{ $student->classroom->name ?? 'Not assigned' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="widget-content p-0 mb-3">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="widget-heading">Class Level</div>
                            </div>
                            <div class="widget-content-right">
                                <div class="widget-numbers text-success">{{ $student->classroom->level ?? 'Not specified' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="widget-content p-0 mb-3">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="widget-heading">School</div>
                            </div>
                            <div class="widget-content-right">
                                <div class="widget-numbers text-info">{{ $school->name ?? 'Not specified' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="widget-heading">Enrollment Date</div>
                            </div>
                            <div class="widget-content-right">
                                <div class="widget-numbers text-warning">{{ $student->created_at ? \Carbon\Carbon::parse($student->created_at)->format('M d, Y') : 'Not specified' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-phone icon-gradient bg-ripe-malin"></i>
                        Contact Information
                    </div>
                </div>
                <div class="card-body">
                    <div class="widget-content p-0 mb-3">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="widget-heading">Emergency Phone</div>
                            </div>
                            <div class="widget-content-right">
                                <div class="widget-numbers text-primary">{{ $student->emergency_phone ?? 'Not provided' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="widget-content p-0 mb-3">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="widget-heading">Driving Service</div>
                            </div>
                            <div class="widget-content-right">
                                <div class="widget-numbers text-{{ $student->driving_service ? 'success' : 'secondary' }}">
                                    {{ $student->driving_service ? 'Yes' : 'No' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="widget-content p-0 mb-3">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="widget-heading">Parent</div>
                            </div>
                            <div class="widget-content-right">
                                <div class="widget-numbers text-info">
                                    {{ $student->parent->first_name ?? 'N/A' }} {{ $student->parent->last_name ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="widget-heading">Last Updated</div>
                            </div>
                            <div class="widget-content-right">
                                <div class="widget-numbers text-warning">{{ $student->updated_at ? \Carbon\Carbon::parse($student->updated_at)->format('M d, Y H:i') : 'Never' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-tools icon-gradient bg-ripe-malin"></i>
                        Quick Actions
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('student.dashboard') }}" class="btn btn-primary btn-block btn-lg">
                                <i class="fa fa-home mr-2"></i>Back to Dashboard
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('student.grades') }}" class="btn btn-success btn-block btn-lg">
                                <i class="fa fa-graduation-cap mr-2"></i>View Grades
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('student.attendance') }}" class="btn btn-info btn-block btn-lg">
                                <i class="fa fa-check mr-2"></i>View Attendance
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('student.timetable') }}" class="btn btn-warning btn-block btn-lg">
                                <i class="fa fa-clock mr-2"></i>View Timetable
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 