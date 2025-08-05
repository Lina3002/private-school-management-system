@extends('layouts.superadmin')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h2 class="page-title">Super Admin Dashboard</h2>
        <div class="subheading-1">Platform Overview and School Profits</div>
    </div>
</div>
{{-- Kero-style Management Dashboard --}}
<div class="row">
    <div class="col-md-3">
        <div class="card mb-4 shadow-sm">
            <div class="card-body text-center">
                <div class="widget-numbers text-primary display-4">{{ $stats['total_schools'] }}</div>
                <div class="widget-subheading">Schools</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card mb-4 shadow-sm">
            <div class="card-body text-center">
                <div class="widget-numbers text-success display-4">{{ $stats['total_students'] }}</div>
                <div class="widget-subheading">Students</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card mb-4 shadow-sm">
            <div class="card-body text-center">
                <div class="widget-numbers text-info display-4">{{ $stats['total_teachers'] }}</div>
                <div class="widget-subheading">Teachers</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card mb-4 shadow-sm">
            <div class="card-body text-center">
                <div class="widget-numbers text-warning display-4">{{ $stats['total_staff'] }}</div>
                <div class="widget-subheading">Staff</div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">Payments (Last 30 Days)</h5>
            </div>
            <div class="card-body">
                <canvas id="paymentsChart" height="90"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-4 shadow-sm">
            <div class="card-body text-center">
                <div class="widget-numbers text-success display-4">${{ number_format($stats['total_profits'], 2) }}</div>
                <div class="widget-subheading">Total Profits</div>
            </div>
        </div>
        <div class="card mb-4 shadow-sm">
            <div class="card-body text-center">
                <div class="widget-numbers text-primary display-4">{{ $stats['total_payments'] }}</div>
                <div class="widget-subheading">Payments</div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">Schools Overview</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>School</th>
                                <th>Students</th>
                                <th>Teachers</th>
                                <th>Staff</th>
                                <th>Profit</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($schools as $school)
                            <tr>
                                <td>{{ $school->name }}</td>
                                <td>{{ $school->students_count }}</td>
                                <td>{{ $school->teachers_count }}</td>
                                <td>{{ $school->staffs_count }}</td>
                                <td>${{ number_format($school->profit, 2) }}</td>
                                <td><a href="{{ route('superadmin.schools.show', $school->id) }}" class="btn btn-sm btn-outline-primary">Manage</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">Recent Platform Activity</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @forelse($recentActivity as $activity)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $activity->description }}
                            <span class="badge badge-secondary">{{ $activity->created_at->diffForHumans() }}</span>
                        </li>
                    @empty
                        <li class="list-group-item">No recent activity.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('paymentsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Payments',
                data: @json($chartData),
                fill: true,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0,123,255,0.1)',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endpush
@endsection
