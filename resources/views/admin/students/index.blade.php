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
                    Student Management
                    <div class="page-title-subheading">Manage your school's students and enrollments.</div>
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('admin.students.create') }}" class="btn btn-primary btn-lg">
                    <i class="fa fa-plus mr-2"></i>Add New Student
                </a>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-gleam icon-gradient bg-ripe-malin"></i>
                All Students
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Massar Code</th>
                            <th>Class</th>
                            <th>Gender</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr>
                            <td>
                                <div class="widget-content p-0">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left mr-3">
                                            @if($student->photo)
                                                <img width="40" class="rounded-circle" src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->full_name }}">
                                            @else
                                                <img width="40" class="rounded-circle" src="{{ asset('kero/assets/images/avatars/1.jpg') }}" alt="{{ $student->full_name }}">
                                            @endif
                                        </div>
                                        <div class="widget-content-left">
                                            <div class="widget-heading">{{ $student->full_name }}</div>
                                            <div class="widget-subheading">{{ $student->email }}</div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-secondary">{{ $student->massar_code }}</span>
                            </td>
                            <td>
                                @if($student->classroom)
                                    <div class="badge badge-info">{{ $student->classroom->display_name }}</div>
                                @else
                                    <span class="text-muted">Not enrolled</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $student->gender === 'male' ? 'primary' : 'pink' }}">
                                    {{ ucfirst($student->gender) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.students.show', $student->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.students.grades', $student->id) }}" class="btn btn-success btn-sm">
                                        <i class="fa fa-graduation-cap"></i>
                                    </a>
                                    <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this student?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                <i class="fa fa-user-graduate fa-3x mb-3"></i>
                                <p>No students found</p>
                                <a href="{{ route('admin.students.create') }}" class="btn btn-primary">Add Your First Student</a>
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
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-midnight-bloom">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Total Students</div>
                        <div class="widget-subheading">Enrolled</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $students->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-arielle-smile">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Male Students</div>
                        <div class="widget-subheading">Boys</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $students->where('gender', 'male')->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-grow-early">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Female Students</div>
                        <div class="widget-subheading">Girls</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $students->where('gender', 'female')->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-premium-dark">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Enrolled</div>
                        <div class="widget-subheading">In classes</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $students->where('classroom_id', '!=', null)->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 