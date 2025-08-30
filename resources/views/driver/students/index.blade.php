@extends('layouts.driver')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">My Students</h4>
                </div>
                <div class="card-body">
                    @if($students->count())
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Class</th>
                                        <th>Route</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        <tr>
                                            <td>{{ $student->full_name }}</td>
                                            <td>{{ $student->classroom->name ?? '-' }}</td>
                                            <td>{{ $student->transport->name ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>No students assigned to your routes.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
