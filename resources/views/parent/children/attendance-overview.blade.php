@extends('layouts.parent')

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
                    Children's Attendance Overview
                    <div class="page-title-subheading">Monitor attendance records of all your children.</div>
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('parent.children.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fa fa-arrow-left mr-2"></i>Back to Children
                </a>
            </div>
        </div>
    </div>

    <!-- Children Summary Cards -->
    <div class="row">
        @foreach($children as $child)
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card mb-3 widget-content">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                        <div class="widget-heading">{{ $child->first_name }} {{ $child->last_name }}</div>
                        <div class="widget-subheading">{{ $child->classroom->display_name ?? 'No Class' }}</div>
                    </div>
                    <div class="widget-content-right">
                        @php
                            $summary = $attendanceSummary->get($child->id);
                            $total = $summary ? $summary->total : 0;
                            $present = $summary ? $summary->present : 0;
                            $rate = $total > 0 ? round(($present / $total) * 100, 1) : 0;
                        @endphp
                        <div class="widget-numbers text-{{ $rate >= 90 ? 'success' : ($rate >= 80 ? 'warning' : 'danger') }}">
                            <span>{{ $rate }}%</span>
                        </div>
                        <div class="widget-subheading">Attendance Rate</div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row text-center">
                        <div class="col-4">
                            <small class="text-muted">Total</small>
                            <div class="font-weight-bold">{{ $total }}</div>
                        </div>
                        <div class="col-4">
                            <small class="text-muted">Present</small>
                            <div class="font-weight-bold text-success">{{ $present }}</div>
                        </div>
                        <div class="col-4">
                            <small class="text-muted">Absent</small>
                            <div class="font-weight-bold text-danger">{{ $total - $present }}</div>
                        </div>
                    </div>
                    <div class="btn-group w-100 mt-2" role="group">
                        <a href="{{ route('parent.children.attendance', $child->id) }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-chart-line mr-1"></i>View Details
                        </a>
                        <a href="{{ route('parent.children.grades', $child->id) }}" class="btn btn-info btn-sm">
                            <i class="fa fa-medal mr-1"></i>Grades
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Overall Statistics -->
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-midnight-bloom">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Total Children</div>
                        <div class="widget-subheading">Enrolled</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">{{ $children->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-arielle-smile">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Average Rate</div>
                        <div class="widget-subheading">Attendance</div>
                    </div>
                    <div class="widget-content-right">
                        @php
                            $totalRate = 0;
                            $childCount = 0;
                            foreach($children as $child) {
                                $summary = $attendanceSummary->get($child->id);
                                if($summary && $summary->total > 0) {
                                    $totalRate += ($summary->present / $summary->total) * 100;
                                    $childCount++;
                                }
                            }
                            $avgRate = $childCount > 0 ? round($totalRate / $childCount, 1) : 0;
                        @endphp
                        <div class="widget-numbers text-white">{{ $avgRate }}%</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-grow-early">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Best Performer</div>
                        <div class="widget-subheading">Attendance</div>
                    </div>
                    <div class="widget-content-right">
                        @php
                            $bestRate = 0;
                            $bestChild = null;
                            foreach($children as $child) {
                                $summary = $attendanceSummary->get($child->id);
                                if($summary && $summary->total > 0) {
                                    $rate = ($summary->present / $summary->total) * 100;
                                    if($rate > $bestRate) {
                                        $bestRate = $rate;
                                        $bestChild = $child;
                                    }
                                }
                            }
                        @endphp
                        <div class="widget-numbers text-white">{{ $bestRate }}%</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-premium-dark">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Total Records</div>
                        <div class="widget-subheading">This Year</div>
                    </div>
                    <div class="widget-content-right">
                        @php
                            $totalRecords = 0;
                            foreach($attendanceSummary as $summary) {
                                $totalRecords += $summary->total ?? 0;
                            }
                        @endphp
                        <div class="widget-numbers text-white">{{ $totalRecords }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


