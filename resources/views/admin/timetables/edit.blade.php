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
                    Edit Timetable Entry
                    <div class="page-title-subheading">Update timetable information.</div>
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('admin.timetables.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fa fa-arrow-left mr-2"></i>Back to Timetables
                </a>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-clock icon-gradient bg-ripe-malin"></i>
                Timetable Information
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.timetables.update', $timetable->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="subject_id" class="form-label">Subject *</label>
                            <select class="form-control @error('subject_id') is-invalid @error" 
                                    id="subject_id" name="subject_id" required>
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id', $timetable->subject_id) == $subject->id ? 'selected' : '' }}>
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
                            <select class="form-control @error('staff_id') is-invalid @error" 
                                    id="staff_id" name="staff_id" required>
                                <option value="">Select Staff Member</option>
                                @foreach($staff as $member)
                                    <option value="{{ $member->id }}" {{ old('staff_id', $timetable->staff_id) == $member->id ? 'selected' : '' }}>
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
                            <select class="form-control @error('Day') is-invalid @error" 
                                    id="Day" name="Day" required>
                                <option value="">Select Day</option>
                                <option value="Monday" {{ old('Day', $timetable->day_name) == 'Monday' ? 'selected' : '' }}>Monday</option>
                                <option value="Tuesday" {{ old('Day', $timetable->day_name) == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                                <option value="Wednesday" {{ old('Day', $timetable->day_name) == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                                <option value="Thursday" {{ old('Day', $timetable->day_name) == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                                <option value="Friday" {{ old('Day', $timetable->day_name) == 'Friday' ? 'selected' : '' }}>Friday</option>
                                <option value="Saturday" {{ old('Day', $timetable->day_name) == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                                <option value="Sunday" {{ old('Day', $timetable->day_name) == 'Sunday' ? 'selected' : '' }}>Sunday</option>
                            </select>
                            @error('Day')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="Time_start" class="form-label">Start Time *</label>
                            <input type="time" class="form-control @error('Time_start') is-invalid @error" 
                                   id="Time_start" name="Time_start" value="{{ old('Time_start', $timetable->formatted_start_time) }}" required>
                            @error('Time_start')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="Time_end" class="form-label">End Time *</label>
                            <input type="time" class="form-control @error('Time_end') is-invalid @error" 
                                   id="Time_end" name="Time_end" value="{{ old('Time_end', $timetable->formatted_end_time) }}" required>
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
                            <select class="form-control @error('Type') is-invalid @error" 
                                    id="Type" name="Type" required>
                                <option value="">Select Type</option>
                                <option value="Teacher" {{ old('Type', $timetable->Type) == 'Teacher' ? 'selected' : '' }}>Teacher Session</option>
                                <option value="Student" {{ old('Type', $timetable->Type) == 'Student' ? 'selected' : '' }}>Student Session</option>
                            </select>
                            @error('Type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="creation_date" class="form-label">Creation Date</label>
                            <input type="date" class="form-control @error('creation_date') is-invalid @error" 
                                   id="creation_date" name="creation_date" value="{{ old('creation_date', $timetable->creation_date ? $timetable->creation_date->format('Y-m-d') : date('Y-m-d')) }}">
                            @error('creation_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fa fa-save mr-2"></i>Update Timetable Entry
                    </button>
                    <a href="{{ route('admin.timetables.index') }}" class="btn btn-secondary btn-lg ml-2">
                        <i class="fa fa-times mr-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


