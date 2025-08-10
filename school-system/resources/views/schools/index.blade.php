@extends('layouts.superadmin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="mb-1" style="font-weight:600;">Manage Schools</h2>
        <p class="text-muted mb-0" style="font-size:1.05em;">View, create, edit, or delete schools on the platform.</p>
    </div>
    <a href="{{ route('superadmin.schools.create') }}" class="btn btn-primary">
        <i class="fa fa-plus"></i> Add New School
    </a>
</div>
<!-- If icons below are not visible, ensure FontAwesome CSS is loaded in your layout -->
<div class="main-card mb-3 card">
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered align-middle">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Logo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>School Level</th>
                        <th>Created</th>
                        <th>Updated</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schools as $school)
                    <tr>
                        <td>{{ $school->id }}</td>
                        <td>
                            @if($school->logo)
                                <img src="{{ asset('storage/' . $school->logo) }}" alt="Logo" style="width:40px;height:40px;object-fit:cover;border-radius:4px;">
                            @else
                                <span class="fa fa-image text-secondary" style="font-size:1.5em;"></span>
                            @endif
                        </td>
                        <td>{{ $school->name }}</td>
                        <td>{{ $school->email }}</td>
                        <td>{{ $school->address }}</td>
                        <td>{{ $school->phone }}</td>
                        <td>
                            <span class="badge badge-info text-capitalize">{{ $school->school_level }}</span>
                        </td>
                        <td>{{ $school->created_at->format('Y-m-d') }}</td>
                        <td>{{ $school->updated_at->format('Y-m-d') }}</td>
                        <td>
                            @if(!$school->deleted_at)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('superadmin.schools.edit', $school) }}" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a>
                            <button type="button" class="btn btn-sm btn-danger delete-school-btn" data-action="{{ route('superadmin.schools.destroy', $school) }}" title="Delete">
    <i class="fa fa-trash"></i>
</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center">No schools found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var deleteButtons = document.querySelectorAll('.delete-school-btn');
    var deleteForm = document.getElementById('deleteSchoolForm');
    var deleteModal = $('#deleteSchoolModal');
    deleteButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            deleteForm.action = btn.getAttribute('data-action');
            deleteModal.modal('show');
        });
    });
    // Ensure modal closes and backdrop is removed on cancel/close
    $('#deleteSchoolModal').on('hidden.bs.modal', function () {
        document.body.classList.remove('modal-open');
        $('.modal-backdrop').remove();
    });
});
</script>
@endpush
@include('schools.partials.delete-modal')
@endsection
