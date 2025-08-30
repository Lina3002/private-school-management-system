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
                    My Children
                    <div class="page-title-subheading">Monitor and manage your children's academic information.</div>
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('parent.dashboard') }}" class="btn btn-secondary btn-lg">
                    <i class="fa fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Children Overview Cards -->
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
                                <div class="widget-numbers text-info">{{ $child->attendances_count ?? 0 }}</div>
                                <div class="widget-subheading">Attendance</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('parent.children.grades', $child->id) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-graduation-cap mr-1"></i>Grades
                            </a>
                            <a href="{{ route('parent.children.attendance', $child->id) }}" class="btn btn-success btn-sm">
                                <i class="fa fa-check mr-1"></i>Attendance
                            </a>
                            <a href="{{ route('parent.children.timetable', $child->id) }}" class="btn btn-info btn-sm">
                                <i class="fa fa-clock mr-1"></i>Timetable
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Quick Actions -->
    <div class="row">
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
                            <a href="{{ route('parent.children.grades-overview') }}" class="btn btn-primary btn-block">
                                <i class="fa fa-medal mr-2"></i>All Grades
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="{{ route('parent.children.attendance-overview') }}" class="btn btn-success btn-block">
                                <i class="fa fa-check mr-2"></i>All Attendance
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="{{ route('parent.children.timetable-overview') }}" class="btn btn-info btn-block">
                                <i class="fa fa-clock mr-2"></i>All Timetables
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="{{ route('parent.children.homework-overview') }}" class="btn btn-warning btn-block">
                                <i class="fa fa-book mr-2"></i>All Homework
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-graph icon-gradient bg-ripe-malin"></i>
                        Summary
                    </div>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="widget-numbers text-primary">{{ $children->count() }}</div>
                            <div class="widget-subheading">Total Children</div>
                        </div>
                        <div class="col-6">
                            <div class="widget-numbers text-success">{{ $children->where('classroom_id', '!=', null)->count() }}</div>
                            <div class="widget-subheading">Enrolled</div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <div class="progress">
                            @php
                                $enrolledRate = $children->count() > 0 ? ($children->where('classroom_id', '!=', null)->count() / $children->count()) * 100 : 0;
                            @endphp
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $enrolledRate }}%" aria-valuenow="{{ $enrolledRate }}" aria-valuemin="0" aria-valuemax="100">
                                {{ round($enrolledRate, 1) }}%
                            </div>
                        </div>
                        <small class="text-muted">Enrollment Rate</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($children->count() === 0)
    <!-- No Children Message -->
    <div class="main-card mb-3 card">
        <div class="card-body text-center">
            <i class="fa fa-users fa-5x text-muted mb-3"></i>
            <h4 class="text-muted">No Children Found</h4>
            <p class="text-muted">It appears you don't have any children registered in the system yet.</p>
            <p class="text-muted">Please contact the school administration to register your children.</p>
        </div>
    </div>
    @endif
</div>
@endsection
