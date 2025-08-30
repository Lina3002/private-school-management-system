@extends('layouts.student')

@section('content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-book icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    My Homework
                    <div class="page-title-subheading">View and manage your homework assignments.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Homework Overview -->
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-midnight-bloom">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Total Assignments</div>
                        <div class="widget-subheading">All time</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $homework->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-arielle-smile">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Pending</div>
                        <div class="widget-subheading">To complete</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">
                            <span>{{ $homework->where('due_date', '>', now())->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-grow-early">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Completed</div>
                        <div class="widget-subheading">Finished</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">
                            <span>{{ $homework->where('status', 'completed')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-premium-dark">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Overdue</div>
                        <div class="widget-subheading">Past due date</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">
                            <span>{{ $homework->where('due_date', '<', now())->where('status', '!=', 'completed')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Homework List -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-book icon-gradient bg-ripe-malin"></i>
                Homework Assignments
            </div>
        </div>
        <div class="card-body">
            @if($homework->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($homework as $assignment)
                            <tr>
                                <td>
                                    <div class="widget-content p-0">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left">
                                                <div class="widget-heading">{{ $assignment->subject->name ?? 'N/A' }}</div>
                                                <div class="widget-subheading">{{ $assignment->teacher->first_name ?? 'N/A' }} {{ $assignment->teacher->last_name ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $assignment->title ?? 'N/A' }}</td>
                                <td>{{ Str::limit($assignment->description ?? 'No description', 50) }}</td>
                                <td>
                                    @if($assignment->due_date)
                                        @php
                                            $dueDate = \Carbon\Carbon::parse($assignment->due_date);
                                            $isOverdue = $dueDate->isPast() && ($assignment->status ?? '') !== 'completed';
                                        @endphp
                                        <span class="badge badge-{{ $isOverdue ? 'danger' : 'info' }}">
                                            {{ $dueDate->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="text-muted">No due date</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $status = $assignment->status ?? 'pending';
                                        $statusClass = match($status) {
                                            'completed' => 'success',
                                            'in_progress' => 'warning',
                                            'overdue' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge badge-{{ $statusClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-sm">
                                        <i class="fa fa-eye mr-1"></i>View
                                    </a>
                                    @if($status !== 'completed')
                                        <a href="#" class="btn btn-success btn-sm ml-1">
                                            <i class="fa fa-check mr-1"></i>Mark Complete
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center text-muted py-4">
                    <i class="fa fa-book fa-3x mb-3"></i>
                    <p>No homework assignments available</p>
                    <p class="text-muted">Your teachers will assign homework here when available.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Upcoming Deadlines -->
    <div class="row">
        <div class="col-md-8">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-alarm icon-gradient bg-ripe-malin"></i>
                        Upcoming Deadlines
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $upcomingHomework = $homework->where('due_date', '>', now())->sortBy('due_date')->take(5);
                    @endphp
                    
                    @if($upcomingHomework->count() > 0)
                        @foreach($upcomingHomework as $assignment)
                        <div class="widget-content p-0 mb-3">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">{{ $assignment->title ?? 'Untitled' }}</div>
                                    <div class="widget-subheading">{{ $assignment->subject->name ?? 'N/A' }}</div>
                                </div>
                                <div class="widget-content-right">
                                    @php
                                        $dueDate = \Carbon\Carbon::parse($assignment->due_date);
                                        $daysLeft = $dueDate->diffInDays(now(), false);
                                    @endphp
                                    <div class="widget-numbers text-{{ $daysLeft <= 1 ? 'danger' : ($daysLeft <= 3 ? 'warning' : 'info') }}">
                                        {{ $daysLeft <= 0 ? 'Due today' : ($daysLeft == 1 ? 'Due tomorrow' : "Due in {$daysLeft} days") }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-3">
                            <p>No upcoming deadlines</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-graph icon-gradient bg-ripe-malin"></i>
                        Progress Overview
                    </div>
                </div>
                <div class="card-body">
                    @if($homework->count() > 0)
                        <canvas id="homeworkChart" width="400" height="200"></canvas>
                    @else
                        <div class="text-center text-muted py-3">
                            <p>No data available for chart</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($homework->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('homeworkChart').getContext('2d');
    
    const completed = {{ $homework->where('status', 'completed')->count() }};
    const inProgress = {{ $homework->where('status', 'in_progress')->count() }};
    const pending = {{ $homework->where('status', 'pending')->count() }};
    const overdue = {{ $homework->where('due_date', '<', now())->where('status', '!=', 'completed')->count() }};
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'In Progress', 'Pending', 'Overdue'],
            datasets: [{
                data: [completed, inProgress, pending, overdue],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 99, 132, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(54, 162, 235, 1)',
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