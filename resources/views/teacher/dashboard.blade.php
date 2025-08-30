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
                    Teacher Dashboard
                    <div class="page-title-subheading">Welcome back, {{ session('user_name', 'Teacher') }}! Manage your classes and students.</div>
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
                        <div class="widget-heading">Subjects</div>
                        <div class="widget-subheading">Teaching</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">{{ $subjects->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-arielle-smile">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Students</div>
                        <div class="widget-subheading">Total</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">{{ $totalStudents }}</div>
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
                        <div class="widget-numbers text-white">{{ $todayClasses->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-premium-dark">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Recent Grades</div>
                        <div class="widget-subheading">Recorded</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">{{ $recentGrades->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Today's Classes -->
        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-clock icon-gradient bg-ripe-malin"></i>
                        Today's Classes
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
                                        <th>Class</th>
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
                                            <span class="badge badge-secondary">Class</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('teacher.attendance.mark', ['class' => 'class_id']) }}" class="btn btn-success btn-sm">
                                                <i class="fa fa-check mr-1"></i>Attendance
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="fa fa-clock fa-3x mb-3"></i>
                            <p>No classes scheduled for today</p>
                            <p>Enjoy your free time!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Grades -->
        <div class="col-md-6">
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
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentGrades as $grade)
                                    <tr>
                                        <td>
                                            <strong>{{ $grade->first_name }} {{ $grade->last_name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $grade->subject_name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $grade->value >= 10 ? 'success' : ($grade->value >= 7 ? 'warning' : 'danger') }}">
                                                {{ $grade->value }}/20
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ \Carbon\Carbon::parse($grade->grading_date)->format('M d, Y') }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="fa fa-medal fa-3x mb-3"></i>
                            <p>No recent grades recorded</p>
                            <p>Start grading your students' work!</p>
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
                            <a href="{{ route('teacher.classes.index') }}" class="btn btn-primary btn-block btn-lg">
                                <i class="fa fa-chalkboard mr-2"></i>My Classes
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('teacher.students.index') }}" class="btn btn-success btn-block btn-lg">
                                <i class="fa fa-users mr-2"></i>My Students
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('teacher.attendance.index') }}" class="btn btn-warning btn-block btn-lg">
                                <i class="fa fa-check mr-2"></i>Mark Attendance
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('teacher.grades.index') }}" class="btn btn-info btn-block btn-lg">
                                <i class="fa fa-medal mr-2"></i>Record Grades
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- My Subjects -->
    <div class="row">
        <div class="col-12">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-book icon-gradient bg-ripe-malin"></i>
                        My Subjects
                    </div>
                </div>
                <div class="card-body">
                    @if($subjects->count() > 0)
                        <div class="row">
                            @foreach($subjects as $subject)
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <i class="fa fa-book fa-3x text-primary mb-3"></i>
                                        <h5 class="card-title">{{ $subject->name }}</h5>
                                        <p class="card-text text-muted">{{ $subject->description ?? 'No description' }}</p>
                                        <a href="{{ route('teacher.classes.index') }}" class="btn btn-primary btn-sm">
                                            <i class="fa fa-eye mr-1"></i>View Class
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="fa fa-book fa-3x mb-3"></i>
                            <p>No subjects assigned yet</p>
                            <p>Please contact the administration to get assigned to subjects.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 