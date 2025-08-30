@extends('layouts.driver')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">My Routes</h4>
                </div>
                <div class="card-body">
                    @if($routes->count())
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Route Name</th>
                                        <th>Bus Number</th>
                                        <th>Students</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($routes->unique('id') as $route)
    <tr>
        <td>{{ $route->name ?? $route->route_name ?? 'Route #' . $route->id }}</td>
        <td>{{ $route->bus_number ?? $route->Bus_number ?? '-' }}</td>
        <td>{{ $route->students ? $route->students->count() : 0 }}</td>
        <td>
            <a href="{{ route('driver.routes.show', ['route' => $route->id]) }}" class="btn btn-info btn-sm">Details</a>
            <a href="{{ route('driver.routes.map', ['route' => $route->id]) }}" class="btn btn-primary btn-sm">Map</a>
        </td>
    </tr>
@endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>No routes assigned to you.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
