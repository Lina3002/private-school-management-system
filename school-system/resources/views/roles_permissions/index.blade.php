@extends('layouts.superadmin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="mb-1" style="font-weight:600;">Roles & Permissions Management</h2>
        <p class="text-muted mb-0" style="font-size:1.05em;">Manage roles, job titles, and permissions for the platform.</p>
    </div>
    <a href="{{ route('superadmin.roles_permissions.permission.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Permission</a>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="main-card mb-3 card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Roles</span>
                <a href="{{ route('superadmin.roles_permissions.role.create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Role</a>
            </div>
            <div class="card-body">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Name</th>
                                <th>School</th>
                                <th>Permissions</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                                @if($role->name !== 'super_admin')
                                    <tr>
                                        <td>{{ ucfirst(str_replace('_', ' ', $role->name)) }}</td>
                                        <td>{{ $role->school->name ?? '-' }}</td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                @foreach($permissions as $permission)
                                                    <div class="form-check mb-1">
                                                        <input type="checkbox" class="form-check-input role-permission-checkbox"
                                                            data-role-id="{{ $role->id }}"
                                                            data-permission-id="{{ $permission->id }}"
                                                            data-school-id="{{ $role->school_id }}"
                                                            id="role-perm-{{ $role->id }}-{{ $permission->id }}"
                                                            @if($role->permissions && $role->permissions->contains('id', $permission->id)) checked @endif>
                                                        <label class="form-check-label" for="role-perm-{{ $role->id }}-{{ $permission->id }}">{{ $permission->title }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info edit-role-btn" data-id="{{ $role->id }}"><i class="fa fa-edit"></i></button>
                                            <button class="btn btn-sm btn-danger delete-role-btn" data-id="{{ $role->id }}"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="main-card mb-3 card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Job Titles</span>
                <a href="{{ route('superadmin.roles_permissions.job_title.create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Job Title</a>
            </div>
            <div class="card-body">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Name</th>
                                <th>School</th>
                                <th>Permissions</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jobTitles as $jobTitle)
                                <tr>
                                    <td>{{ ucfirst($jobTitle->name) }}</td>
                                    <td>{{ $jobTitle->school->name ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            @foreach($permissions as $permission)
                                            <div class="form-check mb-1">
                                                <input type="checkbox" class="form-check-input jobtitle-permission-checkbox"
                                                    data-jobtitle-id="{{ $jobTitle->id }}"
                                                    data-permission-id="{{ $permission->id }}"
                                                    data-school-id="{{ $jobTitle->school_id }}"
                                                    id="jobtitle-perm-{{ $jobTitle->id }}-{{ $permission->id }}"
                                                    @if($jobTitle->permissions->contains('id', $permission->id)) checked @endif>
                                                <label class="form-check-label" for="jobtitle-perm-{{ $jobTitle->id }}-{{ $permission->id }}">{{ $permission->title }}</label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info edit-jobtitle-btn" data-id="{{ $jobTitle->id }}"><i class="fa fa-edit"></i></button>
                                        <button class="btn btn-sm btn-danger delete-jobtitle-btn" data-id="{{ $jobTitle->id }}"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="main-card mb-3 card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Permissions</span>
        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addPermissionModal"><i class="fa fa-plus"></i> Add Permission</button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="thead-light">
                    <tr>
                        <th>Title</th>
                        <th>School</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissions as $permission)
                        <tr>
                            <td>{{ $permission->title }}</td>
                            <td>{{ $permission->school->name ?? '-' }}</td>
                            <td>
                                <button class="btn btn-sm btn-info edit-permission-btn" data-id="{{ $permission->id }}"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger delete-permission-btn" data-id="{{ $permission->id }}"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteRolesPermissionsModal" tabindex="-1" role="dialog" aria-labelledby="deleteRolesPermissionsModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteRolesPermissionsModalLabel">Confirm Delete</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" id="deleteRolesPermissionsForm">
        @csrf
        @method('DELETE')
        <div class="modal-body">
          <span id="deleteRolesPermissionsMsg">Are you sure you want to delete this item?</span>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var deleteButtons = document.querySelectorAll('.delete-role-btn, .delete-jobtitle-btn, .delete-permission-btn');
    var deleteForm = document.getElementById('deleteRolesPermissionsForm');
    var deleteModal = $('#deleteRolesPermissionsModal');
    var msgSpan = document.getElementById('deleteRolesPermissionsMsg');
    deleteButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            let entityType = '', entityName = '', url = '';
            if (btn.classList.contains('delete-role-btn')) {
                entityType = 'role';
                entityName = btn.closest('tr').querySelector('td').innerText.trim();
                url = '/superadmin/roles-permissions/role/' + btn.getAttribute('data-id');
            } else if (btn.classList.contains('delete-jobtitle-btn')) {
                entityType = 'job title';
                entityName = btn.closest('tr').querySelector('td').innerText.trim();
                url = '/superadmin/roles-permissions/job-title/' + btn.getAttribute('data-id');
            } else if (btn.classList.contains('delete-permission-btn')) {
                entityType = 'permission';
                entityName = btn.closest('tr').querySelector('td').innerText.trim();
                url = '/superadmin/roles-permissions/permission/' + btn.getAttribute('data-id');
            }
            msgSpan.innerHTML = `Are you sure you want to delete the <b>${entityType}</b> <span class='text-danger'>${entityName}</span>?`;
            deleteForm.action = url;
            deleteModal.modal('show');
        });
    });
    // Ensure modal closes and backdrop is removed on cancel/close
    $('#deleteRolesPermissionsModal').on('hidden.bs.modal', function () {
        document.body.classList.remove('modal-open');
        $('.modal-backdrop').remove();
    });
});
</script>
@endpush


