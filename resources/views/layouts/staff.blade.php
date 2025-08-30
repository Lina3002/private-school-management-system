<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Staff Dashboard') - School Management System</title>
    
    <!-- Kero Template CSS -->
    <link href="{{ asset('kero/assets/css/main.07a59de7b920cd76b874.css') }}" rel="stylesheet">
    
    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/staff-style.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    <div class="app-container">
        <!-- Header -->
        <header class="app-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <a href="{{ route('staff.dashboard') }}" class="app-header-logo">
                            <img src="{{ asset('kero/assets/images/logo.png') }}" alt="Logo" height="40">
                        </a>
                    </div>
                    
                    <div class="col">
                        <div class="app-header-search">
                            <input type="text" class="form-control" placeholder="Search...">
                        </div>
                    </div>
                    
                    <div class="col-auto">
                        <div class="app-header-user">
                            <div class="dropdown">
                                <button class="btn btn-link dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="avatar avatar-sm">
                                        <img src="{{ asset('kero/assets/images/avatars/default.png') }}" alt="User Avatar">
                                    </span>
                                    <span class="ms-2">{{ session('user_name') }}</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="{{ route('staff.profile.index') }}">Profile</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Sidebar -->
        <aside class="app-sidebar">
            <div class="sidebar-header">
                <h3>Staff Portal</h3>
            </div>
            
            <nav class="sidebar-nav">
                <ul class="nav">
                    <li class="nav-item">
                        <a href="{{ route('staff.dashboard') }}" class="nav-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('staff.profile.index') }}" class="nav-link {{ request()->routeIs('staff.profile.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user"></i>
                            <span class="nav-text">Profile</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="app-main">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Kero Template JS -->
    <script src="{{ asset('kero/assets/js/main.js') }}"></script>
    
    <!-- Initialize Bootstrap dropdowns -->
    <script>
        $(document).ready(function() {
            $('.dropdown-toggle').dropdown();
        });
    </script>
    
    @stack('scripts')
</body>
</html>


