@extends('layouts.staff')

@section('title', 'Staff Profile')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-header">
            <h1 class="page-title">My Profile</h1>
            <p class="page-subtitle">Manage your personal information</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Profile Summary</h4>
            </div>
            <div class="card-body text-center">
                <div class="avatar avatar-xl mb-3">
                    <img src="{{ asset('kero/assets/images/avatars/default.png') }}" alt="Profile Avatar" class="rounded-circle">
                </div>
                <h5>{{ $staff->first_name }} {{ $staff->last_name }}</h5>
                <p class="text-muted">{{ ucfirst($staff->job_title_name) }}</p>
                <p class="text-muted">{{ $school->name }}</p>
                
                <div class="mt-3">
                    <p><strong>Email:</strong> {{ $staff->email }}</p>
                    <p><strong>Phone:</strong> {{ $staff->phone ?? 'Not provided' }}</p>
                    <p><strong>Address:</strong> {{ $staff->address ?? 'Not provided' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Profile</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('staff.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                       id="first_name" name="first_name" value="{{ old('first_name', $staff->first_name) }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                       id="last_name" name="last_name" value="{{ old('last_name', $staff->last_name) }}" required>
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
                                       id="email" name="email" value="{{ old('email', $staff->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $staff->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3">{{ old('address', $staff->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Job Title</label>
                        <input type="text" class="form-control" value="{{ ucfirst($staff->job_title_name) }}" readonly>
                        <small class="form-text text-muted">Job title cannot be changed. Contact administrator for changes.</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">School</label>
                        <input type="text" class="form-control" value="{{ $school->name }}" readonly>
                        <small class="form-text text-muted">School assignment cannot be changed. Contact administrator for changes.</small>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                        <a href="{{ route('staff.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Account Information</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Account Type:</strong> Staff Member</p>
                        <p><strong>Member Since:</strong> {{ $staff->created_at ? $staff->created_at->format('F j, Y') : 'Not available' }}</p>
                        <p><strong>Last Updated:</strong> {{ $staff->updated_at ? $staff->updated_at->format('F j, Y') : 'Not available' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Status:</strong> <span class="badge bg-success">Active</span></p>
                        <p><strong>Access Level:</strong> Staff</p>
                        <p><strong>Permissions:</strong> Basic staff access</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
