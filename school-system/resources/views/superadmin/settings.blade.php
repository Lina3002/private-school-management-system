@extends('layouts.superadmin')
@section('content')
<div class="row mb-3">
    <div class="col-md-8">
        <h2>Platform Settings</h2>
    </div>
    <div class="col-md-4 text-right">
        <button class="btn btn-primary" data-toggle="modal" data-target="#addSettingModal"><i class="fa fa-plus"></i> Add Setting</button>
    </div>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="card mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="thead-light">
                    <tr>
                        <th>Key</th>
                        <th>Value</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Group</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($settings as $setting)
                    <tr>
                        <td>{{ $setting->key }}</td>
                        <td>{{ $setting->value }}</td>
                        <td>{{ $setting->type }}</td>
                        <td>{{ $setting->description }}</td>
                        <td>{{ $setting->group }}</td>
                        <td>
                            <button class="btn btn-sm btn-info edit-setting-btn" data-id="{{ $setting->id }}" data-key="{{ $setting->key }}" data-value="{{ $setting->value }}" data-type="{{ $setting->type }}" data-description="{{ $setting->description }}" data-group="{{ $setting->group }}"><i class="fa fa-edit"></i></button>
                            <form action="{{ route('superadmin.settings.destroy', $setting) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this setting?')"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">No settings found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Add Setting Modal -->
<div class="modal fade" id="addSettingModal" tabindex="-1" role="dialog" aria-labelledby="addSettingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('superadmin.settings.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addSettingModalLabel">Add Setting</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="settingKey">Key</label>
                        <input type="text" name="key" id="settingKey" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="settingValue">Value</label>
                        <input type="text" name="value" id="settingValue" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="settingType">Type</label>
                        <select name="type" id="settingType" class="form-control" required>
                            <option value="string">String</option>
                            <option value="int">Integer</option>
                            <option value="bool">Boolean</option>
                            <option value="json">JSON</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="settingDescription">Description</label>
                        <input type="text" name="description" id="settingDescription" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="settingGroup">Group</label>
                        <input type="text" name="group" id="settingGroup" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Setting Modal -->
<div class="modal fade" id="editSettingModal" tabindex="-1" role="dialog" aria-labelledby="editSettingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" id="editSettingForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editSettingModalLabel">Edit Setting</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editSettingValue">Value</label>
                        <input type="text" name="value" id="editSettingValue" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="editSettingType">Type</label>
                        <select name="type" id="editSettingType" class="form-control" required>
                            <option value="string">String</option>
                            <option value="int">Integer</option>
                            <option value="bool">Boolean</option>
                            <option value="json">JSON</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editSettingDescription">Description</label>
                        <input type="text" name="description" id="editSettingDescription" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="editSettingGroup">Group</label>
                        <input type="text" name="group" id="editSettingGroup" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var editBtns = document.querySelectorAll('.edit-setting-btn');
    var editForm = document.getElementById('editSettingForm');
    editBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = btn.getAttribute('data-id');
            var value = btn.getAttribute('data-value');
            var type = btn.getAttribute('data-type');
            var description = btn.getAttribute('data-description');
            var group = btn.getAttribute('data-group');
            editForm.action = '/superadmin/settings/' + id;
            document.getElementById('editSettingValue').value = value;
            document.getElementById('editSettingType').value = type;
            document.getElementById('editSettingDescription').value = description;
            document.getElementById('editSettingGroup').value = group;
            $('#editSettingModal').modal('show');
        });
    });
    $('#editSettingModal').on('hidden.bs.modal', function () {
        document.body.classList.remove('modal-open');
        $('.modal-backdrop').remove();
    });
    $('#addSettingModal').on('hidden.bs.modal', function () {
        document.body.classList.remove('modal-open');
        $('.modal-backdrop').remove();
    });
});
</script>
@endpush
