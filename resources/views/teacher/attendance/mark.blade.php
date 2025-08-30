@extends('layouts.teacher')

@section('content')
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
                    <div class="page-title-subheading">Mark student attendance for your subjects.</div>
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
        <!-- Attendance Form -->
        <div class="col-md-8">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-edit icon-gradient bg-ripe-malin"></i>
                        Mark Student Attendance
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('teacher.attendance.store') }}">
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
                                    <label for="date">Date *</label>
                                    <input type="date" 
                                           class="form-control @error('date') is-invalid @enderror" 
                                           id="date" 
                                           name="date" 
                                           value="{{ old('date', date('Y-m-d')) }}"
                                           max="{{ date('Y-m-d') }}"
                                           required>
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Student Attendance *</label>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle mr-2"></i>
                                Select the attendance status for each student. You can also add justification for absences.
                            </div>
                            
                            <div id="students-container">
                                <div class="text-center text-muted py-4">
                                    <i class="fa fa-users fa-2x mb-2"></i>
                                    <p>Please select a subject to view students</p>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save mr-2"></i>Save Attendance
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
                        <a href="{{ route('teacher.attendance.index') }}" class="btn btn-primary">
                            <i class="fa fa-check mr-2"></i>View Attendance
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
                        Attendance Guidelines
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Attendance Status:</h6>
                        <ul class="list-unstyled">
                            <li><span class="badge badge-success">Present:</span> Student attended the class</li>
                            <li><span class="badge badge-danger">Absent:</span> Student missed the class</li>
                        </ul>
                    </div>
                    <div class="mb-3">
                        <h6>Justification Examples:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fa fa-check-circle text-success mr-2"></i>Medical appointment</li>
                            <li><i class="fa fa-check-circle text-success mr-2"></i>Family emergency</li>
                            <li><i class="fa fa-check-circle text-success mr-2"></i>Transportation issue</li>
                            <li><i class="fa fa-check-circle text-success mr-2"></i>School activity</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-graph icon-gradient bg-ripe-malin"></i>
                        Today's Summary
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
                            <p class="text-muted mb-0">Students Present</p>
                        </div>
                        <div class="mb-3">
                            <h4 class="text-danger">0</h4>
                            <p class="text-muted mb-0">Students Absent</p>
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
    const studentsContainer = document.getElementById('students-container');
    const dateInput = document.getElementById('date');
    
    // Set maximum date for attendance (today)
    dateInput.max = new Date().toISOString().split('T')[0];
    
    // Load students when subject is selected
    subjectSelect.addEventListener('change', function() {
        const subjectId = this.value;
        
        if (subjectId) {
            loadStudents(subjectId);
        } else {
            studentsContainer.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fa fa-users fa-2x mb-2"></i>
                    <p>Please select a subject to view students</p>
                </div>
            `;
        }
    });
    
    function loadStudents(subjectId) {
        studentsContainer.innerHTML = `
            <div class="text-center text-muted py-4">
                <i class="fa fa-spinner fa-spin fa-2x mb-2"></i>
                <p>Loading students...</p>
            </div>
        `;
        
        // This would typically be an AJAX call to get students for the subject
        // For now, we'll show a placeholder with sample students
        setTimeout(() => {
            const sampleStudents = [
                { id: 1, name: 'Ali Benali', massar_code: 'J879456000' },
                { id: 2, name: 'Fatima Alami', massar_code: 'J879456001' },
                { id: 3, name: 'Hassan Tazi', massar_code: 'J879456002' },
                { id: 4, name: 'Amina Bennani', massar_code: 'J879456003' },
                { id: 5, name: 'Omar Idrissi', massar_code: 'J879456004' }
            ];
            
            let html = '';
            sampleStudents.forEach(student => {
                html += `
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <h6 class="mb-1">${student.name}</h6>
                                    <small class="text-muted">${student.massar_code}</small>
                                </div>
                                <div class="col-md-4">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-outline-success">
                                            <input type="radio" name="attendance[${student.id}][status]" value="1" required> Present
                                        </label>
                                        <label class="btn btn-outline-danger">
                                            <input type="radio" name="attendance[${student.id}][status]" value="0" required> Absent
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <input type="hidden" name="attendance[${student.id}][student_id]" value="${student.id}">
                                    <input type="text" 
                                           class="form-control form-control-sm" 
                                           name="attendance[${student.id}][justification]" 
                                           placeholder="Justification (if absent)">
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            studentsContainer.innerHTML = html;
        }, 1000);
    }
    
    // Update summary when attendance changes
    document.addEventListener('change', function(e) {
        if (e.target.name && e.target.name.includes('[status]')) {
            updateSummary();
        }
    });
    
    function updateSummary() {
        const presentCount = document.querySelectorAll('input[value="1"]:checked').length;
        const absentCount = document.querySelectorAll('input[value="0"]:checked').length;
        
        // Update summary display if it exists
        const summaryElements = document.querySelectorAll('.text-success, .text-danger');
        if (summaryElements.length >= 2) {
            summaryElements[0].textContent = presentCount;
            summaryElements[1].textContent = absentCount;
        }
    }
});
</script>
@endpush
@endsection 