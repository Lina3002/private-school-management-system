@extends('layouts.admin')

@section('admin-content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-home icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    Class Management
                    <div class="page-title-subheading">Manage your school's classes and grade levels.</div>
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('admin.classes.create') }}" class="btn btn-primary btn-lg">
                    <i class="fa fa-plus mr-2"></i>Create New Class
                </a>
            </div>
        </div>
    </div>

    <!-- Classes Table -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-home icon-gradient bg-ripe-malin"></i>
                All Classes
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Class Name</th>
                            <th>Level</th>
                            <th>Students</th>
                            <th>Subjects</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $class)
                        <tr>
                            <td>
                                <div class="widget-content p-0">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left mr-3">
                                            <div class="widget-heading">{{ $class->name }}</div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="badge badge-info">{{ $class->level }}</div>
                            </td>
                            <td>
                                <span class="text-muted">{{ $class->current_enrollment ?? 0 }} students</span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $class->subjects->count() ?? 0 }} subjects</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.classes.show', $class->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.classes.edit', $class->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.classes.destroy', $class->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this class?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                <i class="fa fa-home fa-3x mb-3"></i>
                                <p>No classes found</p>
                                <a href="{{ route('admin.classes.create') }}" class="btn btn-primary">Create Your First Class</a>
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
                        <div class="widget-heading">Total Classes</div>
                        <div class="widget-subheading">Active classes</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $classes->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3 widget-content bg-arielle-smile">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Total Students</div>
                        <div class="widget-subheading">Enrolled students</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $classes->sum('current_enrollment') ?? 0 }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3 widget-content bg-grow-early">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Total Subjects</div>
                        <div class="widget-subheading">Taught subjects</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $classes->sum(function($class) { return $class->subjects->count(); }) }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 