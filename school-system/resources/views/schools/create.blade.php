@extends('layouts.superadmin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="mb-1" style="font-weight:600;">Add New School</h2>
        <p class="text-muted mb-0" style="font-size:1.05em;">Create and register a new school on the platform.</p>
    </div>
    <a href="{{ route('superadmin.schools.index') }}" class="btn btn-secondary">
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
        <form method="POST" action="{{ route('superadmin.schools.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <div class="position-relative form-group">
                        <label for="name">School Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
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
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="position-relative form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="position-relative form-group">
                        <label for="logo">Logo</label>
                        <input type="file" class="form-control-file" id="logo" name="logo" accept="image/*">
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="position-relative form-group">
                        <label for="school_level">School Level</label>
                        <select class="form-control" id="school_level" name="school_level" required>
                            <option value="">Select Level</option>
                            <option value="primary" {{ old('school_level') == 'primary' ? 'selected' : '' }}>Primary</option>
                            <option value="middle" {{ old('school_level') == 'middle' ? 'selected' : '' }}>Middle</option>
                            <option value="high" {{ old('school_level') == 'high' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success"><i class="fa fa-plus-circle"></i> Create School</button>
        </form>
    </div>
</div>
@endsection
