@extends('layouts.teacher')

@section('content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-medal icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    Grades
                    <div class="page-title-subheading">View and manage student grades for your subjects.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('teacher.grades.record') }}" class="btn btn-primary btn-block">
                                <i class="fa fa-plus mr-2"></i>Record New Grade
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary btn-block">
                                <i class="fa fa-arrow-left mr-2"></i>Back to Dashboard
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('teacher.students.index') }}" class="btn btn-success btn-block">
                                <i class="fa fa-users mr-2"></i>View Students
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('teacher.classes.index') }}" class="btn btn-info btn-block">
                                <i class="fa fa-chalkboard mr-2"></i>View Classes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($grades->count() > 0)
        <div class="main-card mb-3 card">
            <div class="card-header">
                <div class="card-header-title">
                    <i class="header-icon pe-7s-medal icon-gradient bg-ripe-malin"></i>
                    Recent Grades
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
                                <th>Date</th>
                                <th>Comment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grades as $grade)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('kero/assets/images/avatars/' . rand(1, 10) . '.jpg') }}" 
                                             alt="Student" 
                                             class="rounded-circle mr-2" 
                                             style="width: 32px; height: 32px; object-fit: cover;">
                                        <div>
                                            <strong>{{ $grade->first_name }} {{ $grade->last_name }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $grade->subject_name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $grade->value >= 10 ? 'success' : ($grade->value >= 7 ? 'warning' : 'danger') }}">
                                        {{ $grade->value }}/{{ $grade->max_value ?? 20 }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">{{ $grade->term ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ \Carbon\Carbon::parse($grade->grading_date)->format('M d, Y') }}</span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ Str::limit($grade->comment ?? 'No comment', 30) }}</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#gradeModal{{ $grade->id }}">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <a href="{{ route('teacher.grades.record') }}" class="btn btn-sm btn-outline-warning">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
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

        <!-- Grade Detail Modals -->
        @foreach($grades as $grade)
        <div class="modal fade" id="gradeModal{{ $grade->id }}" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Grade Details</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Student:</strong> {{ $grade->first_name }} {{ $grade->last_name }}</p>
                                <p><strong>Subject:</strong> {{ $grade->subject_name ?? 'N/A' }}</p>
                                <p><strong>Grade:</strong> {{ $grade->value }}/{{ $grade->max_value ?? 20 }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Term:</strong> {{ $grade->term ?? 'N/A' }}</p>
                                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($grade->grading_date)->format('M d, Y') }}</p>
                                <p><strong>Type:</strong> {{ $grade->exam_type ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <p><strong>Comment:</strong></p>
                                <p class="text-muted">{{ $grade->comment ?? 'No comment provided' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <a href="{{ route('teacher.grades.record') }}" class="btn btn-primary">Edit Grade</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="main-card mb-3 card">
            <div class="card-body text-center">
                <i class="fa fa-medal fa-3x text-muted mb-3"></i>
                <h5>No Grades Recorded</h5>
                <p class="text-muted">No grades have been recorded for your subjects yet.</p>
                <p class="text-muted">Start recording grades for your students!</p>
                <a href="{{ route('teacher.grades.record') }}" class="btn btn-primary mt-3">
                    <i class="fa fa-plus mr-2"></i>Record First Grade
                </a>
            </div>
        </div>
    @endif

    <!-- Statistics -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-midnight-bloom">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Total Grades</div>
                        <div class="widget-subheading">Recorded</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">{{ $grades->total() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-arielle-smile">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Average Grade</div>
                        <div class="widget-subheading">Class Performance</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">
                            {{ $grades->count() > 0 ? number_format($grades->avg('value'), 1) : 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-grow-early">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Highest Grade</div>
                        <div class="widget-subheading">Best Performance</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">
                            {{ $grades->count() > 0 ? $grades->max('value') : 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-premium-dark">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Lowest Grade</div>
                        <div class="widget-subheading">Needs Attention</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">
                            {{ $grades->count() > 0 ? $grades->min('value') : 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


