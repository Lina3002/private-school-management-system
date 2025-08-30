@extends('layouts.superadmin')
@section('content')
<div class="container mt-4">
    <h2>Edit Role</h2>
    <form method="POST" action="{{ route('superadmin.roles_permissions.role.update', $role->id) }}">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label for="name">Role Name</label>
            <select name="name" id="name" class="form-control" required>
                <option value="admin" {{ $role->name == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="manager" {{ $role->name == 'manager' ? 'selected' : '' }}>Manager</option>
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="school_id">School</label>
            <select name="school_id" id="school_id" class="form-control" required>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}" {{ $role->school_id == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Role</button>
        <a href="{{ route('superadmin.roles_permissions.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
