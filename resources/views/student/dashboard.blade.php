@extends('layouts.student')

@section('content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-gleam icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    Student Dashboard
                    <div class="page-title-subheading">Welcome back, {{ $student->first_name }}! Here's your academic overview.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-midnight-bloom">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Current GPA</div>
                        <div class="widget-subheading">Overall average</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">
                            <span>{{ number_format($grades->avg('value'), 2) ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-arielle-smile">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Attendance Rate</div>
                        <div class="widget-subheading">This month</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">
                            @php
                                $total = $attendanceSummary->total ?? 0;
                                $present = $attendanceSummary->present ?? 0;
                                $rate = $total > 0 ? round(($present / $total) * 100, 1) : 0;
                            @endphp
                            <span>{{ $rate }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-grow-early">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Today's Classes</div>
                        <div class="widget-subheading">Scheduled</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $todayClasses->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-premium-dark">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Upcoming Homework</div>
                        <div class="widget-subheading">Due soon</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $upcomingHomework->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Classes -->
    <div class="row">
        <div class="col-md-8">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-clock icon-gradient bg-ripe-malin"></i>
                        Today's Schedule
                    </div>
                </div>
                <div class="card-body">
                    @if($todayClasses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Subject</th>
                                        <th>Teacher</th>
                                        <th>Status</th>
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
                                        <td>{{ $class->subject->name ?? 'N/A' }}</td>
                                        <td>{{ $class->staff->first_name ?? 'N/A' }} {{ $class->staff->last_name ?? 'N/A' }}</td>
                                        <td>
                                            @php
                                                $now = now();
                                                $startTime = \Carbon\Carbon::parse($class->Time_start);
                                                $endTime = \Carbon\Carbon::parse($class->Time_end);
                                                
                                                if ($now < $startTime) {
                                                    $status = 'upcoming';
                                                    $statusText = 'Upcoming';
                                                    $statusClass = 'warning';
                                                } elseif ($now >= $startTime && $now <= $endTime) {
                                                    $status = 'ongoing';
                                                    $statusText = 'Ongoing';
                                                    $statusClass = 'success';
                                                } else {
                                                    $status = 'completed';
                                                    $statusText = 'Completed';
                                                    $statusClass = 'secondary';
                                                }
                                            @endphp
                                            <span class="badge badge-{{ $statusClass }}">{{ $statusText }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fa fa-calendar fa-3x mb-3"></i>
                            <p>No classes scheduled for today</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-medal icon-gradient bg-ripe-malin"></i>
                        Recent Grades
                    </div>
                </div>
                <div class="card-body">
                    @if($grades->count() > 0)
                        @foreach($grades->take(5) as $grade)
                        <div class="widget-content p-0 mb-3">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">{{ $grade->subject->name ?? 'N/A' }}</div>
                                    <div class="widget-subheading">{{ $grade->term ?? 'N/A' }}</div>
                                </div>
                                <div class="widget-content-right">
                                    <div class="widget-numbers text-{{ $grade->grade_color ?? 'primary' }}">
                                        {{ $grade->formatted_grade ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <div class="text-center mt-3">
                            <a href="{{ route('student.grades') }}" class="btn btn-primary btn-sm">View All Grades</a>
                        </div>
                    @else
                        <div class="text-center text-muted py-3">
                            <p>No grades recorded yet</p>
                        </div>
                    @endif
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
                            <a href="{{ route('student.grades') }}" class="btn btn-primary btn-block btn-lg">
                                <i class="fa fa-graduation-cap mr-2"></i>View Grades
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('student.attendance') }}" class="btn btn-success btn-block btn-lg">
                                <i class="fa fa-check mr-2"></i>Check Attendance
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('student.timetable') }}" class="btn btn-info btn-block btn-lg">
                                <i class="fa fa-clock mr-2"></i>View Timetable
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('student.classes') }}" class="btn btn-warning btn-block btn-lg">
                                <i class="fa fa-home mr-2"></i>My Classes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 