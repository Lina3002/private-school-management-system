@extends('layouts.teacher')

@section('content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-book icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    Assign Homework
                    <div class="page-title-subheading">Create and assign homework to your students.</div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-triangle mr-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Homework Form -->
        <div class="col-md-8">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-edit icon-gradient bg-ripe-malin"></i>
                        Create New Homework
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('teacher.homework.store') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subject_id">Subject *</label>
                                    <select class="form-control @error('subject_id') is-invalid @enderror" 
                                            id="subject_id" 
                                            name="subject_id" 
                                            required>
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
                                    <label for="classroom_id">Class *</label>
                                    <select class="form-control @error('classroom_id') is-invalid @enderror" 
                                            id="classroom_id" 
                                            name="classroom_id" 
                                            required>
                                        <option value="">Select Class</option>
                                        @foreach($subjects as $subject)
                                            @if($subject->classroom_id)
                                                <option value="{{ $subject->classroom_id }}" data-subject="{{ $subject->id }}" style="display:none;">
                                                    {{ DB::table('classrooms')->where('id', $subject->classroom_id)->value('name') }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('classroom_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="title">Homework Title *</label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}" 
                                   placeholder="Enter homework title"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="5" 
                                      placeholder="Enter detailed homework description"
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="due_date">Due Date *</label>
                                    <input type="date" 
                                           class="form-control @error('due_date') is-invalid @enderror" 
                                           id="due_date" 
                                           name="due_date" 
                                           value="{{ old('due_date') }}" 
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                           required>
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="priority">Priority</label>
                                    <select class="form-control @error('priority') is-invalid @enderror" 
                                            id="priority" 
                                            name="priority">
                                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="attachments">Attachments</label>
                            <input type="file" 
                                   class="form-control-file @error('attachments') is-invalid @enderror" 
                                   id="attachments" 
                                   name="attachments[]" 
                                   multiple>
                            <small class="form-text text-muted">You can upload multiple files (PDF, DOC, images)</small>
                            @error('attachments')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="additional_notes">Additional Notes</label>
                            <textarea class="form-control @error('additional_notes') is-invalid @enderror" 
                                      id="additional_notes" 
                                      name="additional_notes" 
                                      rows="3" 
                                      placeholder="Any additional instructions or notes">{{ old('additional_notes') }}</textarea>
                            @error('additional_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save mr-2"></i>Assign Homework
                            </button>
                            <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary ml-2">
                                <i class="fa fa-arrow-left mr-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Info -->
        <div class="col-md-4">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-info icon-gradient bg-ripe-malin"></i>
                        Quick Actions
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left mr-2"></i>Back to Dashboard
                        </a>
                        <a href="{{ route('teacher.classes.index') }}" class="btn btn-primary">
                            <i class="fa fa-chalkboard mr-2"></i>View Classes
                        </a>
                        <a href="{{ route('teacher.students.index') }}" class="btn btn-success">
                            <i class="fa fa-users mr-2"></i>View Students
                        </a>
                        <a href="{{ route('teacher.grades.index') }}" class="btn btn-info">
                            <i class="fa fa-medal mr-2"></i>View Grades
                        </a>
                    </div>
                </div>
            </div>

            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-light icon-gradient bg-ripe-malin"></i>
                        Tips
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fa fa-check-circle text-success mr-2"></i>
                            Be specific with instructions
                        </li>
                        <li class="mb-2">
                            <i class="fa fa-check-circle text-success mr-2"></i>
                            Set realistic due dates
                        </li>
                        <li class="mb-2">
                            <i class="fa fa-check-circle text-success mr-2"></i>
                            Include relevant attachments
                        </li>
                        <li class="mb-2">
                            <i class="fa fa-check-circle text-success mr-2"></i>
                            Use clear, simple language
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const subjectSelect = document.getElementById('subject_id');
    const classroomSelect = document.getElementById('classroom_id');
    
    // Populate classrooms based on selected subject
    subjectSelect.addEventListener('change', function() {
        const subjectId = this.value;
        const allOptions = classroomSelect.querySelectorAll('option[data-subject]');
        classroomSelect.value = '';
        allOptions.forEach(opt => {
            opt.style.display = (opt.getAttribute('data-subject') === subjectId) ? '' : 'none';
        });
    });
    
    // Set minimum date for due date (tomorrow)
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    document.getElementById('due_date').min = tomorrow.toISOString().split('T')[0];
});
</script>
@endpush
@endsection
