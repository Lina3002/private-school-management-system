@extends('layouts.admin')

@section('admin-content')
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
                    <div class="page-title-subheading">View and update your personal information.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="row">
        <div class="col-md-4">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-user icon-gradient bg-ripe-malin"></i>
                        Profile Picture
                    </div>
                </div>
                <div class="card-body text-center">
                    <img width="150" class="rounded-circle mb-3" 
                         src="{{ asset('kero/assets/images/avatars/' . (Auth::user()->id % 10 + 1) . '.jpg') }}" 
                         alt="Profile Picture">
                    <h5>{{ $user->first_name }} {{ $user->last_name }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>
                    <p class="text-muted">{{ ucfirst($user->role->name ?? 'User') }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-info icon-gradient bg-ripe-malin"></i>
                        Personal Information
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name" class="form-label">First Name *</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                           id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                           id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-actions mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa fa-save mr-2"></i>Update Profile
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-lg ml-2">
                                <i class="fa fa-arrow-left mr-2"></i>Back to Dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Information -->
    <div class="row">
        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-home icon-gradient bg-ripe-malin"></i>
                        School Information
                    </div>
                </div>
                <div class="card-body">
                    <div class="widget-content p-0 mb-3">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="widget-heading">School Name</div>
                            </div>
                            <div class="widget-content-right">
                                <div class="widget-numbers text-primary">{{ $school->name ?? 'Not assigned' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="widget-content p-0 mb-3">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="widget-heading">Role</div>
                            </div>
                            <div class="widget-content-right">
                                <div class="widget-numbers text-success">{{ ucfirst($user->role->name ?? 'User') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="widget-heading">Member Since</div>
                            </div>
                            <div class="widget-content-right">
                                <div class="widget-numbers text-info">{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('M d, Y') : 'Not specified' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-clock icon-gradient bg-ripe-malin"></i>
                        Account Activity
                    </div>
                </div>
                <div class="card-body">
                    <div class="widget-content p-0 mb-3">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="widget-heading">Last Login</div>
                            </div>
                            <div class="widget-content-right">
                                <div class="widget-numbers text-warning">{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('M d, Y H:i') : 'Never' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="widget-content p-0 mb-3">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="widget-heading">Last Updated</div>
                            </div>
                            <div class="widget-content-right">
                                <div class="widget-numbers text-info">{{ $user->updated_at ? \Carbon\Carbon::parse($user->updated_at)->format('M d, Y H:i') : 'Never' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="widget-heading">Status</div>
                            </div>
                            <div class="widget-content-right">
                                <div class="widget-numbers text-success">
                                    <span class="badge badge-success">Active</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 