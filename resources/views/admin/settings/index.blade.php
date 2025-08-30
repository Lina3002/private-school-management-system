@extends('layouts.admin')

@section('admin-content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-tools icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div>
                    School Settings
                    <div class="page-title-subheading">Configure your school's platform settings.
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-home icon-gradient bg-ripe-malin"> </i>
                        School Information
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.school.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="school_name">Name</label>
                            <input type="text" id="school_name" name="name" class="form-control" value="{{ $school->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="school_email">Email</label>
                            <input type="email" id="school_email" name="email" class="form-control" value="{{ $school->email }}">
                        </div>
                        <div class="form-group">
                            <label for="school_phone">Phone</label>
                            <input type="text" id="school_phone" name="phone" class="form-control" value="{{ $school->phone }}">
                        </div>
                        <div class="form-group">
                            <label for="school_address">Address</label>
                            <input type="text" id="school_address" name="address" class="form-control" value="{{ $school->address }}">
                        </div>
                        <div class="form-group">
                            <label for="school_level">School Level</label>
                            <input type="text" id="school_level" name="school_level" class="form-control" value="{{ $school->school_level }}">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save mr-2"></i>Save School
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-user icon-gradient bg-mean-fruit"> </i>
                        My Account
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" name="first_name" class="form-control" value="{{ auth()->user()->first_name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" id="last_name" name="last_name" class="form-control" value="{{ auth()->user()->last_name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" id="phone" name="phone" class="form-control" value="{{ auth()->user()->phone }}">
                        </div>
                        <div class="form-group">
                            <label for="password">New Password (optional)</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save mr-2"></i>Save Account
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon lnr-cog icon-gradient bg-happy-itmeo"> </i>
                        Platform Configuration
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Setting Key</th>
                                    <th>Current Value</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($settings as $setting)
                                    <tr>
                                        <td>
                                            <strong>{{ $setting->key }}</strong>
                                            <br>
                                            <small class="text-muted">Configuration parameter</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $setting->value }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#editSetting{{ $setting->id }}">
                                                    <i class="fa fa-edit"></i> Edit
                                                </button>
                                                <form action="{{ route('admin.settings.destroy', $setting->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this setting?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editSetting{{ $setting->id }}" tabindex="-1" role="dialog" aria-labelledby="editSettingLabel{{ $setting->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editSettingLabel{{ $setting->id }}">Edit Setting: {{ $setting->key }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('admin.settings.update', $setting->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="value{{ $setting->id }}">Value:</label>
                                                            <input type="text" class="form-control" id="value{{ $setting->id }}" name="value" value="{{ $setting->value }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Update Setting</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">
                                            <i class="pe-7s-tools" style="font-size: 3rem; opacity: 0.3;"></i>
                                            <p class="mt-2">No settings configured yet.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
