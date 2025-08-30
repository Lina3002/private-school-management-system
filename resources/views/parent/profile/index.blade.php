@extends('layouts.parent')

@section('content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-user icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    My Profile
                    <div class="page-title-subheading">Manage your personal information and account settings.</div>
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('parent.dashboard') }}" class="btn btn-secondary btn-lg">
                    <i class="fa fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="row">
        <div class="col-md-8">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-user icon-gradient bg-ripe-malin"></i>
                        Personal Information
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('parent.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name" class="form-label">First Name *</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                           id="first_name" name="first_name" value="{{ old('first_name', $parent->first_name) }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                           id="last_name" name="last_name" value="{{ old('last_name', $parent->last_name) }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $parent->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $parent->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3">{{ old('address', $parent->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa fa-save mr-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Profile Summary -->
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-graph icon-gradient bg-ripe-malin"></i>
                        Profile Summary
                    </div>
                </div>
                <div class="card-body">
                    <div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left mr-3">
                                <img width="80" class="rounded-circle" src="{{ asset('kero/assets/images/avatars/' . rand(1, 10) . '.jpg') }}" alt="">
                            </div>
                            <div class="widget-content-left">
                                <div class="widget-heading">{{ $parent->first_name }} {{ $parent->last_name }}</div>
                                <div class="widget-subheading">{{ $parent->email }}</div>
                                <div class="widget-subheading text-muted">Parent</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="widget-numbers text-primary">{{ $parent->children_count ?? 0 }}</div>
                                <div class="widget-subheading">Children</div>
                            </div>
                            <div class="col-6">
                                <div class="widget-numbers text-success">{{ $parent->school->name ?? 'N/A' }}</div>
                                <div class="widget-subheading">School</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-tools icon-gradient bg-ripe-malin"></i>
                        Quick Actions
                    </div>
                </div>
                <div class="card-body">
                    <a href="{{ route('parent.dashboard') }}" class="btn btn-primary btn-block mb-2">
                        <i class="fa fa-home mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('parent.children.index') }}" class="btn btn-success btn-block mb-2">
                        <i class="fa fa-users mr-2"></i>My Children
                    </a>
                                            <a href="{{ route('parent.children.grades-overview') }}" class="btn btn-info btn-block mb-2">
                        <i class="fa fa-medal mr-2"></i>All Grades
                    </a>
                                         <a href="{{ route('parent.children.attendance-overview') }}" class="btn btn-warning btn-block">
                        <i class="fa fa-check mr-2"></i>All Attendance
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
