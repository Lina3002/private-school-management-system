@extends('layouts.superadmin')
@section('content')
<div class="row mb-3">
    <div class="col-md-8">
        <h2>Platform Logs & Monitoring</h2>
    </div>
    <div class="col-md-4 text-right">
        <form method="GET" action="" class="form-inline float-right">
            <input type="text" name="user" class="form-control mr-2" placeholder="User" value="{{ request('user') }}">
            <input type="text" name="action" class="form-control mr-2" placeholder="Action" value="{{ request('action') }}">
            <input type="date" name="from" class="form-control mr-2" value="{{ request('from') }}">
            <input type="date" name="to" class="form-control mr-2" value="{{ request('to') }}">
            <button type="submit" class="btn btn-secondary mr-2">Filter</button>
            <a href="?export=csv" class="btn btn-success"><i class="fa fa-file-csv"></i> Export CSV</a>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="thead-light">
                    <tr>
                        <th>Date</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Entity</th>
                        <th>Details</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->created_at }}</td>
                        <td>{{ $log->user->name ?? '-' }}</td>
                        <td>{{ $log->description }}</td>
                        <td>{{ class_basename($log->subject_type) }} #{{ $log->subject_id }}</td>
                        <td><pre class="mb-0">{{ $log->properties }}</pre></td>
                        <td>{{ $log->ip_address }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">No logs found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="text-center mt-3">
            {{-- Pagination if needed: --}}
            @if(method_exists($logs, 'links'))
                {{ $logs->links() }}
            @endif
        </div>
    </div>
</div>
<div class="alert alert-info mt-4">
    <i class="fa fa-bolt"></i> Real-time monitoring coming soon.
</div>
@endsection
