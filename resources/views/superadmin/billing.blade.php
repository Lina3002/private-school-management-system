@extends('layouts.superadmin')
@section('content')
<div class="row mb-3">
    <div class="col-md-8">
        <h2>Billing & Profits</h2>
    </div>
    <div class="col-md-4 text-right">
        <form method="GET" action="" class="form-inline float-right">
            <select name="school_id" class="form-control mr-2">
                <option value="">All Schools</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                @endforeach
            </select>
            <input type="date" name="from" class="form-control mr-2" value="{{ request('from') }}">
            <input type="date" name="to" class="form-control mr-2" value="{{ request('to') }}">
            <button type="submit" class="btn btn-secondary mr-2">Filter</button>
            <a href="?export=csv" class="btn btn-success"><i class="fa fa-file-csv"></i> Export CSV</a>
        </form>
    </div>
</div>
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Revenue</h5>
                <p class="card-text h4">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Profit</h5>
                <p class="card-text h4">${{ number_format($stats['total_profit'] ?? 0, 2) }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Payments</h5>
                <p class="card-text h4">{{ $stats['total_payments'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">Outstanding</h5>
                <p class="card-text h4">${{ number_format($stats['total_outstanding'] ?? 0, 2) }}</p>
            </div>
        </div>
    </div>
</div>
<div class="card mb-4">
    <div class="card-body">
        <canvas id="billingChart" height="80"></canvas>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="thead-light">
                    <tr>
                        <th>Date</th>
                        <th>School</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Receipt</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td>{{ $payment->date }}</td>
                        <td>{{ $payment->school->name ?? '-' }}</td>
                        <td>${{ number_format($payment->amount, 2) }}</td>
                        <td>{{ ucfirst($payment->type) }}</td>
                        <td>{{ ucfirst($payment->method) }}</td>
                        <td>{{ ucfirst($payment->payment_status) }}</td>
                        <td>
                            @if($payment->receipt_file)
                                <a href="{{ asset('storage/' . $payment->receipt_file) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fa fa-file"></i> View</a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">No payments found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var ctx = document.getElementById('billingChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels ?? []),
            datasets: [{
                label: 'Payments',
                data: @json($chartData ?? []),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: true,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
