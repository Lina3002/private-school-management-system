@extends('layouts.teacher')

@section('content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-medal icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    Record Grade
                    <div class="page-title-subheading">Record and manage student grades for your subjects.</div>
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
        <!-- Grade Form -->
        <div class="col-md-8">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-edit icon-gradient bg-ripe-malin"></i>
                        Record New Grade
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('teacher.grades.store') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_id">Student *</label>
                                    <select class="form-control @error('student_id') is-invalid @enderror" 
                                            id="student_id" 
                                            name="student_id" 
                                            required>
                                        <option value="">Select Student</option>
                                        <!-- This will be populated via JavaScript based on selected subject -->
                                    </select>
                                    @error('student_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
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
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="value">Grade Value *</label>
                                    <input type="number" 
                                           class="form-control @error('value') is-invalid @enderror" 
                                           id="value" 
                                           name="value" 
                                           value="{{ old('value') }}" 
                                           min="0" 
                                           step="0.01"
                                           placeholder="Enter grade value"
                                           required>
                                    @error('value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="max_value">Maximum Value *</label>
                                    <input type="number" 
                                           class="form-control @error('max_value') is-invalid @enderror" 
                                           id="max_value" 
                                           name="max_value" 
                                           value="{{ old('max_value', 20) }}" 
                                           min="1" 
                                           step="0.01"
                                           placeholder="Enter maximum value"
                                           required>
                                    @error('max_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="term">Term *</label>
                                    <select class="form-control @error('term') is-invalid @enderror" 
                                            id="term" 
                                            name="term" 
                                            required>
                                        <option value="">Select Term</option>
                                        <option value="First Term" {{ old('term') == 'First Term' ? 'selected' : '' }}>First Term</option>
                                        <option value="Second Term" {{ old('term') == 'Second Term' ? 'selected' : '' }}>Second Term</option>
                                        <option value="Third Term" {{ old('term') == 'Third Term' ? 'selected' : '' }}>Third Term</option>
                                        <option value="Final Exam" {{ old('term') == 'Final Exam' ? 'selected' : '' }}>Final Exam</option>
                                    </select>
                                    @error('term')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exam_type">Exam Type</label>
                                    <select class="form-control @error('exam_type') is-invalid @enderror" 
                                            id="exam_type" 
                                            name="exam_type">
                                        <option value="">Select Exam Type</option>
                                        <option value="Quiz" {{ old('exam_type') == 'Quiz' ? 'selected' : '' }}>Quiz</option>
                                        <option value="Midterm" {{ old('exam_type') == 'Midterm' ? 'selected' : '' }}>Midterm</option>
                                        <option value="Final" {{ old('exam_type') == 'Final' ? 'selected' : '' }}>Final</option>
                                        <option value="Assignment" {{ old('exam_type') == 'Assignment' ? 'selected' : '' }}>Assignment</option>
                                        <option value="Project" {{ old('exam_type') == 'Project' ? 'selected' : '' }}>Project</option>
                                        <option value="Participation" {{ old('exam_type') == 'Participation' ? 'selected' : '' }}>Participation</option>
                                    </select>
                                    @error('exam_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="comment">Comment</label>
                            <textarea class="form-control @error('comment') is-invalid @enderror" 
                                      id="comment" 
                                      name="comment" 
                                      rows="4" 
                                      placeholder="Enter feedback or comments about the student's performance">{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="grading_date">Grading Date</label>
                            <input type="date" 
                                   class="form-control @error('grading_date') is-invalid @enderror" 
                                   id="grading_date" 
                                   name="grading_date" 
                                   value="{{ old('grading_date', date('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}"
                                   required>
                            @error('grading_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save mr-2"></i>Record Grade
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
                        <a href="{{ route('teacher.grades.index') }}" class="btn btn-primary">
                            <i class="fa fa-medal mr-2"></i>View All Grades
                        </a>
                        <a href="{{ route('teacher.students.index') }}" class="btn btn-success">
                            <i class="fa fa-users mr-2"></i>View Students
                        </a>
                        <a href="{{ route('teacher.classes.index') }}" class="btn btn-info">
                            <i class="fa fa-chalkboard mr-2"></i>View Classes
                        </a>
                    </div>
                </div>
            </div>

            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-light icon-gradient bg-ripe-malin"></i>
                        Grading Guidelines
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Grade Scale (20-point system):</h6>
                        <ul class="list-unstyled">
                            <li><span class="badge badge-success">18-20:</span> Excellent</li>
                            <li><span class="badge badge-primary">16-17:</span> Very Good</li>
                            <li><span class="badge badge-info">14-15:</span> Good</li>
                            <li><span class="badge badge-warning">12-13:</span> Satisfactory</li>
                            <li><span class="badge badge-danger">0-11:</span> Needs Improvement</li>
                        </ul>
                    </div>
                    <div class="mb-3">
                        <h6>Tips for Effective Grading:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fa fa-check-circle text-success mr-2"></i>Be consistent</li>
                            <li><i class="fa fa-check-circle text-success mr-2"></i>Provide constructive feedback</li>
                            <li><i class="fa fa-check-circle text-success mr-2"></i>Grade promptly</li>
                            <li><i class="fa fa-check-circle text-success mr-2"></i>Use clear criteria</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-graph icon-gradient bg-ripe-malin"></i>
                        Grade Statistics
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <h4 class="text-primary">{{ $subjects->count() }}</h4>
                            <p class="text-muted mb-0">Subjects Teaching</p>
                        </div>
                        <div class="mb-3">
                            <h4 class="text-success">0</h4>
                            <p class="text-muted mb-0">Grades Recorded Today</p>
                        </div>
                        <div class="mb-3">
                            <h4 class="text-info">0</h4>
                            <p class="text-muted mb-0">Average Grade</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const subjectSelect = document.getElementById('subject_id');
    const studentSelect = document.getElementById('student_id');
    
    // Populate students based on selected subject
    subjectSelect.addEventListener('change', function() {
        const subjectId = this.value;
        studentSelect.innerHTML = '<option value="">Select Student</option>';
        
        if (subjectId) {
            // This would typically be an AJAX call to get students for the subject
            // For now, we'll show a placeholder
            studentSelect.innerHTML = '<option value="">Loading students...</option>';
            
            // Simulate loading students (replace with actual AJAX call)
            setTimeout(() => {
                studentSelect.innerHTML = '<option value="">Select Student</option>';
                // Add actual student options here based on the selected subject
            }, 500);
        }
    });
    
    // Set maximum date for grading date (today)
    document.getElementById('grading_date').max = new Date().toISOString().split('T')[0];
    
    // Auto-calculate percentage when grade and max value change
    const valueInput = document.getElementById('value');
    const maxValueInput = document.getElementById('max_value');
    
    function updatePercentage() {
        const value = parseFloat(valueInput.value) || 0;
        const maxValue = parseFloat(maxValueInput.value) || 20;
        
        if (maxValue > 0) {
            const percentage = (value / maxValue) * 100;
            // You could display this percentage somewhere if needed
        }
    }
    
    valueInput.addEventListener('input', updatePercentage);
    maxValueInput.addEventListener('input', updatePercentage);
});
</script>
@endpush
@endsection 