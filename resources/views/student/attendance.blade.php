@extends('layouts.student')

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
                    My Attendance
                    <div class="page-title-subheading">Track your attendance across all subjects.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Summary -->
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-3 widget-content bg-midnight-bloom">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Total Days</div>
                        <div class="widget-subheading">Recorded</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $attendance->total() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3 widget-content bg-arielle-smile">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Present</div>
                        <div class="widget-subheading">Days attended</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">
                            <span>{{ $attendance->where('Status', 1)->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3 widget-content bg-grow-early">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Attendance Rate</div>
                        <div class="widget-subheading">Overall percentage</div>
                    </div>
                    <div class="widget-content-right">
                        @php
                            $total = $attendance->total();
                            $present = $attendance->where('Status', 1)->count();
                            $rate = $total > 0 ? round(($present / $total) * 100, 1) : 0;
                        @endphp
                        <div class="widget-numbers text-white"><span>{{ $rate }}%</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance by Subject -->
    <div class="row">
        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-graph icon-gradient bg-ripe-malin"></i>
                        Attendance by Subject
                    </div>
                </div>
                <div class="card-body">
                    @if($attendanceBySubject->count() > 0)
                        @foreach($attendanceBySubject as $subjectName => $records)
                        <div class="widget-content p-0 mb-3">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">{{ $subjectName }}</div>
                                    <div class="widget-subheading">{{ $records->count() }} records</div>
                                </div>
                                <div class="widget-content-right">
                                    @php
                                        $subjectPresent = $records->where('Status', 1)->count();
                                        $subjectTotal = $records->count();
                                        $subjectRate = $subjectTotal > 0 ? round(($subjectPresent / $subjectTotal) * 100, 1) : 0;
                                    @endphp
                                    <div class="widget-numbers text-{{ $subjectRate >= 80 ? 'success' : ($subjectRate >= 60 ? 'warning' : 'danger') }}">
                                        {{ $subjectRate }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-3">
                            <p>No attendance data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-pie icon-gradient bg-ripe-malin"></i>
                        Attendance Chart
                    </div>
                </div>
                <div class="card-body">
                    @if($attendance->count() > 0)
                        <canvas id="attendanceChart" width="400" height="200"></canvas>
                    @else
                        <div class="text-center text-muted py-3">
                            <p>No data available for chart</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Attendance Records -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-check icon-gradient bg-ripe-malin"></i>
                Detailed Attendance Records
            </div>
        </div>
        <div class="card-body">
            @if($attendance->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Justification</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendance as $record)
                            <tr>
                                <td>{{ $record->attendancy_date ? \Carbon\Carbon::parse($record->attendancy_date)->format('M d, Y') : 'N/A' }}</td>
                                <td>{{ $record->subject->name ?? 'N/A' }}</td>
                                <td>
                                    @if($record->Status == 1)
                                        <span class="badge badge-success">Present</span>
                                    @else
                                        <span class="badge badge-danger">Absent</span>
                                    @endif
                                </td>
                                <td>{{ $record->justification ?? 'No reason provided' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($attendance->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $attendance->links() }}
                    </div>
                @endif
            @else
                <div class="text-center text-muted py-4">
                    <i class="fa fa-check fa-3x mb-3"></i>
                    <p>No attendance records found</p>
                </div>
            @endif
        </div>
    </div>
</div>

@if($attendance->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    
    const present = {{ $attendance->where('Status', 1)->count() }};
    const absent = {{ $attendance->where('Status', 0)->count() }};
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Present', 'Absent'],
            datasets: [{
                data: [present, absent],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 99, 132, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script>
@endif
@endsection 