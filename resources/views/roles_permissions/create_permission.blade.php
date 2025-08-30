@extends('layouts.superadmin')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Create Permission</span>
                    <a href="{{ route('superadmin.roles_permissions.index') }}" class="btn btn-sm btn-secondary">Back</a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('superadmin.roles_permissions.permission.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="permissionTitle">Permission Title</label>
                            <input type="text" name="title" id="permissionTitle" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="permissionSchool">School</label>
                            <select name="school_id" id="permissionSchool" class="form-control" required>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Permission</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
