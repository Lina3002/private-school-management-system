@extends('layouts.student')

@section('content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-home icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    My Classes
                    <div class="page-title-subheading">View your class information and classmates.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Class Information -->
    <div class="row">
        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-home icon-gradient bg-ripe-malin"></i>
                        Class Information
                    </div>
                </div>
                <div class="card-body">
                    @if($classroom)
                        <div class="widget-content p-0 mb-3">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Class Name</div>
                                </div>
                                <div class="widget-content-right">
                                    <div class="widget-numbers text-primary">{{ $classroom->name }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="widget-content p-0 mb-3">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Level</div>
                                </div>
                                <div class="widget-content-right">
                                    <div class="widget-numbers text-success">{{ $classroom->level }}</div>
                                </div>
                            </div>
                        </div>
                        
                        @if($classroom->description)
                        <div class="widget-content p-0 mb-3">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Description</div>
                                </div>
                                <div class="widget-content-right">
                                    <div class="widget-numbers text-info">{{ $classroom->description }}</div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @else
                        <div class="text-center text-muted py-3">
                            <p>No class information available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-book icon-gradient bg-ripe-malin"></i>
                        My Subjects
                    </div>
                </div>
                <div class="card-body">
                    @if($subjects->count() > 0)
                        @foreach($subjects as $subject)
                        <div class="widget-content p-0 mb-3">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">{{ $subject->name }}</div>
                                    <div class="widget-subheading">Subject</div>
                                </div>
                                <div class="widget-content-right">
                                    <div class="widget-numbers text-warning">
                                        <i class="fa fa-book"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-3">
                            <p>No subjects assigned</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Classmates -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-users icon-gradient bg-ripe-malin"></i>
                My Classmates
            </div>
        </div>
        <div class="card-body">
            @if($classmates->count() > 0)
                <div class="row">
                    @foreach($classmates as $classmate)
                    <div class="col-md-4 col-lg-3 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <img width="80" class="rounded-circle mb-3" 
                                     src="{{ asset('kero/assets/images/avatars/' . rand(1, 10) . '.jpg') }}" 
                                     alt="Avatar">
                                <h6 class="card-title">{{ $classmate->first_name }} {{ $classmate->last_name }}</h6>
                                <p class="card-text text-muted">{{ $classmate->massar_code }}</p>
                                <p class="card-text">
                                    <small class="text-muted">
                                        @if($classmate->gender == 'M')
                                            <i class="fa fa-mars text-primary"></i> Male
                                        @elseif($classmate->gender == 'F')
                                            <i class="fa fa-venus text-danger"></i> Female
                                        @else
                                            <i class="fa fa-question text-secondary"></i> Not specified
                                        @endif
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-muted py-4">
                    <i class="fa fa-users fa-3x mb-3"></i>
                    <p>No classmates found</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Class Statistics -->
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-midnight-bloom">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Total Students</div>
                        <div class="widget-subheading">In class</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">
                            <span>{{ $classmates->count() + 1 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-arielle-smile">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Subjects</div>
                        <div class="widget-subheading">Enrolled</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $subjects->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-grow-early">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Class Level</div>
                        <div class="widget-subheading">Current</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">
                            <span>{{ $classroom->level ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-premium-dark">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">School</div>
                        <div class="widget-subheading">Institution</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">
                            <span>{{ $school->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 