@extends('layouts.teacher')

@section('content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-clock icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    My Timetable
                    <div class="page-title-subheading">View your weekly teaching schedule.</div>
                </div>
            </div>
        </div>
    </div>

    @if($timetable->count() > 0)
        <div class="row">
            @foreach($timetable as $day => $classes)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="main-card mb-3 card">
                    <div class="card-header">
                        <div class="card-header-title">
                            <i class="header-icon pe-7s-clock icon-gradient bg-ripe-malin"></i>
                            {{ $day }}
                        </div>
                    </div>
                    <div class="card-body">
                        @if($classes->count() > 0)
                            @foreach($classes as $class)
                            <div class="mb-3 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>{{ $class->subject_name ?? 'N/A' }}</strong>
                                    <span class="badge badge-info">
                                        {{ \Carbon\Carbon::parse($class->Time_start)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($class->Time_end)->format('H:i') }}
                                    </span>
                                </div>
                                <div class="text-muted small">
                                    <i class="fa fa-clock mr-1"></i>
                                    Duration: {{ \Carbon\Carbon::parse($class->Time_start)->diffInMinutes(\Carbon\Carbon::parse($class->Time_end)) }} minutes
                                </div>
                                <div class="text-muted small">
                                    <i class="fa fa-chalkboard mr-1"></i>
                                    Type: {{ $class->Type ?? 'N/A' }}
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted">
                                <i class="fa fa-clock fa-2x mb-2"></i>
                                <p>No classes scheduled</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="main-card mb-3 card">
            <div class="card-body text-center">
                <i class="fa fa-clock fa-3x text-muted mb-3"></i>
                <h5>No Timetable Found</h5>
                <p class="text-muted">No timetable has been set for your classes yet.</p>
                <p class="text-muted">Please contact the administration to set up your teaching schedule.</p>
            </div>
        </div>
    @endif

    <!-- Today's Schedule -->
    @php
        $today = now()->format('l');
        $todayClasses = $timetable->get($today, collect());
    @endphp
    
    @if($todayClasses->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-calendar icon-gradient bg-ripe-malin"></i>
                        Today's Schedule ({{ $today }})
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Subject</th>
                                    <th>Duration</th>
                                    <th>Type</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todayClasses as $class)
                                <tr>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ \Carbon\Carbon::parse($class->Time_start)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($class->Time_end)->format('H:i') }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ $class->subject_name ?? 'N/A' }}</strong>
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($class->Time_start)->diffInMinutes(\Carbon\Carbon::parse($class->Time_end)) }} min
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $class->Type ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('teacher.attendance.mark') }}" class="btn btn-success btn-sm">
                                            <i class="fa fa-check mr-1"></i>Attendance
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
                            <a href="{{ route('teacher.students.index') }}" class="btn btn-success btn-block">
                                <i class="fa fa-users mr-2"></i>View Students
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('teacher.attendance.index') }}" class="btn btn-warning btn-block">
                                <i class="fa fa-check mr-2"></i>View Attendance
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


