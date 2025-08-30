@extends('layouts.driver')

@section('content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-car icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    Driver Dashboard
                    <div class="page-title-subheading">Welcome back, {{ $driver?->first_name ?? 'Driver' }}! Here's your transportation overview.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-midnight-bloom">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Active Routes</div>
                        <div class="widget-subheading">Assigned routes</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $routes->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-arielle-smile">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Total Students</div>
                        <div class="widget-subheading">To transport</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $totalStudents }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-grow-early">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Today's Pickups</div>
                        <div class="widget-subheading">Scheduled</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $todayPickups->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-premium-dark">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Vehicle Status</div>
                        <div class="widget-subheading">Bus condition</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span class="text-success">Ready</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Pickups -->
    <div class="row">
        <div class="col-md-8">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-clock icon-gradient bg-ripe-malin"></i>
                        Today's Pickup Schedule
                    </div>
                </div>
                <div class="card-body">
                    @if($todayPickups->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Route</th>
                                        <th>Pickup Time</th>
                                        <th>Dropoff Time</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todayPickups as $pickup) // Only one table for today's pickups
                                    <tr>
                                        <td>
                                            <div class="widget-content p-0">
                                                <div class="widget-content-wrapper">
                                                    <div class="widget-content-left mr-3">
                                                        <img width="40" class="rounded-circle" src="{{ asset('kero/assets/images/avatars/' . rand(1, 10) . '.jpg') }}" alt="">
                                                    </div>
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">
    {{ optional($pickup['student'])->first_name ?? 'N/A' }} {{ optional($pickup['student'])->last_name ?? '' }}
</div>
                                                        <div class="widget-subheading">{{ $pickup['student']->classroom->name ?? 'N/A' }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $pickup['route']->route_name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ $pickup['pickup_time'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-warning">{{ $pickup['dropoff_time'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">Scheduled</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('driver.attendance.mark') }}?student={{ $pickup['student']->id ?? '' }}" 
                                               class="btn btn-success btn-sm">
                                                <i class="fa fa-check mr-1"></i>Mark Attendance
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fa fa-calendar fa-3x mb-3"></i>
                            <p>No pickups scheduled for today</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-map icon-gradient bg-ripe-malin"></i>
                        Route Information
                    </div>
                </div>
                <div class="card-body">
                    @if($routes->count() > 0)
                        @foreach($routes as $route) // Only one route info card
                        <div class="widget-content p-0 mb-3">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">{{ $route->route_name ?? 'N/A' }}</div>
                                    <div class="widget-subheading">Bus {{ $route->Bus_number ?? 'N/A' }}</div>
                                    <div class="widget-subheading text-muted">{{ $route->students->count() }} students</div>
                                </div>
                                <div class="widget-content-right">
                                    <div class="widget-numbers text-primary">
                                        <span class="badge badge-info">{{ $route->capacity ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="widget-content-right ml-3">
                                    <a href="{{ route('driver.routes.map', ['route' => $route->id]) }}" class="btn btn-primary btn-sm">View Map</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <div class="text-center mt-3">
                            <a href="{{ route('driver.routes.map', ['route' => $routes->first()->id]) }}" class="btn btn-primary btn-sm">View Route Map</a>
                        </div>
                    @else
                        <div class="text-center text-muted py-3">
                            <p>No routes assigned</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-tools icon-gradient bg-ripe-malin"></i>
                        Quick Actions
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('driver.routes') }}" class="btn btn-primary btn-block btn-lg">
                                <i class="fa fa-route mr-2"></i>View Routes
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('driver.attendance.mark') }}" class="btn btn-success btn-block btn-lg">
                                <i class="fa fa-check mr-2"></i>Mark Transport Attendance
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            @if(isset($routes) && $routes->count())
    <a href="{{ route('driver.routes.map', ['route' => $routes->first()->id]) }}" class="btn btn-info btn-block btn-lg">
        <i class="fa fa-map mr-2"></i>Route Map
    </a>
@endif
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('driver.profile.index') }}" class="btn btn-secondary btn-block btn-lg">
                                <i class="fa fa-user mr-2"></i>My Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Emergency Contacts -->
    <div class="row">
        <div class="col-12">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-phone icon-gradient bg-ripe-malin"></i>
                        Emergency Contacts
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>School Office</h6>
                            <p><i class="fa fa-phone mr-2"></i>+212 123 456 789</p>
                            <p><i class="fa fa-envelope mr-2"></i>office@school.com</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Transport Manager</h6>
                            <p><i class="fa fa-phone mr-2"></i>+212 987 654 321</p>
                            <p><i class="fa fa-envelope mr-2"></i>transport@school.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 