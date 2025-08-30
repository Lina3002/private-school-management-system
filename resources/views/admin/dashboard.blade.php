@extends('layouts.admin')

@section('admin-content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-rocket icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    School Dashboard
                    <div class="page-title-subheading">Welcome to your school management system. Monitor key metrics and access important functions.</div>
                </div>
            </div>
            <div class="page-title-actions">
                <div class="d-inline-block dropdown">
                    <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-info">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="fa fa-business-time fa-w-20"></i>
                        </span>
                        Quick Actions
                    </button>
                    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.students.create') }}" class="nav-link">
                                    <i class="nav-link-icon pe-7s-user"></i>
                                    <span>Add Student</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.classes.create') }}" class="nav-link">
                                    <i class="nav-link-icon pe-7s-home"></i>
                                    <span>Create Class</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.subjects.create') }}" class="nav-link">
                                    <i class="nav-link-icon pe-7s-book"></i>
                                    <span>Add Subject</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="card mb-3 widget-content bg-midnight-bloom">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Total Students</div>
                        <div class="widget-subheading">Enrolled this year</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $totalStudents ?? 0 }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card mb-3 widget-content bg-arielle-smile">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Total Classes</div>
                        <div class="widget-subheading">Active classes</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $totalClasses ?? 0 }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card mb-3 widget-content bg-grow-early">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Total Subjects</div>
                        <div class="widget-subheading">Taught courses</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $totalSubjects ?? 0 }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card mb-3 widget-content bg-premium-dark">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Total Staff</div>
                        <div class="widget-subheading">Teachers & staff</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $totalStaff ?? 0 }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Cards -->
    <div class="row">
        <!-- Recent Activities -->
        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-graph2 icon-gradient bg-ripe-malin"></i>
                        Recent Activities
                    </div>
                </div>
                <div class="card-body">
                    <div class="scroll-area-sm">
                        <div class="scrollbar-container">
                            <div class="vertical-time-simple vertical-without-time vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                                <div class="dot-vertical-success"></div>
                                <div class="vertical-timeline-element">
                                    <div class="vertical-timeline-element--bubble">
                                        <div class="vertical-timeline-element--content">
                                            <p>New student registration completed</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="dot-vertical-primary"></div>
                                <div class="vertical-timeline-element">
                                    <div class="vertical-timeline-element--bubble">
                                        <div class="vertical-timeline-element--content">
                                            <p>Class schedule updated for next week</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="dot-vertical-warning"></div>
                                <div class="vertical-timeline-element">
                                    <div class="vertical-timeline-element--bubble">
                                        <div class="vertical-timeline-element--content">
                                            <p>Grade reports generated for semester 1</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="dot-vertical-info"></div>
                                <div class="vertical-timeline-element">
                                    <div class="vertical-timeline-element--bubble">
                                        <div class="vertical-timeline-element--content">
                                            <p>Transportation routes optimized</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-tools icon-gradient bg-ripe-malin"></i>
                        Quick Actions
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <a href="{{ route('admin.students.create') }}" class="btn btn-primary btn-block">
                                <i class="fa fa-user-plus mr-2"></i>Add Student
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="{{ route('admin.classes.create') }}" class="btn btn-success btn-block">
                                <i class="fa fa-home mr-2"></i>Create Class
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="{{ route('admin.subjects.create') }}" class="btn btn-info btn-block">
                                <i class="fa fa-book mr-2"></i>Add Subject
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="{{ route('admin.grades.create') }}" class="btn btn-warning btn-block">
                                <i class="fa fa-graduation-cap mr-2"></i>Record Grades
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Tables Row -->
    <div class="row">
        <!-- Attendance Overview -->
        <div class="col-md-8">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-graph icon-gradient bg-ripe-malin"></i>
                        Attendance Overview
                    </div>
                </div>
                <div class="card-body">
                    <div class="widget-chart-wrapper widget-chart-wrapper-lg opacity-10 m-0">
                        <div class="widget-chart-sizes">
                            <div class="widget-numbers">
                                <span class="text-muted">Attendance overview coming soon. Please check the Attendance section for detailed reports.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="col-md-4">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-shield icon-gradient bg-ripe-malin"></i>
                        System Status
                    </div>
                </div>
                <div class="card-body">
                    <div class="widget-content">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="widget-heading">Database</div>
                            </div>
                            <div class="widget-content-right">
                                <div class="badge badge-success">Online</div>
                            </div>
                        </div>
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="widget-heading">Backup</div>
                            </div>
                            <div class="widget-content-right">
                                <div class="badge badge-info">Last: 2h ago</div>
                            </div>
                        </div>
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="widget-heading">Updates</div>
                            </div>
                            <div class="widget-content-right">
                                <div class="badge badge-warning">Available</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
