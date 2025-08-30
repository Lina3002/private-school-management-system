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
                    Visual Timetable
                    <div class="page-title-subheading">View timetables in a visual grid format.</div>
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('admin.timetables.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fa fa-arrow-left mr-2"></i>Back to Timetables
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-filter icon-gradient bg-ripe-malin"></i>
                Select Timetable View
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.timetables.visual') }}" class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="type" class="form-label">View Type</label>
                        <select class="form-control" id="type" name="type" onchange="toggleEntitySelect()">
                            <option value="classroom" {{ $type === 'classroom' ? 'selected' : '' }}>Class Timetable</option>
                            <option value="teacher" {{ $type === 'teacher' ? 'selected' : '' }}>Teacher Timetable</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4" id="classroom-select" style="{{ $type === 'teacher' ? 'display: none;' : '' }}">
                    <div class="form-group">
                        <label for="classroom_id" class="form-label">Select Class</label>
                        <select class="form-control" name="entity_id">
                            <option value="">Choose a class...</option>
                            @foreach($classrooms as $classroom)
                                <option value="{{ $classroom->id }}" {{ request('entity_id') == $classroom->id ? 'selected' : '' }}>
                                    {{ $classroom->display_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4" id="teacher-select" style="{{ $type === 'classroom' ? 'display: none;' : '' }}">
                    <div class="form-group">
                        <label for="teacher_id" class="form-label">Select Teacher</label>
                        <select class="form-control" name="entity_id">
                            <option value="">Choose a teacher...</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ request('entity_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->first_name }} {{ $teacher->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search mr-2"></i>View Timetable
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($entity)
    <!-- Visual Timetable -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-clock icon-gradient bg-ripe-malin"></i>
                {{ $type === 'teacher' ? 'Teacher' : 'Class' }} Timetable: 
                <strong>
                    @if($type === 'teacher')
                        {{ $entity->first_name }} {{ $entity->last_name }}
                    @else
                        {{ $entity->display_name }}
                    @endif
                </strong>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered timetable-grid">
                    <thead>
                        <tr>
                            <th style="width: 100px;">Time</th>
                            @foreach($days as $day)
                                @php
                                    $dayMapping = [
                                        'Mon' => 'Monday',
                                        'Tue' => 'Tuesday',
                                        'Wed' => 'Wednesday',
                                        'Thu' => 'Thursday',
                                        'Fri' => 'Friday',
                                        'Sat' => 'Saturday',
                                        'Sun' => 'Sunday'
                                    ];
                                    $fullDayName = $dayMapping[$day] ?? $day;
                                @endphp
                                <th class="text-center">{{ $fullDayName }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timeSlots as $index => $time)
                            <tr>
                                <td class="time-slot">
                                    <strong>{{ $time }}</strong>
                                </td>
                                @foreach($days as $day)
                                    @php
                                        $slotTimetables = $timetables->where('Day', $day)
                                                                   ->where('Time_start', $time);
                                        $timetable = $slotTimetables->first();
                                    @endphp
                                    <td class="timetable-cell {{ $timetable ? 'has-content' : '' }}">
                                        @if($timetable)
                                            <div class="timetable-item">
                                                <div class="subject-name">
                                                    <strong>{{ $timetable->subject->name ?? 'N/A' }}</strong>
                                                </div>
                                                @if($type === 'classroom')
                                                    <div class="teacher-name">
                                                        {{ $timetable->staff->first_name ?? '' }} {{ $timetable->staff->last_name ?? '' }}
                                                    </div>
                                                @else
                                                    <div class="class-name">
                                                        {{ $timetable->classroom->display_name ?? 'N/A' }}
                                                    </div>
                                                @endif
                                                <div class="time-range">
                                                    {{ $timetable->Time_start }} - {{ $timetable->Time_end }}
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Timetable Summary -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-graph icon-gradient bg-ripe-malin"></i>
                Timetable Summary
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="text-center">
                        <h3 class="text-primary">{{ $timetables->count() }}</h3>
                        <p class="text-muted">Total Sessions</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h3 class="text-success">{{ $timetables->unique('subject_id')->count() }}</h3>
                        <p class="text-muted">Subjects</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h3 class="text-info">{{ $timetables->unique('Day')->count() }}</h3>
                        <p class="text-muted">Days</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h3 class="text-warning">{{ $timetables->groupBy('Day')->map->count()->max() }}</h3>
                        <p class="text-muted">Max Sessions/Day</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.timetable-grid {
    font-size: 0.9rem;
}

.timetable-grid th {
    background-color: #f8f9fa;
    text-align: center;
    vertical-align: middle;
    min-width: 120px;
}

.time-slot {
    background-color: #e9ecef;
    text-align: center;
    vertical-align: middle;
    font-weight: bold;
}

.timetable-cell {
    height: 80px;
    vertical-align: middle;
    text-align: center;
    position: relative;
}

.timetable-cell.has-content {
    background-color: #d4edda;
    border: 2px solid #28a745;
}

.timetable-item {
    padding: 5px;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.subject-name {
    font-weight: bold;
    color: #155724;
    margin-bottom: 2px;
}

.teacher-name, .class-name {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 2px;
}

.time-range {
    font-size: 0.7rem;
    color: #6c757d;
}
</style>

<script>
function toggleEntitySelect() {
    const type = document.getElementById('type').value;
    const classroomSelect = document.getElementById('classroom-select');
    const teacherSelect = document.getElementById('teacher-select');
    
    if (type === 'classroom') {
        classroomSelect.style.display = 'block';
        teacherSelect.style.display = 'none';
    } else {
        classroomSelect.style.display = 'none';
        teacherSelect.style.display = 'block';
    }
}
</script>
@endsection
