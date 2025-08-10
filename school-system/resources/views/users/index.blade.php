@extends('layouts.superadmin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="mb-1" style="font-weight:600;">Manage Users</h2>
        <p class="text-muted mb-0" style="font-size:1.05em;">View, create, edit, or delete users on the platform.</p>
    </div>
    <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary">
        <i class="fa fa-plus"></i> Add New User
    </a>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form class="form-inline mb-3" method="GET">
    <div class="form-group mr-2">
        <select name="school_id" class="form-control">
            <option value="">All Schools</option>
            @foreach($schools as $school)
                <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group mr-2">
        <select name="type" class="form-control">
            <option value="">All User Types</option>
            @foreach($userTypes as $type)
                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-outline-primary"><i class="fa fa-filter"></i> Filter</button>
</form>
<div class="main-card mb-3 card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered align-middle">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>School</th>
                        <th>Type</th>
                        <th>Created</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php $shown = false; @endphp
                    @forelse($users as $user)
                        @if(optional($user->role)->name !== 'super_admin')
                            @php $shown = true; @endphp
                            <tr>
                                <td>{{ $user->id ?? '-' }}</td>
                                <td>{{ $user->full_name ?? '-' }}</td>
                                <td>{{ $user->account_email ?? '-' }}</td>
                                <td>{{ $user->account_school ?? '-' }}</td>
                                <td>{{ ucfirst($user->type ?? '-') }}</td>
                                <td>{{ isset($user->created_at) ? (is_object($user->created_at) ? $user->created_at->format('Y-m-d') : $user->created_at) : '-' }}</td>
                                <td>
                                    @if($user->deleted_at)
                                        <span class="badge badge-danger">Inactive</span>
                                    @else
                                        <span class="badge badge-success">Active</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a>
                                    <button type="button" class="btn btn-sm btn-danger delete-user-btn" data-action="{{ route('superadmin.users.destroy', $user) }}" title="Delete"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No users found.</td>
                        </tr>
                    @endforelse
                    @if(!$shown)
                        <tr><td colspan="8" class="text-center">No users found.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="deleteUserForm">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Are you sure you want to delete this user?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var deleteButtons = document.querySelectorAll('.delete-user-btn');
        var deleteForm = document.getElementById('deleteUserForm');
        var deleteModal = $('#deleteUserModal');
        deleteButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                deleteForm.action = this.getAttribute('data-action');
                deleteModal.modal('show');
            });
        });
    });
</script>
@endpush
