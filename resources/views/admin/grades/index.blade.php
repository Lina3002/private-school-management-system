@extends('layouts.admin')

@section('admin-content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-medal icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    Grade Management
                    <div class="page-title-subheading">Manage student grades and academic performance.</div>
                </div>
            </div>
            <div class="page-title-actions">
                <div class="btn-group mr-2">
                    <button type="button" class="btn btn-success btn-lg dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-download mr-2"></i>Export CSV
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('admin.grades.export-csv', ['term' => 'semester 1']) }}">
                            <i class="fa fa-file-csv mr-2"></i>Semester 1 - All Students
                        </a>
                        <a class="dropdown-item" href="{{ route('admin.grades.export-csv', ['term' => 'semester 2']) }}">
                            <i class="fa fa-file-csv mr-2"></i>Semester 2 - All Students
                        </a>
                    </div>
                </div>
                <a href="{{ route('admin.grades.create') }}" class="btn btn-primary btn-lg">
                    <i class="fa fa-plus mr-2"></i>Record New Grade
                </a>
            </div>
        </div>
    </div>

    <!-- Grades Table -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-medal icon-gradient bg-ripe-malin"></i>
                All Grades
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Subject</th>
                            <th>Grade</th>
                            <th>Term</th>
                            <th>Exam Type</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($grades as $grade)
                        <tr>
                            <td>
                                <div class="widget-content p-0">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left mr-3">
                                            @if($grade->student && $grade->student->photo)
                                                <img width="40" class="rounded-circle" src="{{ asset('storage/' . $grade->student->photo) }}" alt="{{ $grade->student->full_name }}">
                                            @else
                                                <img width="40" class="rounded-circle" src="{{ asset('kero/assets/images/avatars/1.jpg') }}" alt="{{ $grade->student->full_name ?? 'Student' }}">
                                            @endif
                                        </div>
                                        <div class="widget-content-left">
                                            <div class="widget-heading">{{ $grade->student->full_name ?? 'N/A' }}</div>
                                            <div class="widget-subheading">{{ $grade->student->massar_code ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $grade->subject->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="widget-content p-0">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left">
                                            <div class="widget-heading">{{ $grade->formatted_grade }}</div>
                                            <div class="widget-subheading">
                                                <span class="badge badge-{{ $grade->grade_color }}">{{ $grade->grade_letter }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-secondary">{{ $grade->term ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="badge badge-warning">{{ $grade->exam_type ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $grade->formatted_date ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.grades.show', $grade->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.grades.edit', $grade->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.grades.destroy', $grade->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this grade?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                <i class="fa fa-graduation-cap fa-3x mb-3"></i>
                                <p>No grades found</p>
                                <a href="{{ route('admin.grades.create') }}" class="btn btn-primary">Record Your First Grade</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($grades->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $grades->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-midnight-bloom">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Total Grades</div>
                        <div class="widget-subheading">Recorded</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $grades->total() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-arielle-smile">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Average Grade</div>
                        <div class="widget-subheading">Overall</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">
                            <span>{{ number_format($grades->avg('value'), 1) ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-grow-early">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Passing Rate</div>
                        <div class="widget-subheading">Students</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">
                            <span>{{ $grades->where('is_passing', true)->count() > 0 ? round(($grades->where('is_passing', true)->count() / $grades->count()) * 100, 1) : 0 }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-premium-dark">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">This Term</div>
                        <div class="widget-subheading">Grades</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">
                            <span>{{ $grades->where('term', 'semester 1')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 