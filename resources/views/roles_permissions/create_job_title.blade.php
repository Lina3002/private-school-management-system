@extends('layouts.superadmin')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Create Job Title</span>
                    <a href="{{ route('superadmin.roles_permissions.index') }}" class="btn btn-sm btn-secondary">Back</a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('superadmin.roles_permissions.job_title.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="jobTitleName">Job Title Name</label>
                            <input type="text" name="name" id="jobTitleName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="jobTitleSchool">School</label>
                            <select name="school_id" id="jobTitleSchool" class="form-control" required>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Job Title</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
