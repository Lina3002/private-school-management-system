@extends('layouts.admin')

@section('admin-content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-book icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    Edit Subject
                    <div class="page-title-subheading">Update subject details and classroom.</div>
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fa fa-arrow-left mr-2"></i>Back to Subjects
                </a>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.subjects.update', $subject->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $subject->name) }}" required>
                </div>

                <div class="form-group">
                    <label for="classroom_id">Classroom</label>
                    <select id="classroom_id" name="classroom_id" class="form-control" required>
                        <option value="">Select classroom</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" {{ (old('classroom_id', $subject->classroom_id) == $classroom->id) ? 'selected' : '' }}>
                                {{ $classroom->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="staff_id">Assigned Teacher (optional)</label>
                    <select id="staff_id" name="staff_id" class="form-control">
                        <option value="">No teacher assigned</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ ($currentTeacherId == $teacher->id) ? 'selected' : '' }}>
                                {{ $teacher->first_name }} {{ $teacher->last_name }} ({{ $teacher->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save mr-2"></i>Save Changes
                </button>
            </form>
        </div>
    </div>
</div>
@endsection


