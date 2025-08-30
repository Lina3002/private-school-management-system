@extends('layouts.teacher')

@section('content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="fa fa-users icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    My Students
                    <div class="page-title-subheading">View and manage the students in your classes.</div>
                </div>
            </div>
        </div>
    </div>

    @if($students->count() > 0)
        <div class="row">
            @foreach($students as $student)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="main-card mb-3 card">
                    <div class="card-header">
                        <div class="card-header-title">
                            <i class="header-icon fa fa-user icon-gradient bg-ripe-malin"></i>
                            {{ $student->first_name }} {{ $student->last_name }}
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $student->photo) }}" 
                                 alt="Student Photo" 
                                 class="rounded-circle" 
                                 style="width: 80px; height: 80px; object-fit: cover;"
                                 onerror="this.src='{{ asset('kero/assets/images/avatars/default.jpg') }}'">
                        </div>
                        
                        <h6 class="card-title">{{ $student->first_name }} {{ $student->last_name }}</h6>
                        <p class="text-muted">{{ $student->massar_code ?? 'N/A' }}</p>
                        
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <div class="widget-numbers text-primary">{{ $student->gender ?? 'N/A' }}</div>
                                <div class="widget-subheading">Gender</div>
                            </div>
                            <div class="col-6">
                                <div class="widget-numbers text-success">{{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->age : 'N/A' }}</div>
                                <div class="widget-subheading">Age</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('teacher.attendance.mark') }}" class="btn btn-success btn-sm">
                                <i class="fa fa-check mr-1"></i>Attendance
                            </a>
                            <a href="{{ route('teacher.grades.record') }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-medal mr-1"></i>Grades
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="main-card mb-3 card">
            <div class="card-body text-center">
                <i class="fa fa-users fa-3x text-muted mb-3"></i>
                <h5>No Students Found</h5>
                <p class="text-muted">No students are currently assigned to your classes.</p>
                <p class="text-muted">Please contact the administration to get assigned to classes with students.</p>
            </div>
        </div>
    @endif

    <!-- Quick Actions -->
    <div class="row mt-4">
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
                            <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary btn-block">
                                <i class="fa fa-arrow-left mr-2"></i>Back to Dashboard
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('teacher.classes.index') }}" class="btn btn-primary btn-block">
                                <i class="fa fa-chalkboard mr-2"></i>View Classes
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('teacher.attendance.index') }}" class="btn btn-success btn-block">
                                <i class="fa fa-check mr-2"></i>View Attendance
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('teacher.grades.index') }}" class="btn btn-info btn-block">
                                <i class="fa fa-medal mr-2"></i>View Grades
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


