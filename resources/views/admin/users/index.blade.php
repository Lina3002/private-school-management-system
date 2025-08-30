@extends('layouts.admin')

@section('admin-content')
<div class="app-main__inner">
    <!-- Page Title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-users icon-gradient bg-mean-fruit"></i>
                </div>
                <div>
                    User & Staff Management
                    <div class="page-title-subheading">Manage your school's users and staff members efficiently.</div>
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-lg">
                    <i class="fa fa-plus mr-2"></i>Invite User/Staff
                </a>
            </div>
        </div>
    </div>

    <!-- Users Section -->
    <div class="row">
        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-user icon-gradient bg-ripe-malin"></i>
                        Users
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>
                                        <div class="widget-content p-0">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-left mr-3">
                                                    <img width="40" class="rounded-circle" src="{{ asset('kero/assets/images/avatars/' . rand(1, 10) . '.jpg') }}" alt="">
                                                </div>
                                                <div class="widget-content-left">
                                                    <div class="widget-heading">{{ $user->first_name }} {{ $user->last_name }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role)
                                            @if($user->role->name === 'admin')
                                                <div class="badge badge-danger">{{ strtoupper($user->role->name) }}</div>
                                            @elseif($user->role->name === 'manager')
                                                <div class="badge badge-primary">{{ strtoupper($user->role->name) }}</div>
                                            @else
                                                <div class="badge badge-info">{{ strtoupper($user->role->name) }}</div>
                                            @endif
                                        @else
                                            <div class="badge badge-secondary">No Role</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        <i class="fa fa-users fa-3x mb-3"></i>
                                        <p>No users found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Members Section -->
        <div class="col-md-6">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title">
                        <i class="header-icon pe-7s-id icon-gradient bg-ripe-malin"></i>
                        Staff Members
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Job Title</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($staffs as $staff)
                                <tr>
                                    <td>
                                        <div class="widget-content p-0">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-left mr-3">
                                                    <img width="40" class="rounded-circle" src="{{ asset('kero/assets/images/avatars/' . rand(1, 10) . '.jpg') }}" alt="">
                                                </div>
                                                <div class="widget-content-left">
                                                    <div class="widget-heading">{{ $staff->first_name }} {{ $staff->last_name }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $staff->email }}</td>
                                    <td>
                                        @if($staff->jobTitle)
                                            <div class="badge badge-info">{{ strtoupper($staff->jobTitle->name) }}</div>
                                        @else
                                            <div class="badge badge-secondary">No Title</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.staff.edit', $staff->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.staff.destroy', $staff->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this staff account?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        <i class="fa fa-id-card fa-3x mb-3"></i>
                                        <p>No staff members found</p>
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

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-midnight-bloom">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Total Users</div>
                        <div class="widget-subheading">System accounts</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $users->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-arielle-smile">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Total Staff</div>
                        <div class="widget-subheading">Employees</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $staffs->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-grow-early">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Admins</div>
                        <div class="widget-subheading">Administrators</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $users->where('role.name', 'admin')->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3 widget-content bg-premium-dark">
                <div class="widget-content-wrapper text-white">
                    <div class="widget-content-left">
                        <div class="widget-heading">Teachers</div>
                        <div class="widget-subheading">Teaching staff</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-white"><span>{{ $staffs->where('jobTitle.name', 'teacher')->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
