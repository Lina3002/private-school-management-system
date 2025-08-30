@extends('layouts.admin')

@section('admin-content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-gleam icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    Student Grades - {{ $student->full_name }}
                    <div class="page-title-subheading">View and manage student grades.</div>
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('admin.students.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fa fa-arrow-left mr-2"></i>Back to Students
                </a>
            </div>
        </div>
    </div>

    <!-- Student Info Card -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-user icon-gradient bg-ripe-malin"></i>
                Student Information
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>Name:</strong> {{ $student->full_name }}
                </div>
                <div class="col-md-3">
                    <strong>Massar Code:</strong> {{ $student->massar_code }}
                </div>
                <div class="col-md-3">
                    <strong>Class:</strong> {{ $student->classroom ? $student->classroom->name : 'Not Assigned' }}
                </div>
                <div class="col-md-3">
                    <strong>Average Grade:</strong> 
                    @if($student->average_grade)
                        <span class="badge badge-success">{{ $student->average_grade }}/20</span>
                    @else
                        <span class="badge badge-secondary">No grades yet</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Grades Table -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-notebook icon-gradient bg-ripe-malin"></i>
                Grades History
            </div>
            <div class="card-header-actions">
                <div class="btn-group">
                    <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-download mr-1"></i>Export CSV
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('admin.grades.export-student', $student, ['term' => 'semester 1']) }}">
                            <i class="fa fa-file-csv mr-2"></i>Semester 1
                        </a>
                        <a class="dropdown-item" href="{{ route('admin.grades.export-student', $student, ['term' => 'semester 2']) }}">
                            <i class="fa fa-file-csv mr-2"></i>Semester 2
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($grades->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Grade</th>
                                <th>Max Grade</th>
                                <th>Percentage</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grades as $grade)
                                <tr>
                                    <td>
                                        <strong>{{ $grade->subject ? $grade->subject->name : 'N/A' }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $grade->value >= 10 ? 'success' : 'danger' }}">
                                            {{ $grade->value }}/20
                                        </span>
                                    </td>
                                    <td>{{ $grade->max_grade ?? 20 }}</td>
                                    <td>
                                        @php
                                            $percentage = $grade->max_grade ? round(($grade->value / $grade->max_grade) * 100, 1) : round(($grade->value / 20) * 100, 1);
                                        @endphp
                                        <span class="badge badge-{{ $percentage >= 50 ? 'success' : ($percentage >= 40 ? 'warning' : 'danger') }}">
                                            {{ $percentage }}%
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ ucfirst($grade->type ?? 'Regular') }}</span>
                                    </td>
                                    <td>{{ $grade->grading_date ? $grade->grading_date->format('M d, Y') : 'N/A' }}</td>
                                    <td>{{ $grade->comment ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="pe-7s-notebook icon-gradient bg-mean-fruit" style="font-size: 4rem; opacity: 0.5;"></i>
                    <h4 class="mt-3 text-muted">No Grades Found</h4>
                    <p class="text-muted">This student doesn't have any grades recorded yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Grade Statistics -->
    @if($grades->count() > 0)
        <div class="main-card mb-3 card">
            <div class="card-header">
                <div class="card-header-title">
                    <i class="header-icon pe-7s-graph icon-gradient bg-ripe-malin"></i>
                    Grade Statistics
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-success">{{ $grades->where('value', '>=', 10)->count() }}</h3>
                            <p class="text-muted">Passing Grades</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-danger">{{ $grades->where('value', '<', 10)->count() }}</h3>
                            <p class="text-muted">Failing Grades</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-primary">{{ $grades->count() }}</h3>
                            <p class="text-muted">Total Grades</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-info">{{ $grades->unique('subject_id')->count() }}</h3>
                            <p class="text-muted">Subjects</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
