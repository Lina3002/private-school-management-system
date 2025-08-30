@extends('layouts.parent')

@section('content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-users icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    Parent Dashboard
                    <div class="page-title-subheading">Welcome back, {{ $parent->first_name }}! Monitor your children's academic progress.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Children Overview -->
    <div class="row">
        @foreach($children as $child)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-user icon-gradient bg-ripe-malin"></i>
                        {{ $child->first_name }} {{ $child->last_name }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left mr-3">
                                <img width="60" class="rounded-circle" src="{{ asset('kero/assets/images/avatars/' . rand(1, 10) . '.jpg') }}" alt="">
                            </div>
                            <div class="widget-content-left">
                                <div class="widget-heading">{{ $child->first_name }} {{ $child->last_name }}</div>
                                                                 <div class="widget-subheading">{{ $child->classroom_name ?? 'No Class' }}</div>
                                <div class="widget-subheading text-muted">{{ $child->massar_code }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="widget-numbers text-primary">{{ $child->grades_count ?? 0 }}</div>
                                <div class="widget-subheading">Grades</div>
                            </div>
                            <div class="col-6">
                                @php
                                    $childAttendance = $attendanceSummary->where('student_id', $child->id)->first();
                                    $rate = $childAttendance ? round(($childAttendance->present / $childAttendance->total) * 100, 1) : 0;
                                @endphp
                                <div class="widget-numbers text-{{ $rate >= 80 ? 'success' : ($rate >= 60 ? 'warning' : 'danger') }}">
                                    {{ $rate }}%
                                </div>
                                <div class="widget-subheading">Attendance</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('parent.children.grades', $child->id) }}" class="btn btn-primary btn-sm btn-block">
                            <i class="fa fa-graduation-cap mr-1"></i>View Grades
                        </a>
                        <a href="{{ route('parent.children.attendance', $child->id) }}" class="btn btn-success btn-sm btn-block">
                            <i class="fa fa-check mr-1"></i>View Attendance
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Recent Grades -->
    <div class="row">
        <div class="col-md-8">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-medal icon-gradient bg-ripe-malin"></i>
                        Recent Grades
                    </div>
                </div>
                <div class="card-body">
                    @if($recentGrades->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Subject</th>
                                        <th>Grade</th>
                                        <th>Term</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentGrades as $grade)
                                    <tr>
                                        <td>
                                            <div class="widget-content p-0">
                                                <div class="widget-content-wrapper">
                                                    <div class="widget-content-left mr-3">
                                                        <img width="40" class="rounded-circle" src="{{ asset('kero/assets/images/avatars/' . rand(1, 10) . '.jpg') }}" alt="">
                                                    </div>
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">{{ $grade->student->first_name ?? 'N/A' }} {{ $grade->student->last_name ?? 'N/A' }}</div>
                                                        <div class="widget-subheading">{{ $grade->student->classroom->name ?? 'N/A' }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $grade->subject->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $grade->grade_color ?? 'primary' }}">
                                                {{ $grade->formatted_grade ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>{{ $grade->term ?? 'N/A' }}</td>
                                        <td>{{ $grade->grading_date ? \Carbon\Carbon::parse($grade->grading_date)->format('M d, Y') : 'N/A' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fa fa-graduation-cap fa-3x mb-3"></i>
                            <p>No grades recorded yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-graph icon-gradient bg-ripe-malin"></i>
                        Attendance Summary
                    </div>
                </div>
                <div class="card-body">
                    @if($attendanceSummary)
                        <div class="widget-content p-0 mb-3">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Total Records</div>
                                </div>
                                <div class="widget-content-right">
                                    <div class="widget-numbers text-primary">{{ $attendanceSummary->total ?? 0 }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content p-0 mb-3">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Present</div>
                                </div>
                                <div class="widget-content-right">
                                    <div class="widget-numbers text-success">{{ $attendanceSummary->present ?? 0 }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content p-0 mb-3">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Absent</div>
                                </div>
                                <div class="widget-content-right">
                                    <div class="widget-numbers text-danger">{{ $attendanceSummary->absent ?? 0 }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Overall Rate</div>
                                </div>
                                <div class="widget-content-right">
                                    @php
                                        $total = $attendanceSummary->total ?? 0;
                                        $present = $attendanceSummary->present ?? 0;
                                        $rate = $total > 0 ? round(($present / $total) * 100, 1) : 0;
                                    @endphp
                                    <div class="widget-numbers text-{{ $rate >= 80 ? 'success' : ($rate >= 60 ? 'warning' : 'danger') }}">
                                        {{ $rate }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center text-muted py-3">
                            <p>No attendance data available</p>
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
                            <a href="{{ route('parent.children.index') }}" class="btn btn-primary btn-block btn-lg">
                                <i class="fa fa-users mr-2"></i>View All Children
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('parent.children.timetable-overview') }}" class="btn btn-info btn-block btn-lg">
                                <i class="fa fa-clock mr-2"></i>View Timetables
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('parent.children.homework-overview') }}" class="btn btn-warning btn-block btn-lg">
                                <i class="fa fa-book mr-2"></i>View Homework
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('parent.profile.index') }}" class="btn btn-secondary btn-block btn-lg">
                                <i class="fa fa-user mr-2"></i>My Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 