@extends('layouts.admin')

@section('admin-content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-car icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    Transport Management
                    <div class="page-title-subheading">Manage your school's transportation services and routes.</div>
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('admin.transport.create') }}" class="btn btn-primary btn-lg">
                    <i class="fa fa-plus mr-2"></i>Add New Route
                </a>
            </div>
        </div>
    </div>

    <!-- Transport Routes Table -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-car icon-gradient bg-ripe-malin"></i>
                All Transport Routes
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Route Name</th>
                            <th>Bus Number</th>
                            <th>Capacity</th>
                            <th>Students</th>
                            <th>Available Seats</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transportRoutes as $route)
                        <tr>
                            <td>
                                <div class="widget-content p-0">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left mr-3">
                                            <i class="fa fa-route fa-2x text-primary"></i>
                                        </div>
                                        <div class="widget-content-left">
                                            <div class="widget-heading">{{ $route->route_name }}</div>
                                            <div class="widget-subheading">{{ $route->route_description ?? 'No description' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $route->Bus_number }}</span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $route->capacity }} seats</span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $route->students->count() ?? 0 }} assigned</span>
                            </td>
                            <td>
                                @php
                                    $availableSeats = $route->capacity - ($route->students->count() ?? 0);
                                    $seatClass = $availableSeats > 0 ? 'success' : 'danger';
                                @endphp
                                <span class="badge badge-{{ $seatClass }}">{{ $availableSeats }} available</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.transport.show', $route->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.transport.edit', $route->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.transport.assign-students', $route->id) }}" class="btn btn-success btn-sm">
                                        <i class="fa fa-users"></i>
                                    </a>
                                    <form action="{{ route('admin.transport.destroy', $route->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this transport route?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                <i class="fa fa-car fa-3x mb-3"></i>
                                <p>No transport routes found</p>
                                <a href="{{ route('admin.transport.create') }}" class="btn btn-primary">Add Your First Transport Route</a>
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
                        <div class="widget-heading">Total Routes</div>
                        <div class="widget-subheading">Active routes</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $transportRoutes->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-arielle-smile">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Total Capacity</div>
                        <div class="widget-subheading">All buses</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $transportRoutes->sum('capacity') }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-grow-early">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Assigned Students</div>
                        <div class="widget-subheading">Using transport</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">
                            <span>{{ $transportRoutes->sum(function($route) { return $route->students->count(); }) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-premium-dark">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Available Seats</div>
                        <div class="widget-subheading">Remaining</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">
                            <span>{{ $transportRoutes->sum('capacity') - $transportRoutes->sum(function($route) { return $route->students->count(); }) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 