@extends('layouts.admin')

@section('admin-content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-clock icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    Timetable Management
                    <div class="page-title-subheading">Manage your school's class schedules and timetables.</div>
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('admin.timetables.create') }}" class="btn btn-primary btn-lg">
                    <i class="fa fa-plus mr-2"></i>Add Timetable Entry
                </a>
            </div>
        </div>
    </div>

    <!-- Timetables Table -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-clock icon-gradient bg-ripe-malin"></i>
                All Timetable Entries
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Subject</th>
                            <th>Staff</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($timetables as $timetable)
                        <tr>
                            <td>
                                <span class="badge badge-primary">{{ $timetable->day_name }}</span>
                            </td>
                            <td>
                                <div class="widget-content p-0">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left">
                                            <div class="widget-heading">{{ $timetable->time_slot }}</div>
                                            <div class="widget-subheading">{{ $timetable->duration }} min</div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $timetable->subject->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="widget-content p-0">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left mr-3">
                                            <img width="32" class="rounded-circle" src="{{ asset('kero/assets/images/avatars/' . rand(1, 10) . '.jpg') }}" alt="">
                                        </div>
                                        <div class="widget-content-left">
                                            <div class="widget-heading">{{ $timetable->staff->first_name ?? 'N/A' }} {{ $timetable->staff->last_name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-{{ $timetable->Type === 'Teacher' ? 'success' : 'warning' }}">
                                    {{ $timetable->Type }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.timetables.show', $timetable->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.timetables.edit', $timetable->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.timetables.destroy', $timetable->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this timetable entry?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                <i class="fa fa-clock fa-3x mb-3"></i>
                                <p>No timetable entries found</p>
                                <a href="{{ route('admin.timetables.create') }}" class="btn btn-primary">Add Your First Timetable Entry</a>
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
                        <div class="widget-heading">Total Entries</div>
                        <div class="widget-subheading">Scheduled</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $timetables->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-arielle-smile">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Teacher Sessions</div>
                        <div class="widget-subheading">Scheduled</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $timetables->where('Type', 'Teacher')->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-grow-early">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Student Sessions</div>
                        <div class="widget-subheading">Scheduled</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $timetables->where('Type', 'Student')->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-premium-dark">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Active Days</div>
                        <div class="widget-subheading">This week</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $timetables->pluck('Day')->unique()->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
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
                            <a href="{{ route('admin.timetables.create') }}" class="btn btn-info btn-block">
                                <i class="fa fa-plus mr-2"></i>Add Entry
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 