@extends('layouts.parent')

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
                    Children's Timetables Overview
                    <div class="page-title-subheading">View timetables for all your children.</div>
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('parent.children.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fa fa-arrow-left mr-2"></i>Back to Children
                </a>
            </div>
        </div>
    </div>

    <!-- Children Timetables -->
    @foreach($children as $child)
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-clock icon-gradient bg-ripe-malin"></i>
                {{ $child->first_name }} {{ $child->last_name }} - {{ $child->classroom->display_name ?? 'No Class' }}
            </div>
        </div>
        <div class="card-body">
            @php
                $childTimetable = $timetables->get($child->id);
            @endphp
            
            @if($childTimetable && $childTimetable->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Subject</th>
                                <th>Time</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($childTimetable as $day => $sessions)
                                @foreach($sessions as $session)
                                <tr>
                                    <td>
                                        <span class="badge badge-info">{{ $day }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $session->subject->name ?? 'N/A' }}</strong>
                                    </td>
                                    <td>
                                        {{ $session->formatted_start_time }} - {{ $session->formatted_end_time }}
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $session->Type === 'Teacher' ? 'primary' : 'success' }}">
                                            {{ $session->Type }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center text-muted">
                    <i class="fa fa-clock fa-3x mb-3"></i>
                    <p>No timetable found for {{ $child->first_name }}</p>
                    <p>Please check with the school administration.</p>
                </div>
            @endif
        </div>
    </div>
    @endforeach

    @if($children->count() === 0)
    <!-- No Children Message -->
    <div class="main-card mb-3 card">
        <div class="card-body text-center">
            <i class="fa fa-users fa-5x text-muted mb-3"></i>
            <h4 class="text-muted">No Children Found</h4>
            <p class="text-muted">It appears you don't have any children registered in the system yet.</p>
        </div>
    </div>
    @endif
</div>
@endsection


