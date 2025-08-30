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
                    Add New Transport Route
                    <div class="page-title-subheading">Create a new transportation route for your school.</div>
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('admin.transport.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fa fa-arrow-left mr-2"></i>Back to Transport
                </a>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-car icon-gradient bg-ripe-malin"></i>
                Route Information
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.transport.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="route_name" class="form-label">Route Name *</label>
                            <input type="text" class="form-control @error('route_name') is-invalid @enderror" 
                                   id="route_name" name="route_name" value="{{ old('route_name') }}" required>
                            @error('route_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Bus_number" class="form-label">Bus Number *</label>
                            <input type="text" class="form-control @error('Bus_number') is-invalid @enderror" 
                                   id="Bus_number" name="Bus_number" value="{{ old('Bus_number') }}" required>
                            @error('Bus_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="capacity" class="form-label">Bus Capacity *</label>
                            <input type="number" min="1" class="form-control @error('capacity') is-invalid @enderror" 
                                   id="capacity" name="capacity" value="{{ old('capacity') }}" required>
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="route_description" class="form-label">Route Description</label>
                            <textarea class="form-control @error('route_description') is-invalid @enderror" 
                                      id="route_description" name="route_description" rows="3">{{ old('route_description') }}</textarea>
                            @error('route_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fa fa-save mr-2"></i>Create Transport Route
                    </button>
                    <a href="{{ route('admin.transport.index') }}" class="btn btn-secondary btn-lg ml-2">
                        <i class="fa fa-times mr-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Route Planning Tips -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-help2 icon-gradient bg-info"></i>
                Route Planning Tips
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="alert alert-info">
                        <i class="fa fa-lightbulb mr-2"></i>
                        <strong>Tip:</strong> Use descriptive route names like "North District Route" or "Downtown Express" 
                        to help students and parents identify their route easily.
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-warning">
                        <i class="fa fa-exclamation-triangle mr-2"></i>
                        <strong>Note:</strong> After creating a route, you can assign students to it using the 
                        "Assign Students" action from the transport routes list.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 