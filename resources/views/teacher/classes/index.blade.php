@extends('layouts.teacher')

@section('content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-chalkboard icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    My Classes
                    <div class="page-title-subheading">View and manage the classes you teach.</div>
                </div>
            </div>
        </div>
    </div>

    @if($classes->count() > 0)
        <div class="row">
            @foreach($classes as $class)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="main-card mb-3 card">
                    <div class="card-header">
                        <div class="card-header-title">
                            <i class="header-icon pe-7s-chalkboard icon-gradient bg-ripe-malin"></i>
                            {{ $class->name }}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="card-title">{{ $class->name }}</h6>
                            <p class="text-muted">{{ $class->description ?? 'No description available' }}</p>
                        </div>
                        
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <div class="widget-numbers text-primary">{{ $class->capacity ?? 'N/A' }}</div>
                                <div class="widget-subheading">Capacity</div>
                            </div>
                            <div class="col-6">
                                <div class="widget-numbers text-success">{{ $class->academic_year ?? 'N/A' }}</div>
                                <div class="widget-subheading">Academic Year</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('teacher.students.index') }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-users mr-1"></i>View Students
                            </a>
                            <a href="{{ route('teacher.attendance.mark') }}" class="btn btn-success btn-sm">
                                <i class="fa fa-check mr-1"></i>Mark Attendance
                            </a>
                            <a href="{{ route('teacher.homework.assign') }}" class="btn btn-warning btn-sm">
                                <i class="fa fa-book mr-1"></i>Create Homework
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
                <i class="fa fa-chalkboard fa-3x text-muted mb-3"></i>
                <h5>No Classes Assigned</h5>
                <p class="text-muted">You haven't been assigned to any classes yet.</p>
                <p class="text-muted">Please contact the administration to get assigned to classes.</p>
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
                            <a href="{{ route('teacher.students.index') }}" class="btn btn-primary btn-block">
                                <i class="fa fa-users mr-2"></i>View All Students
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