@push('scripts')
<script>
$(function() {
    // Edit Role
    $(document).on('click', '.edit-role-btn', function() {
        window.location.href = '/superadmin/roles-permissions/role/' + $(this).data('id') + '/edit';
    });
    // Edit Job Title
    $(document).on('click', '.edit-jobtitle-btn', function() {
        window.location.href = '/superadmin/roles-permissions/job-title/' + $(this).data('id') + '/edit';
    });
    // Edit Permission
    $(document).on('click', '.edit-permission-btn', function() {
        window.location.href = '/superadmin/roles-permissions/permission/' + $(this).data('id') + '/edit';
    });
    
    
    

    // Add Role AJAX
    
        e.preventDefault();
        $.ajax({
            url: '{{ route('superadmin.roles_permissions.role.store') }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(resp) {
                $('#addRoleModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                alert('Failed to add role. ' + (xhr.responseJSON?.message || ''));
            }
        });
    });
    // Add Job Title AJAX
    
        e.preventDefault();
        $.ajax({
            url: '{{ route('superadmin.roles_permissions.job_title.store') }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(resp) {
                $('#addJobTitleModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                alert('Failed to add job title. ' + (xhr.responseJSON?.message || ''));
            }
        });
    });
    // Add Permission AJAX
    
    e.preventDefault();
    var roleId = $('#editRoleId').val();
    $.ajax({
        url: '/superadmin/roles-permissions/role/' + roleId,
        type: 'PUT',
        data: $(this).serialize(),
        success: function(resp) {
            $('#editRoleModal').modal('hide');
            location.reload();
        },
        error: function(xhr) {
            alert('Failed to update role. ' + (xhr.responseJSON?.message || ''));
        }
    });
});


        e.preventDefault();
        $.ajax({
            url: '{{ route('superadmin.roles_permissions.permission.store') }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(resp) {
                $('#addPermissionModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                alert('Failed to add permission. ' + (xhr.responseJSON?.message || ''));
            }
        });
    });
});
</script>
@endpush
