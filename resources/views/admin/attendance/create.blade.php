@extends('layouts.admin')

@section('admin-content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-check icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    Mark Attendance
                    <div class="page-title-subheading">Record student attendance for a specific subject.</div>
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fa fa-arrow-left mr-2"></i>Back to Attendance
                </a>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-check icon-gradient bg-ripe-malin"></i>
                Attendance Information
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.attendance.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="student_id" class="form-label">Student *</label>
                            <select class="form-control @error('student_id') is-invalid @enderror" 
                                    id="student_id" name="student_id" required>
                                <option value="">Select Student</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->full_name }} ({{ $student->massar_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="subject_id" class="form-label">Subject *</label>
                            <select class="form-control @error('subject_id') is-invalid @enderror" 
                                    id="subject_id" name="subject_id" required>
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="attendancy_date" class="form-label">Date *</label>
                            <input type="date" class="form-control @error('attendancy_date') is-invalid @enderror" 
                                   id="attendancy_date" name="attendancy_date" value="{{ old('attendancy_date', date('Y-m-d')) }}" required>
                            @error('attendancy_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Status" class="form-label">Attendance Status *</label>
                            <select class="form-control @error('Status') is-invalid @enderror" 
                                    id="Status" name="Status" required>
                                <option value="">Select Status</option>
                                <option value="1" {{ old('Status') == '1' ? 'selected' : '' }}>Present</option>
                                <option value="0" {{ old('Status') == '0' ? 'selected' : '' }}>Absent</option>
                            </select>
                            @error('Status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="justification" class="form-label">Justification (if absent)</label>
                    <textarea class="form-control @error('justification') is-invalid @enderror" 
                              id="justification" name="justification" rows="3" 
                              placeholder="Please provide a reason if the student is absent...">{{ old('justification') }}</textarea>
                    @error('justification')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fa fa-save mr-2"></i>Mark Attendance
                    </button>
                    <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary btn-lg ml-2">
                        <i class="fa fa-times mr-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-tools icon-gradient bg-info"></i>
                Quick Actions
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="alert alert-info">
                        <i class="fa fa-users mr-2"></i>
                        <strong>Bulk Marking:</strong> Need to mark attendance for multiple students? 
                        Use the <a href="{{ route('admin.attendance.bulk-mark') }}" class="alert-link">Bulk Mark</a> feature 
                        to record attendance for an entire class at once.
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-warning">
                        <i class="fa fa-calendar mr-2"></i>
                        <strong>Daily Records:</strong> You can mark attendance for any date, but it's recommended 
                        to mark it on the actual day for better accuracy and record keeping.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 