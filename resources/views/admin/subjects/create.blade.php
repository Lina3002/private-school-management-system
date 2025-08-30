@extends('layouts.admin')

@section('admin-content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-book icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    Add New Subject
                    <div class="page-title-subheading">Create a new subject for your school.</div>
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fa fa-arrow-left mr-2"></i>Back to Subjects
                </a>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-book icon-gradient bg-ripe-malin"></i>
                Subject Information
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.subjects.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="form-label">Subject Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="classroom_id" class="form-label">Assigned Class</label>
                            <select class="form-control @error('classroom_id') is-invalid @enderror" 
                                    id="classroom_id" name="classroom_id">
                                <option value="">Select Class (Optional)</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('classroom_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->display_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('classroom_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fa fa-save mr-2"></i>Create Subject
                    </button>
                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary btn-lg ml-2">
                        <i class="fa fa-times mr-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 