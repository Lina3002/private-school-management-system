@extends('layouts.admin')

@section('admin-content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-clock icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    Add Timetable Entry
                    <div class="page-title-subheading">Schedule a new class or activity.</div>
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('admin.timetables.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fa fa-arrow-left mr-2"></i>Back to Timetables
                </a>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-clock icon-gradient bg-ripe-malin"></i>
                Timetable Information
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.timetables.store') }}" method="POST">
                @csrf
                
                <div class="row">
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
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="staff_id" class="form-label">Staff Member *</label>
                            <select class="form-control @error('staff_id') is-invalid @enderror" 
                                    id="staff_id" name="staff_id" required>
                                <option value="">Select Staff Member</option>
                                @foreach($staff as $member)
                                    <option value="{{ $member->id }}" {{ old('staff_id') == $member->id ? 'selected' : '' }}>
                                        {{ $member->first_name }} {{ $member->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('staff_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="Day" class="form-label">Day *</label>
                            <select class="form-control @error('Day') is-invalid @enderror" 
                                    id="Day" name="Day" required>
                                <option value="">Select Day</option>
                                <option value="Monday" {{ old('Day') == 'Monday' ? 'selected' : '' }}>Monday</option>
                                <option value="Tuesday" {{ old('Day') == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                                <option value="Wednesday" {{ old('Day') == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                                <option value="Thursday" {{ old('Day') == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                                <option value="Friday" {{ old('Day') == 'Friday' ? 'selected' : '' }}>Friday</option>
                                <option value="Saturday" {{ old('Day') == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                                <option value="Sunday" {{ old('Day') == 'Sunday' ? 'selected' : '' }}>Sunday</option>
                            </select>
                            @error('Day')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="Time_start" class="form-label">Start Time *</label>
                            <input type="time" class="form-control @error('Time_start') is-invalid @enderror" 
                                   id="Time_start" name="Time_start" value="{{ old('Time_start') }}" required>
                            @error('Time_start')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="Time_end" class="form-label">End Time *</label>
                            <input type="time" class="form-control @error('Time_end') is-invalid @enderror" 
                                   id="Time_end" name="Time_end" value="{{ old('Time_end') }}" required>
                            @error('Time_end')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Type" class="form-label">Session Type *</label>
                            <select class="form-control @error('Type') is-invalid @enderror" 
                                    id="Type" name="Type" required>
                                <option value="">Select Type</option>
                                <option value="Teacher" {{ old('Type') == 'Teacher' ? 'selected' : '' }}>Teacher Session</option>
                                <option value="Student" {{ old('Type') == 'Student' ? 'selected' : '' }}>Student Session</option>
                            </select>
                            @error('Type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="creation_date" class="form-label">Creation Date</label>
                            <input type="date" class="form-control @error('creation_date') is-invalid @enderror" 
                                   id="creation_date" name="creation_date" value="{{ old('creation_date', date('Y-m-d')) }}">
                            @error('creation_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fa fa-save mr-2"></i>Create Timetable Entry
                    </button>
                    <a href="{{ route('admin.timetables.index') }}" class="btn btn-secondary btn-lg ml-2">
                        <i class="fa fa-times mr-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Time Conflict Warning -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-attention icon-gradient bg-warning"></i>
                Important Notes
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fa fa-info-circle mr-2"></i>
                <strong>Note:</strong> The system will automatically check for time conflicts when creating timetable entries. 
                Make sure the selected time slot doesn't overlap with existing schedules for the same staff member or subject.
            </div>
        </div>
    </div>
</div>
@endsection 