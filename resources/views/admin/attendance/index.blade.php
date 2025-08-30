@extends('layouts.admin')

@section('admin-content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-check icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    Attendance Management
                    <div class="page-title-subheading">Track student attendance and manage records.</div>
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('admin.attendance.create') }}" class="btn btn-primary btn-lg">
                    <i class="fa fa-plus mr-2"></i>Mark Attendance
                </a>
            </div>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-check icon-gradient bg-ripe-malin"></i>
                Recent Attendance Records
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Justification</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendanceRecords as $record)
                        <tr>
                            <td>
                                <div class="widget-content p-0">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left mr-3">
                                            <img width="40" class="rounded-circle" src="{{ asset('kero/assets/images/avatars/' . rand(1, 10) . '.jpg') }}" alt="">
                                        </div>
                                        <div class="widget-content-left">
                                            <div class="widget-heading">{{ $record->student->full_name ?? 'N/A' }}</div>
                                            <div class="widget-subheading">{{ $record->student->massar_code ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $record->subject->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $record->formatted_date }}</span>
                            </td>
                            <td>
                                @if($record->Status)
                                    <span class="badge badge-success">
                                        <i class="fa fa-check mr-1"></i>Present
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        <i class="fa fa-times mr-1"></i>Absent
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($record->justification)
                                    <span class="text-muted">{{ Str::limit($record->justification, 30) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.attendance.show', $record->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.attendance.edit', $record->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.attendance.destroy', $record->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this attendance record?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                <i class="fa fa-calendar-check fa-3x mb-3"></i>
                                <p>No attendance records found</p>
                                <a href="{{ route('admin.attendance.create') }}" class="btn btn-primary">Mark Your First Attendance</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($attendanceRecords->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $attendanceRecords->links() }}
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
                        <div class="widget-heading">Total Records</div>
                        <div class="widget-subheading">Today</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $attendanceRecords->total() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-arielle-smile">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Present</div>
                        <div class="widget-subheading">Students</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">
                            <span>{{ $attendanceRecords->where('Status', true)->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-grow-early">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Absent</div>
                        <div class="widget-subheading">Students</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">
                            <span>{{ $attendanceRecords->where('Status', false)->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-premium-dark">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Attendance Rate</div>
                        <div class="widget-subheading">Today</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">
                            @php
                                $total = $attendanceRecords->count();
                                $present = $attendanceRecords->where('Status', true)->count();
                                $rate = $total > 0 ? round(($present / $total) * 100, 1) : 0;
                            @endphp
                            <span>{{ $rate }}%</span>
                        </div>
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
                            <a href="{{ route('admin.attendance.bulk-mark') }}" class="btn btn-success btn-block">
                                <i class="fa fa-users mr-2"></i>Bulk Mark
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="{{ route('admin.attendance.create') }}" class="btn btn-info btn-block">
                                <i class="fa fa-plus mr-2"></i>Mark Attendance
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 