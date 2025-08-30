@extends('layouts.student')

@section('content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-clock icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    My Timetable
                    <div class="page-title-subheading">View your weekly class schedule.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Timetable -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-clock icon-gradient bg-ripe-malin"></i>
                Weekly Schedule
            </div>
        </div>
        <div class="card-body">
            @if($timetable->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Monday</th>
                                <th>Tuesday</th>
                                <th>Wednesday</th>
                                <th>Thursday</th>
                                <th>Friday</th>
                                <th>Saturday</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $timeSlots = [
                                    '08:00' => '08:00 - 09:00',
                                    '09:00' => '09:00 - 10:00',
                                    '10:00' => '10:00 - 11:00',
                                    '11:00' => '11:00 - 12:00',
                                    '12:00' => '12:00 - 13:00',
                                    '14:00' => '14:00 - 15:00',
                                    '15:00' => '15:00 - 16:00',
                                    '16:00' => '16:00 - 17:00'
                                ];
                            @endphp
                            
                            @foreach($timeSlots as $startTime => $timeSlot)
                            <tr>
                                <td class="text-center font-weight-bold">
                                    <span class="badge badge-info">{{ $timeSlot }}</span>
                                </td>
                                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                                    <td class="text-center">
                                        @php
                                            $class = $timetable->get($day, collect())->first(function($item) use ($startTime) {
                                                return \Carbon\Carbon::parse($item->Time_start)->format('H:i') === $startTime;
                                            });
                                        @endphp
                                        
                                        @if($class)
                                            <div class="timetable-class">
                                                <div class="subject-name font-weight-bold">{{ $class->subject->name ?? 'N/A' }}</div>
                                                <div class="teacher-name text-muted">{{ $class->staff->first_name ?? 'N/A' }} {{ $class->staff->last_name ?? 'N/A' }}</div>
                                                <div class="classroom text-muted">{{ $class->subject->classroom->name ?? 'N/A' }}</div>
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
            @else
                <div class="text-center text-muted py-4">
                    <i class="fa fa-clock fa-3x mb-3"></i>
                    <p>No timetable data available</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Today's Classes -->
    <div class="row">
        <div class="col-md-8">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-calendar icon-gradient bg-ripe-malin"></i>
                        Today's Classes
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $today = now()->format('l');
                        $todayClasses = $timetable->get($today, collect());
                    @endphp
                    
                    @if($todayClasses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Subject</th>
                                        <th>Teacher</th>
                                        <th>Classroom</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todayClasses->sortBy('Time_start') as $class)
                                    <tr>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ \Carbon\Carbon::parse($class->Time_start)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($class->Time_end)->format('H:i') }}
                                            </span>
                                        </td>
                                        <td>{{ $class->subject->name ?? 'N/A' }}</td>
                                        <td>{{ $class->staff->first_name ?? 'N/A' }} {{ $class->staff->last_name ?? 'N/A' }}</td>
                                        <td>{{ $class->subject->classroom->name ?? 'N/A' }}</td>
                                        <td>
                                            @php
                                                $now = now();
                                                $startTime = \Carbon\Carbon::parse($class->Time_start);
                                                $endTime = \Carbon\Carbon::parse($class->Time_end);
                                                
                                                if ($now < $startTime) {
                                                    $status = 'upcoming';
                                                    $statusText = 'Upcoming';
                                                    $statusClass = 'warning';
                                                } elseif ($now >= $startTime && $now <= $endTime) {
                                                    $status = 'ongoing';
                                                    $statusText = 'Ongoing';
                                                    $statusClass = 'success';
                                                } else {
                                                    $status = 'completed';
                                                    $statusText = 'Completed';
                                                    $statusClass = 'secondary';
                                                }
                                            @endphp
                                            <span class="badge badge-{{ $statusClass }}">{{ $statusText }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-3">
                            <p>No classes scheduled for today</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-info icon-gradient bg-ripe-malin"></i>
                        Timetable Info
                    </div>
                </div>
                <div class="card-body">
                    <div class="widget-content p-0 mb-3">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="widget-heading">Total Classes</div>
                            </div>
                            <div class="widget-content-right">
                                <div class="widget-numbers text-primary">{{ $timetable->flatten(1)->count() }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="widget-content p-0 mb-3">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="widget-heading">Subjects</div>
                            </div>
                            <div class="widget-content-right">
                                <div class="widget-numbers text-success">{{ $timetable->flatten(1)->unique('subject_id')->count() }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="widget-heading">Teachers</div>
                            </div>
                            <div class="widget-content-right">
                                <div class="widget-numbers text-info">{{ $timetable->flatten(1)->unique('staff_id')->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timetable-class {
    padding: 8px;
    border-radius: 4px;
    background-color: #f8f9fa;
    border-left: 3px solid #007bff;
}

.subject-name {
    font-size: 0.9rem;
    color: #007bff;
}

.teacher-name {
    font-size: 0.8rem;
}

.classroom {
    font-size: 0.8rem;
}
</style>
@endsection 