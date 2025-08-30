@extends('layouts.teacher')

@section('content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-check icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    Attendance
                    <div class="page-title-subheading">View and manage student attendance for your subjects.</div>
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
                            <a href="{{ route('teacher.attendance.mark') }}" class="btn btn-success btn-block">
                                <i class="fa fa-check mr-2"></i>Mark Attendance
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary btn-block">
                                <i class="fa fa-arrow-left mr-2"></i>Back to Dashboard
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('teacher.students.index') }}" class="btn btn-primary btn-block">
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

    @if($attendance->count() > 0)
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
                            @foreach($attendance as $record)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('kero/assets/images/avatars/' . rand(1, 10) . '.jpg') }}" 
                                             alt="Student" 
                                             class="rounded-circle mr-2" 
                                             style="width: 32px; height: 32px; object-fit: cover;">
                                        <div>
                                            <strong>{{ $record->first_name }} {{ $record->last_name }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $record->subject_name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ \Carbon\Carbon::parse($record->attendancy_date)->format('M d, Y') }}</span>
                                </td>
                                <td>
                                    @if($record->Status == 1)
                                        <span class="badge badge-success">Present</span>
                                    @else
                                        <span class="badge badge-danger">Absent</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted">{{ Str::limit($record->justification ?? 'No justification', 30) }}</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#attendanceModal{{ $record->id }}">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <a href="{{ route('teacher.attendance.mark') }}" class="btn btn-sm btn-outline-warning">
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
                @if($attendance->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $attendance->links() }}
                </div>
                @endif
            </div>
        </div>

        <!-- Attendance Detail Modals -->
        @foreach($attendance as $record)
        <div class="modal fade" id="attendanceModal{{ $record->id }}" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Attendance Details</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Student:</strong> {{ $record->first_name }} {{ $record->last_name }}</p>
                                <p><strong>Subject:</strong> {{ $record->subject_name ?? 'N/A' }}</p>
                                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($record->attendancy_date)->format('M d, Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Status:</strong> 
                                    @if($record->Status == 1)
                                        <span class="badge badge-success">Present</span>
                                    @else
                                        <span class="badge badge-danger">Absent</span>
                                    @endif
                                </p>
                                <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($record->attendancy_date)->format('H:i') }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <p><strong>Justification:</strong></p>
                                <p class="text-muted">{{ $record->justification ?? 'No justification provided' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <a href="{{ route('teacher.attendance.mark') }}" class="btn btn-primary">Edit Attendance</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="main-card mb-3 card">
            <div class="card-body text-center">
                <i class="fa fa-check fa-3x text-muted mb-3"></i>
                <h5>No Attendance Records</h5>
                <p class="text-muted">No attendance records have been created for your subjects yet.</p>
                <p class="text-muted">Start marking attendance for your students!</p>
                <a href="{{ route('teacher.attendance.mark') }}" class="btn btn-success mt-3">
                    <i class="fa fa-check mr-2"></i>Mark First Attendance
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
                        <div class="widget-heading">Total Records</div>
                        <div class="widget-subheading">Attendance</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">{{ $attendance->total() }}</div>
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
                            {{ $attendance->where('Status', 1)->count() }}
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
                            {{ $attendance->where('Status', 0)->count() }}
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
                        <div class="widget-subheading">Percentage</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">
                            @php
                                $total = $attendance->count();
                                $present = $attendance->where('Status', 1)->count();
                                $rate = $total > 0 ? round(($present / $total) * 100, 1) : 0;
                            @endphp
                            {{ $rate }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


