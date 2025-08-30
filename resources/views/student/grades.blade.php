@extends('layouts.student')

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
                    My Grades
                    <div class="page-title-subheading">View your academic performance across all subjects.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grade Summary -->
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-midnight-bloom">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Current GPA</div>
                        <div class="widget-subheading">Overall average</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">
                            <span>{{ number_format($grades->avg('value'), 2) ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-arielle-smile">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Total Grades</div>
                        <div class="widget-subheading">Recorded</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $grades->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-grow-early">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Subjects</div>
                        <div class="widget-subheading">With grades</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $grades->unique('subject_id')->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-premium-dark">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Best Subject</div>
                        <div class="widget-subheading">Highest average</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white">
                            <span>{{ $bestSubject ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grades Table -->
    <div class="main-card mb-3 card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="header-icon pe-7s-medal icon-gradient bg-ripe-malin"></i>
                All Grades
            </div>
        </div>
        <div class="card-body">
            @if($grades->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Grade</th>
                                <th>Term</th>
                                <th>Exam Type</th>
                                <th>Date</th>
                                <th>Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grades as $grade)
                            <tr>
                                <td>
                                    <div class="widget-content p-0">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left">
                                                <div class="widget-heading">{{ $grade->subject->name ?? 'N/A' }}</div>
                                                <div class="widget-subheading">{{ $grade->subject->classroom->name ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $grade->grade_color ?? 'primary' }}">
                                        {{ $grade->formatted_grade ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>{{ $grade->term ?? 'N/A' }}</td>
                                <td>{{ $grade->exam_type ?? 'N/A' }}</td>
                                <td>{{ $grade->grading_date ? \Carbon\Carbon::parse($grade->grading_date)->format('M d, Y') : 'N/A' }}</td>
                                <td>{{ $grade->comments ?? 'No comments' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($grades->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $grades->links() }}
                    </div>
                @endif
            @else
                <div class="text-center text-muted py-4">
                    <i class="fa fa-graduation-cap fa-3x mb-3"></i>
                    <p>No grades recorded yet</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Subject Performance Chart -->
    <div class="row">
        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-graph icon-gradient bg-ripe-malin"></i>
                        Subject Performance
                    </div>
                </div>
                <div class="card-body">
                    @if($grades->count() > 0)
                        <canvas id="subjectChart" width="400" height="200"></canvas>
                    @else
                        <div class="text-center text-muted py-3">
                            <p>No data available for chart</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-pie icon-gradient bg-ripe-malin"></i>
                        Grade Distribution
                    </div>
                </div>
                <div class="card-body">
                    @if($grades->count() > 0)
                        <canvas id="gradeChart" width="400" height="200"></canvas>
                    @else
                        <div class="text-center text-muted py-3">
                            <p>No data available for chart</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($grades->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Subject Performance Chart
    const subjectCtx = document.getElementById('subjectChart').getContext('2d');
    const subjectData = @json($grades->groupBy('subject.name')->map(function($group) {
        return $group->avg('value');
    }));
    
    new Chart(subjectCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(subjectData),
            datasets: [{
                label: 'Average Grade',
                data: Object.values(subjectData),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    max: 20
                }
            }
        }
    });
    
    // Grade Distribution Chart
    const gradeCtx = document.getElementById('gradeChart').getContext('2d');
    const gradeData = @json($grades->groupBy(function($grade) {
        if ($grade->value >= 16) return 'Excellent (16-20)';
        elseif ($grade->value >= 14) return 'Very Good (14-15.99)';
        elseif ($grade->value >= 12) return 'Good (12-13.99)';
        elseif ($grade->value >= 10) return 'Pass (10-11.99)';
        else return 'Fail (<10)';
    })->map->count());
    
    new Chart(gradeCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(gradeData),
            datasets: [{
                data: Object.values(gradeData),
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        }
    });
});
</script>
@endif
@endsection 