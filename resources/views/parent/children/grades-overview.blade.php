@extends('layouts.parent')

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
                    Children's Grades Overview
                    <div class="page-title-subheading">Monitor academic performance of all your children.</div>
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('parent.children.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fa fa-arrow-left mr-2"></i>Back to Children
                </a>
            </div>
        </div>
    </div>

    <!-- Children Summary Cards -->
    <div class="row">
        @foreach($children as $child)
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card mb-3 widget-content">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                        <div class="widget-heading">{{ $child->first_name }} {{ $child->last_name }}</div>
                        <div class="widget-subheading">{{ $child->classroom->display_name ?? 'No Class' }}</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-primary">
                            <span>{{ number_format($childrenGPA->get($child->id, 0), 2) }}</span>
                        </div>
                        <div class="widget-subheading">GPA</div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('parent.children.grades', $child->id) }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-chart-line mr-1"></i>View Details
                        </a>
                        <a href="{{ route('parent.children.attendance', $child->id) }}" class="btn btn-info btn-sm">
                            <i class="fa fa-check mr-1"></i>Attendance
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Recent Grades Table -->
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
                            <th>Child</th>
                            <th>Subject</th>
                            <th>Grade</th>
                            <th>Date</th>
                            <th>Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentGrades as $grade)
                        <tr>
                            <td>
                                <div class="widget-content p-0">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left mr-3">
                                            <img width="40" class="rounded-circle" src="{{ asset('kero/assets/images/avatars/' . rand(1, 10) . '.jpg') }}" alt="">
                                        </div>
                                        <div class="widget-content-left">
                                            <div class="widget-heading">{{ $grade->student->first_name }} {{ $grade->student->last_name }}</div>
                                            <div class="widget-subheading">{{ $grade->student->classroom->display_name ?? 'No Class' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $grade->subject->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $grade->value >= 10 ? 'success' : ($grade->value >= 7 ? 'warning' : 'danger') }}">
                                    {{ $grade->value }}/20
                                </span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $grade->grading_date ? $grade->grading_date->format('M d, Y') : 'N/A' }}</span>
                            </td>
                            <td>
                                @if($grade->comment)
                                    <span class="text-muted">{{ Str::limit($grade->comment, 30) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                <i class="fa fa-medal fa-3x mb-3"></i>
                                <p>No grades found for your children</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection


