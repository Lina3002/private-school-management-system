@extends('layouts.teacher')

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
        </div>
    </div>

    <div class="row">
        <!-- Profile Summary -->
        <div class="col-md-4">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-user icon-gradient bg-ripe-malin"></i>
                        Profile Summary
                    </div>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img src="{{ asset('kero/assets/images/avatars/' . rand(1, 10) . '.jpg') }}" 
                             alt="Profile Picture" 
                             class="rounded-circle" 
                             style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                    <h5 class="card-title">{{ $teacher->first_name }} {{ $teacher->last_name }}</h5>
                    <p class="text-muted">{{ ucfirst($teacher->job_title) }}</p>
                    <div class="mt-3">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="widget-numbers text-primary">{{ $teacher->phone ?? 'N/A' }}</div>
                                <div class="widget-subheading">Phone</div>
                            </div>
                            <div class="col-6">
                                <div class="widget-numbers text-success">{{ $teacher->email ?? 'N/A' }}</div>
                                <div class="widget-subheading">Email</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
        <div class="col-md-8">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-edit icon-gradient bg-ripe-malin"></i>
                        Edit Profile
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('teacher.profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input type="text" 
                                           class="form-control @error('first_name') is-invalid @enderror" 
                                           id="first_name" 
                                           name="first_name" 
                                           value="{{ old('first_name', $teacher->first_name) }}" 
                                           required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" 
                                           class="form-control @error('last_name') is-invalid @enderror" 
                                           id="last_name" 
                                           name="last_name" 
                                           value="{{ old('last_name', $teacher->last_name) }}" 
                                           required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $teacher->email) }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', $teacher->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3">{{ old('address', $teacher->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="birth_date">Birth Date</label>
                            <input type="date" 
                                   class="form-control @error('birth_date') is-invalid @enderror" 
                                   id="birth_date" 
                                   name="birth_date" 
                                   value="{{ old('birth_date', $teacher->birth_date) }}">
                            @error('birth_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select class="form-control @error('gender') is-invalid @enderror" 
                                    id="gender" 
                                    name="gender">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $teacher->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $teacher->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="hire_date">Hire Date</label>
                            <input type="date" 
                                   class="form-control @error('hire_date') is-invalid @enderror" 
                                   id="hire_date" 
                                   name="hire_date" 
                                   value="{{ old('hire_date', $teacher->hire_date) }}">
                            @error('hire_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="salary">Salary</label>
                            <input type="number" 
                                   class="form-control @error('salary') is-invalid @enderror" 
                                   id="salary" 
                                   name="salary" 
                                   value="{{ old('salary', $teacher->salary) }}" 
                                   step="0.01">
                            @error('salary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="job_title">Job Title</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="job_title" 
                                   value="{{ ucfirst($teacher->job_title) }}" 
                                   readonly>
                            <small class="form-text text-muted">Job title cannot be changed. Contact administration for changes.</small>
                        </div>

                        <div class="form-group">
                            <label for="school">School</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="school" 
                                   value="{{ $school->name }}" 
                                   readonly>
                            <small class="form-text text-muted">School assignment cannot be changed. Contact administration for changes.</small>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save mr-2"></i>Update Profile
                            </button>
                            <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary ml-2">
                                <i class="fa fa-arrow-left mr-2"></i>Back to Dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="row">
        <div class="col-12">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-info icon-gradient bg-ripe-malin"></i>
                        Additional Information
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Emergency Contact</h6>
                            <p><strong>Name:</strong> {{ $teacher->emergency_contact_name ?? 'Not provided' }}</p>
                            <p><strong>Phone:</strong> {{ $teacher->emergency_contact_phone ?? 'Not provided' }}</p>
                            <p><strong>Relationship:</strong> {{ $teacher->emergency_contact_relationship ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Professional Details</h6>
                            <p><strong>Department:</strong> {{ $teacher->department ?? 'Not specified' }}</p>
                            <p><strong>Qualifications:</strong> {{ $teacher->qualifications ?? 'Not specified' }}</p>
                            <p><strong>Experience:</strong> {{ $teacher->experience_years ?? 'Not specified' }} years</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


