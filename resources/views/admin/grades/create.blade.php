@extends('layouts.admin')

@section('admin-content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-medal icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    Record New Grade
                    <div class="page-title-subheading">Add a new grade for a student.</div>
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('admin.grades.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fa fa-arrow-left mr-2"></i>Back to Grades
                </a>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-medal icon-gradient bg-ripe-malin"></i>
                Grade Information
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.grades.store') }}" method="POST">
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
                                <option value="">Select Student First</option>
                            </select>
                            @error('subject_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="value" class="form-label">Grade Value *</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('value') is-invalid @enderror" 
                                   id="value" name="value" value="{{ old('value') }}" required>
                            @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="min_value" class="form-label">Minimum Value</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('min_value') is-invalid @enderror" 
                                   id="min_value" name="min_value" value="{{ old('min_value', 0) }}">
                            @error('min_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="max_value" class="form-label">Maximum Value *</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('max_value') is-invalid @enderror" 
                                   id="max_value" name="max_value" value="{{ old('max_value', 20) }}" required>
                            @error('max_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="term" class="form-label">Term *</label>
                            <select class="form-control @error('term') is-invalid @enderror" 
                                    id="term" name="term" required>
                                <option value="">Select Term</option>
                                <option value="semester 1" {{ old('term') == 'semester 1' ? 'selected' : '' }}>Semester 1</option>
                                <option value="semester 2" {{ old('term') == 'semester 2' ? 'selected' : '' }}>Semester 2</option>
                                <option value="final" {{ old('term') == 'final' ? 'selected' : '' }}>Final</option>
                            </select>
                            @error('term')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exam_type" class="form-label">Exam Type</label>
                            <select class="form-control @error('exam_type') is-invalid @enderror" 
                                    id="exam_type" name="exam_type">
                                <option value="">Select Type</option>
                                <option value="quiz" {{ old('exam_type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                                <option value="midterm" {{ old('exam_type') == 'midterm' ? 'selected' : '' }}>Midterm</option>
                                <option value="final" {{ old('exam_type') == 'final' ? 'selected' : '' }}>Final</option>
                                <option value="assignment" {{ old('exam_type') == 'assignment' ? 'selected' : '' }}>Assignment</option>
                            </select>
                            @error('exam_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="staff_id" class="form-label">Graded By</label>
                            <select class="form-control @error('staff_id') is-invalid @enderror" 
                                    id="staff_id" name="staff_id">
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
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="grading_date" class="form-label">Grading Date</label>
                            <input type="date" class="form-control @error('grading_date') is-invalid @enderror" 
                                   id="grading_date" name="grading_date" value="{{ old('grading_date', date('Y-m-d')) }}">
                            @error('grading_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="comment" class="form-label">Comments</label>
                    <textarea class="form-control @error('comment') is-invalid @enderror" 
                              id="comment" name="comment" rows="3">{{ old('comment') }}</textarea>
                    @error('comment')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fa fa-save mr-2"></i>Record Grade
                    </button>
                    <a href="{{ route('admin.grades.index') }}" class="btn btn-secondary btn-lg ml-2">
                        <i class="fa fa-times mr-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const studentSelect = document.getElementById('student_id');
    const subjectSelect = document.getElementById('subject_id');
    
    // Store all subjects data
    const allSubjects = @json($subjects);
    
    studentSelect.addEventListener('change', function() {
        const selectedStudentId = this.value;
        
        // Clear subject options
        subjectSelect.innerHTML = '<option value="">Select Subject</option>';
        
        if (selectedStudentId) {
            // Find the selected student to get their class
            const selectedStudent = @json($students).find(student => student.id == selectedStudentId);
            
            if (selectedStudent && selectedStudent.classroom_id) {
                // Filter subjects based on the student's class
                const classSubjects = allSubjects.filter(subject => 
                    subject.classroom_id == selectedStudent.classroom_id
                );
                
                // Add filtered subjects to the dropdown
                classSubjects.forEach(subject => {
                    const option = document.createElement('option');
                    option.value = subject.id;
                    option.textContent = subject.name;
                    subjectSelect.appendChild(option);
                });
                
                if (classSubjects.length === 0) {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'No subjects available for this class';
                    option.disabled = true;
                    subjectSelect.appendChild(option);
                }
            } else {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'Student not assigned to a class';
                option.disabled = true;
                subjectSelect.appendChild(option);
            }
        }
    });
});
</script>
@endsection 