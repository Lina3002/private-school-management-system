@extends('layouts.superadmin')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="mb-1" style="font-weight:600;"><i class="fa fa-user-plus mr-2"></i>Add New User</h2>
        <p class="text-muted mb-0" style="font-size:1.05em;">Register a new user for a school and assign a role.</p>
    </div>
    <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary">
        <i class="fa fa-arrow-left"></i> Back to List
    </a>
</div>
<div class="main-card mb-3 card">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="alert alert-info" style="font-size:1em;">
            <i class="fa fa-info-circle mr-1"></i>
            Creating a user will generate a login account and email the credentials to the user/staff.
        </div>
        <form method="POST" action="{{ route('superadmin.users.store') }}">
            @csrf
            <div class="form-group mb-3">
                <label class="font-weight-bold">Account Type <span class="text-danger">*</span></label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="account_type" id="managing_user" value="managing" {{ old('account_type', 'managing') == 'managing' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="managing_user">Managing User (Admin/Manager)</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="account_type" id="staff_member" value="staff" {{ old('account_type') == 'staff' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="staff_member">Staff Member (Teacher, Bus Assistant, etc.)</label>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <div class="position-relative form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="position-relative form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="position-relative form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="position-relative form-group">
                        <label for="school_id">School</label>
                        <select class="form-control" id="school_id" name="school_id" required>
                            <option value="">Select School</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mb-3 managing-fields">
                    <div class="position-relative form-group">
                        <label for="role_id">Role</label>
                        <select class="form-control" id="role_id" name="role_id">
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                @if(in_array($role->name, ['admin','manager']))
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mb-3 staff-fields" style="display:none;">
                    <div class="position-relative form-group">
                        <label for="job_title_id">Job Title</label>
                        <select class="form-control" id="job_title_id" name="job_title_id">
                            <option value="">Select Job Title</option>
                            {{-- Only show unique job titles for staff --}}
                            @foreach($jobTitles as $jobTitle)
                                <option value="{{ $jobTitle->id }}" {{ old('job_title_id') == $jobTitle->id ? 'selected' : '' }}>{{ ucfirst($jobTitle->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success"><i class="fa fa-plus-circle"></i> Create User</button>
        </form>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function toggleFields() {
        const managing = document.getElementById('managing_user').checked;
        document.querySelectorAll('.managing-fields').forEach(el => {
            el.style.display = managing ? '' : 'none';
            el.querySelectorAll('select, input').forEach(input => input.required = managing);
        });
        document.querySelectorAll('.staff-fields').forEach(el => {
            el.style.display = managing ? 'none' : '';
            el.querySelectorAll('select, input').forEach(input => input.required = !managing);
        });
    }
    document.getElementById('managing_user').addEventListener('change', toggleFields);
    document.getElementById('staff_member').addEventListener('change', toggleFields);
    toggleFields(); // initial
});
</script>
@endpush
@endsection
