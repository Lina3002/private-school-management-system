@extends('layouts.superadmin')
@section('content')
<div class="container mt-4">
    <h2>Edit Job Title</h2>
    <form method="POST" action="{{ route('superadmin.roles_permissions.job_title.update', $jobTitle->id) }}">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label for="name">Job Title Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $jobTitle->name }}" required>
        </div>
        <div class="form-group mb-3">
            <label for="school_id">School</label>
            <select name="school_id" id="school_id" class="form-control" required>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}" {{ $jobTitle->school_id == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Job Title</button>
        <a href="{{ route('superadmin.roles_permissions.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
