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
                                @if($role->name && $role->name !== 'super_admin')
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

    // --- Direct Delete for Roles ---
    $(document).on('click', '.delete-role-btn', function() {
        if (!confirm('Are you sure you want to delete this role?')) return;
        var id = $(this).data('id');
        $.ajax({
            url: '/superadmin/roles-permissions/role/' + id,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(resp) {
                location.reload();
            },
            error: function(xhr) {
                // Error on delete: do nothing
            }
        });
    });
    // --- Direct Delete for Job Titles ---
    $(document).on('click', '.delete-jobtitle-btn', function() {
        if (!confirm('Are you sure you want to delete this job title?')) return;
        var id = $(this).data('id');
        $.ajax({
            url: '/superadmin/roles-permissions/job-title/' + id,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(resp) {
                location.reload();
            },
            error: function(xhr) {
                // Error on delete: do nothing
            }
        });
    });
    // --- Direct Delete for Permissions ---
    $(document).on('click', '.delete-permission-btn', function() {
        if (!confirm('Are you sure you want to delete this permission?')) return;
        var id = $(this).data('id');
        $.ajax({
            url: '/superadmin/roles-permissions/permission/' + id,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: 'DELETE'
            },
            success: function(resp) {
                location.reload();
            },
            error: function(xhr) {
                // Error on delete: do nothing
            }
        });
    });

    // --- Permission Assignment for Roles ---
    $(document).on('change', '.role-permission-checkbox', function() {
        var checkbox = $(this);
        var roleId = checkbox.data('role-id');
        var permissionId = checkbox.data('permission-id');
        var schoolId = checkbox.data('school-id');
        var assign = checkbox.is(':checked') ? 1 : 0;
        checkbox.prop('disabled', true);
        $.ajax({
            url: '/superadmin/roles-permissions/assign-role-permission',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                role_id: roleId,
                permission_id: permissionId,
                school_id: schoolId,
                assign: assign
            },
            success: function(resp) {
                checkbox.prop('disabled', false);
            },
            error: function(xhr) {
                checkbox.prop('checked', !assign);
                checkbox.prop('disabled', false);
                alert('Failed to update permission: ' + (xhr.responseJSON?.message || xhr.statusText));
            }
        });
    });

    // --- Permission Assignment for Job Titles ---
    $(document).on('change', '.jobtitle-permission-checkbox', function() {
        var checkbox = $(this);
        var jobTitleId = checkbox.data('jobtitle-id');
        var permissionId = checkbox.data('permission-id');
        var schoolId = checkbox.data('school-id');
        var assign = checkbox.is(':checked') ? 1 : 0;
        checkbox.prop('disabled', true);
        $.ajax({
            url: '/superadmin/roles-permissions/assign-jobtitle-permission',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                job_title_id: jobTitleId,
                permission_id: permissionId,
                school_id: schoolId,
                assign: assign
            },
            success: function(resp) {
                checkbox.prop('disabled', false);
            },
            error: function(xhr) {
                checkbox.prop('checked', !assign);
                checkbox.prop('disabled', false);
                alert('Failed to update permission: ' + (xhr.responseJSON?.message || xhr.statusText));
            }
        });
    });


    // Add Role AJAX
    $(document).on('submit', '#addRoleForm', function(e) {
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
    $(document).on('submit', '#addJobTitleForm', function(e) {
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
    $(document).on('submit', '#addPermissionForm', function(e) {
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

    // Edit Role AJAX
    $(document).on('submit', '#editRoleForm', function(e) {
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

});
</script>
@endpush
