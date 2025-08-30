@extends('layouts.superadmin')
@section('content')
<div class="container mt-4">
    <h2>Edit Permission</h2>
    <form method="POST" action="{{ route('superadmin.roles_permissions.permission.update', $permission->id) }}">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label for="title">Permission Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $permission->title }}" required>
        </div>
        <div class="form-group mb-3">
            <label for="school_id">School</label>
            <select name="school_id" id="school_id" class="form-control" required>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}" {{ $permission->school_id == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Permission</button>
        <a href="{{ route('superadmin.roles_permissions.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
