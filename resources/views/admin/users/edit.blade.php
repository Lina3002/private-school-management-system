@extends('layouts.admin')

@section('admin-content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-user icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    Edit User
                    <div class="page-title-subheading">Update user information and permissions.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                @csrf
                @method('PUT')
                
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="first_name" class="">First Name</label>
                            <input name="first_name" id="first_name" placeholder="First Name" type="text" class="form-control" value="{{ old('first_name', $user->first_name) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="last_name" class="">Last Name</label>
                            <input name="last_name" id="last_name" placeholder="Last Name" type="text" class="form-control" value="{{ old('last_name', $user->last_name) }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="email" class="">Email</label>
                            <input name="email" id="email" placeholder="Email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="role_id" class="">Role</label>
                            <select name="role_id" id="role_id" class="form-control" required>
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="password" class="">Password (leave blank to keep current)</label>
                            <input name="password" id="password" placeholder="Password" type="password" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="password_confirmation" class="">Confirm Password</label>
                            <input name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" type="password" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-12">
                        <div class="position-relative form-group">
                            <label for="phone" class="">Phone</label>
                            <input name="phone" id="phone" placeholder="Phone" type="text" class="form-control" value="{{ old('phone', $user->phone ?? '') }}">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-12">
                        <div class="position-relative form-group">
                            <label for="address" class="">Address</label>
                            <textarea name="address" id="address" placeholder="Address" class="form-control" rows="3">{{ old('address', $user->address ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            <i class="fa fa-save mr-2"></i>Update User
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 