@extends('layouts.superadmin')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Create Role</span>
                    <a href="{{ route('superadmin.roles_permissions.index') }}" class="btn btn-sm btn-secondary">Back</a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('superadmin.roles_permissions.role.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="roleName">Role Name</label>
                            <select name="name" id="roleName" class="form-control" required>
    <option value="">Select role name</option>
    <option value="super_admin">Super Admin</option>
    <option value="admin">Admin</option>
    <option value="manager">Manager</option>
</select>
                        </div>
                        <div class="form-group">
                            <label for="roleSchool">School</label>
                            <select name="school_id" id="roleSchool" class="form-control" required>
    @forelse($schools as $school)
        <option value="{{ $school->id }}">{{ $school->name }}@if($school->deleted_at) (deleted)@endif</option>
    @empty
        <option value="">No schools found</option>
    @endforelse
</select>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Role</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
