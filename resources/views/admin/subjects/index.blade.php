@extends('layouts.admin')

@section('admin-content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-book icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    Subject Management
                    <div class="page-title-subheading">Manage your school's subjects and courses.</div>
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary btn-lg">
                    <i class="fa fa-plus mr-2"></i>Add New Subject
                </a>
            </div>
        </div>
    </div>

    <!-- Subjects Table -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-book icon-gradient bg-ripe-malin"></i>
                All Subjects
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Subject Name</th>
                            <th>Class</th>
                            <th>Students</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $subject)
                        <tr>
                            <td>
                                <div class="widget-content p-0">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left mr-3">
                                            <div class="widget-heading">{{ $subject->name }}</div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($subject->classroom)
                                    <div class="badge badge-info">{{ $subject->classroom->display_name }}</div>
                                @else
                                    <span class="text-muted">No class assigned</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted">{{ $subject->classroom ? $subject->classroom->current_enrollment : 0 }} students</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.subjects.show', $subject->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.subjects.edit', $subject->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this subject?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                <i class="fa fa-book fa-3x mb-3"></i>
                                <p>No subjects found</p>
                                <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">Add Your First Subject</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-3 widget-content bg-midnight-bloom">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Total Subjects</div>
                        <div class="widget-subheading">Active subjects</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $subjects->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3 widget-content bg-arielle-smile">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Assigned Subjects</div>
                        <div class="widget-subheading">To classes</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $subjects->where('classroom_id', '!=', null)->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3 widget-content bg-grow-early">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Unassigned</div>
                        <div class="widget-subheading">Subjects</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $subjects->where('classroom_id', null)->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 