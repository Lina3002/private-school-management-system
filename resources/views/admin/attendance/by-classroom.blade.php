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
                    Class Attendance - {{ $classroom->display_name }}
                    <div class="page-title-subheading">Mark attendance for all students in this class.</div>
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fa fa-arrow-left mr-2"></i>Back to Attendance
                </a>
            </div>
        </div>
    </div>

    <!-- Date Selection -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-calendar icon-gradient bg-ripe-malin"></i>
                Select Date
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.attendance.by-classroom', $classroom->id) }}" class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date" class="form-label">Attendance Date</label>
                        <input type="date" class="form-control" id="date" name="date" 
                               value="{{ $date }}" max="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fa fa-search mr-2"></i>Load Attendance
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Attendance Form -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-check icon-gradient bg-ripe-malin"></i>
                Mark Attendance for {{ date('l, F j, Y', strtotime($date)) }}
            </div>
        </div>
        <div class="card-body">
            @if($students->count() > 0)
                <form id="attendanceForm" action="{{ route('admin.attendance.mark-class') }}" method="POST">
                    @csrf
                    <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">
                    <input type="hidden" name="date" value="{{ $date }}">
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Student</th>
                                    <th>Massar Code</th>
                                    <th style="width: 120px;">Status</th>
                                    <th style="width: 200px;">Justification</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $index => $student)
                                    @php
                                        $existingAttendance = $existingAttendance->get($student->id);
                                        $isPresent = $existingAttendance ? $existingAttendance->Status : true;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="widget-content p-0">
                                                <div class="widget-content-wrapper">
                                                    <div class="widget-content-left mr-3">
                                                        @if($student->photo)
                                                            <img width="40" class="rounded-circle" src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->full_name }}">
                                                        @else
                                                            <img width="40" class="rounded-circle" src="{{ asset('kero/assets/images/avatars/1.jpg') }}" alt="{{ $student->full_name }}">
                                                        @endif
                                                    </div>
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">{{ $student->full_name }}</div>
                                                        <div class="widget-subheading">{{ $student->email }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">{{ $student->massar_code }}</span>
                                        </td>
                                        <td>
                                            <div class="form-group mb-0">
                                                <select class="form-control attendance-status" name="attendance_data[{{ $index }}][status]" 
                                                        data-student-id="{{ $student->id }}">
                                                    <option value="1" {{ $isPresent ? 'selected' : '' }}>Present</option>
                                                    <option value="0" {{ !$isPresent ? 'selected' : '' }}>Absent</option>
                                                </select>
                                                <input type="hidden" name="attendance_data[{{ $index }}][student_id]" value="{{ $student->id }}">
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control justification-field" 
                                                   name="attendance_data[{{ $index }}][justification]" 
                                                   placeholder="Optional reason for absence"
                                                   value="{{ $existingAttendance ? $existingAttendance->justification : '' }}"
                                                   style="{{ $isPresent ? 'display: none;' : '' }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="form-actions mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fa fa-save mr-2"></i>Save Attendance
                        </button>
                        <button type="button" class="btn btn-success btn-lg ml-2" onclick="markAllPresent()">
                            <i class="fa fa-check-double mr-2"></i>Mark All Present
                        </button>
                        <button type="button" class="btn btn-warning btn-lg ml-2" onclick="markAllAbsent()">
                            <i class="fa fa-times mr-2"></i>Mark All Absent
                        </button>
                    </div>
                </form>
            @else
                <div class="text-center py-4">
                    <i class="pe-7s-users icon-gradient bg-mean-fruit" style="font-size: 4rem; opacity: 0.5;"></i>
                    <h4 class="mt-3 text-muted">No Students Found</h4>
                    <p class="text-muted">There are no students enrolled in this class.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Attendance Summary -->
    @if($students->count() > 0)
        <div class="main-card mb-3 card">
            <div class="card-header">
                <div class="card-header-title">
                    <i class="header-icon pe-7s-graph icon-gradient bg-ripe-malin"></i>
                    Attendance Summary
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-primary">{{ $students->count() }}</h3>
                            <p class="text-muted">Total Students</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-success" id="present-count">{{ $existingAttendance->where('Status', true)->count() }}</h3>
                            <p class="text-muted">Present</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-danger" id="absent-count">{{ $existingAttendance->where('Status', false)->count() }}</h3>
                            <p class="text-muted">Absent</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-info" id="attendance-rate">
                                {{ $students->count() > 0 ? round(($existingAttendance->where('Status', true)->count() / $students->count()) * 100, 1) : 0 }}%
                            </h3>
                            <p class="text-muted">Attendance Rate</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle attendance status changes
    document.querySelectorAll('.attendance-status').forEach(function(select) {
        select.addEventListener('change', function() {
            const studentId = this.getAttribute('data-student-id');
            const isPresent = this.value === '1';
            const justificationField = this.closest('tr').querySelector('.justification-field');
            
            if (isPresent) {
                justificationField.style.display = 'none';
                justificationField.value = '';
            } else {
                justificationField.style.display = 'block';
            }
            
            updateAttendanceSummary();
        });
    });
});

function markAllPresent() {
    document.querySelectorAll('.attendance-status').forEach(function(select) {
        select.value = '1';
        const justificationField = select.closest('tr').querySelector('.justification-field');
        justificationField.style.display = 'none';
        justificationField.value = '';
    });
    updateAttendanceSummary();
}

function markAllAbsent() {
    document.querySelectorAll('.attendance-status').forEach(function(select) {
        select.value = '0';
        const justificationField = select.closest('tr').querySelector('.justification-field');
        justificationField.style.display = 'block';
    });
    updateAttendanceSummary();
}

function updateAttendanceSummary() {
    const totalStudents = document.querySelectorAll('.attendance-status').length;
    const presentCount = document.querySelectorAll('.attendance-status option:checked[value="1"]').length;
    const absentCount = totalStudents - presentCount;
    const attendanceRate = totalStudents > 0 ? ((presentCount / totalStudents) * 100).toFixed(1) : 0;
    
    document.getElementById('present-count').textContent = presentCount;
    document.getElementById('absent-count').textContent = absentCount;
    document.getElementById('attendance-rate').textContent = attendanceRate + '%';
}

// Handle form submission
document.getElementById('attendanceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show';
            alert.innerHTML = `
                <i class="fa fa-check mr-2"></i>${data.message}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            `;
            document.querySelector('.card-body').insertBefore(alert, document.querySelector('.table-responsive'));
            
            // Auto-hide after 3 seconds
            setTimeout(() => {
                alert.remove();
            }, 3000);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving attendance.');
    });
});
</script>
@endsection


