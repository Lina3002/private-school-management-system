<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'School Management') }} - Driver</title>
    <!-- Kero Template Assets -->
    <link rel="stylesheet" href="{{ asset('main.07a59de7b920cd76b874.css') }}">
    <link rel="stylesheet" href="{{ asset('kero/assets/custom-sidebar-fix.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <!-- Header -->
        <div class="app-header header-shadow" style="margin-bottom:0;">
            <div class="app-header__logo">
                <div class="logo-src">
                    <img src="{{ asset('kero/assets/images/logo-inverse.png') }}" alt="Logo">
                </div>
                <div class="header__pane ml-auto">
                    <div>
                        <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="app-header__mobile-menu">
                <div>
                    <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="app-header__content">
                <div class="app-header-left">
                    <div class="search-wrapper">
                        <div class="input-holder">
                            <input type="text" class="search-input" placeholder="Search routes, students...">
                            <button class="search-icon"><span></span></button>
                        </div>
                        <button class="close"></button>
                    </div>
                </div>
                <div class="app-header-right">
                    <div class="header-btn-lg pr-0">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                            <img width="42" class="rounded-circle" src="{{ asset('kero/assets/images/avatars/' . rand(1, 10) . '.jpg') }}" alt="">
                                            <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('driver.dashboard') }}" tabindex="0" class="dropdown-item">
                                                <i class="fa fa-home mr-2"></i>Dashboard
                                            </a>
                                            <a href="{{ route('driver.routes') }}" tabindex="0" class="dropdown-item">
                                                <i class="fa fa-route mr-2"></i>My Routes
                                            </a>
                                            <div tabindex="-1" class="dropdown-divider"></div>
                                            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fa fa-sign-out-alt mr-2"></i>Logout
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content-left ml-3 header-user-info">
                                    <div class="widget-heading">
                                        {{ Auth::user()?->first_name ?? 'Driver' }} {{ Auth::user()?->last_name ?? '' }}
                                    </div>
                                    <div class="widget-subheading">
                                        Driver
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="app-sidebar sidebar-shadow" style="width:280px; margin:0; padding:0;">
            <div class="app-sidebar__inner">
                <ul class="vertical-nav-menu">
                    <li class="app-sidebar__heading">Dashboard</li>
                    <li>
                        <a href="{{ route('driver.dashboard') }}" class="mm-active">
                            <i class="metismenu-icon pe-7s-home"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="app-sidebar__heading">Transportation</li>
                    <li>
                        <a href="{{ route('driver.routes') }}">
                            <i class="metismenu-icon pe-7s-route"></i>
                            My Routes
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('driver.students') }}">
                            <i class="metismenu-icon pe-7s-gleam"></i>
                            My Students
                        </a>
                    </li>
                    <li>
                        @if(isset($routes) && $routes->count())
                            <a href="{{ route('driver.routes.map', ['route' => $routes->first()->id]) }}">
                                <i class="metismenu-icon pe-7s-map"></i>
                                Route Map
                            </a>
                        @else
                            <a href="#" class="disabled">
                                <i class="metismenu-icon pe-7s-map"></i>
                                Route Map
                            </a>
                        @endif
                    </li>
                    <li class="app-sidebar__heading">Attendance</li>
                    <li>
                        <a href="{{ route('driver.attendance.mark') }}">
                            <i class="metismenu-icon pe-7s-check"></i>
                            Mark Transport Attendance
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('driver.attendance.index') }}">
                            <i class="metismenu-icon pe-7s-graph"></i>
                            Attendance Reports
                        </a>
                    </li>
                    <li class="app-sidebar__heading">Profile</li>
                    <li>
                        <a href="{{ route('driver.profile.index') }}">
                            <i class="metismenu-icon pe-7s-user"></i>
                            My Profile
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="app-main">
            <div class="app-main__outer">
                <div class="app-main__inner" style="padding-top:0;">
                    @yield('content')
                </div>
                <!-- Footer -->
                <div class="app-wrapper-footer">
                    <div class="app-footer">
                        <div class="app-footer__inner">
                            <div class="app-footer-left">
                                <div class="footer-dots">
                                    <div class="dropdown">
                                        <a aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dot-btn-wrapper">
                                            <i class="dot-btn-icon lnr-bullhorn icon-gradient bg-mean-fruit"></i>
                                            <div class="badge badge-dot badge-abs badge-dot-sm badge-danger">1</div>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-xl rm-pointers dropdown-menu">
                                            <div class="dropdown-menu-header mb-0">
                                                <div class="dropdown-menu-header-inner bg-deep-blue">
                                                    <div class="menu-header-image opacity-1" style="background-image: url('{{ asset('kero/assets/images/menu-header.png') }}');"></div>
                                                    <div class="menu-header-content text-white">
                                                        <h5 class="menu-header-title">Notifications</h5>
                                                        <h6 class="menu-header-subtitle">You have 1 unread message</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="app-footer-right">
                                <div class="header-dots">
                                    <div class="dropdown">
                                        <a aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dot-btn-wrapper">
                                            <i class="dot-btn-icon lnr-heart-pulse icon-gradient bg-mean-fruit"></i>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-xl rm-pointers dropdown-menu">
                                            <div class="dropdown-menu-header">
                                                <div class="dropdown-menu-header-inner bg-tabs-gradient">
                                                    <div class="menu-header-image opacity-1" style="background-image: url('{{ asset('kero/assets/images/menu-header.png') }}');"></div>
                                                    <div class="menu-header-content text-white">
                                                        <h5 class="menu-header-title">Quick Actions</h5>
                                                        <h6 class="menu-header-subtitle">Manage transportation</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('kero/assets/js/main.js') }}"></script>
    <script>
        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.querySelector('.hamburger');
            const sidebar = document.querySelector('.app-sidebar');
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('closed-sidebar');
                });
            }
            // Mobile sidebar toggle
            const mobileToggle = document.querySelector('.mobile-toggle-nav');
            if (mobileToggle) {
                mobileToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('closed-sidebar');
                });
            }
            // Set active sidebar item based on current route
            const currentPath = window.location.pathname;
            const sidebarLinks = document.querySelectorAll('.vertical-nav-menu a');
            sidebarLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.parentElement.classList.add('mm-active');
                }
            });
            // Ensure Bootstrap dropdowns work
            if (typeof $ !== 'undefined') {
                $('.dropdown-toggle').dropdown();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <!-- Header -->
        <div class="app-header header-shadow">
            <div class="app-header__logo">
                <div class="logo-src">
                    <img src="{{ asset('kero/assets/images/logo-inverse.png') }}" alt="Logo">
                </div>
                <div class="header__pane ml-auto">
                    <div>
                        <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="app-header__mobile-menu">
                <div>
                    <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="app-header__content">
                <div class="app-header-left">
                    <div class="search-wrapper">
                        <div class="input-holder">
                            <input type="text" class="search-input" placeholder="Search routes, students...">
                            <button class="search-icon"><span></span></button>
                        </div>
                        <button class="close"></button>
                    </div>
                </div>
                <div class="app-header-right">
                    <div class="header-btn-lg pr-0">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                            <img width="42" class="rounded-circle" src="{{ asset('kero/assets/images/avatars/' . rand(1, 10) . '.jpg') }}" alt="">
                                            <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('driver.dashboard') }}" tabindex="0" class="dropdown-item">
                                                <i class="fa fa-home mr-2"></i>Dashboard
                                            </a>
                                            <a href="{{ route('driver.routes') }}" tabindex="0" class="dropdown-item">
                                                <i class="fa fa-route mr-2"></i>My Routes
                                            </a>
                                            <div tabindex="-1" class="dropdown-divider"></div>
                                            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fa fa-sign-out-alt mr-2"></i>Logout
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content-left  ml-3 header-user-info">
                                    <div class="widget-heading">
                                        {{ Auth::user()?->first_name ?? 'Driver' }} {{ Auth::user()?->last_name ?? '' }}
                                    </div>
                                    <div class="widget-subheading">
                                        Driver
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="app-sidebar sidebar-shadow" style="width:280px; margin:0; padding:0;">
            <div class="app-sidebar__inner">
                <ul class="vertical-nav-menu">
                    <li class="app-sidebar__heading">Dashboard</li>
                    <li>
                        <a href="{{ route('driver.dashboard') }}" class="mm-active">
                            <i class="metismenu-icon pe-7s-home"></i>
                            Dashboard
                        </a>
                    </li>
                    
                    <li class="app-sidebar__heading">Transportation</li>
                    <li>
                        <a href="{{ route('driver.routes') }}">
                            <i class="metismenu-icon pe-7s-route"></i>
                            My Routes
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('driver.students') }}">
                            <i class="metismenu-icon pe-7s-gleam"></i>
                            My Students
                        </a>
                    </li>
                    <li>
                        @if(isset($routes) && $routes->count())
    <a href="{{ route('driver.routes.map', ['route' => $routes->first()->id]) }}">
        <i class="metismenu-icon pe-7s-map"></i>
        Route Map
    </a>
@else
    <a href="#" class="disabled">
        <i class="metismenu-icon pe-7s-map"></i>
        Route Map
    </a>
@endif
                    </li>
                    
                    <li class="app-sidebar__heading">Attendance</li>
                    <li>
                        <a href="{{ route('driver.attendance.mark') }}">
    <i class="metismenu-icon pe-7s-check"></i>
    Mark Transport Attendance
</a>
                    </li>
                    <li>
                        <a href="{{ route('driver.attendance.index') }}">
    <i class="metismenu-icon pe-7s-graph"></i>
    Attendance Reports
</a>
                    </li>
                    
                    <li class="app-sidebar__heading">Profile</li>
                    <li>
                        <a href="{{ route('driver.profile.index') }}">
                            <i class="metismenu-icon pe-7s-user"></i>
                            My Profile
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="app-main">
            <div class="app-main__outer">
                <div class="app-main__inner">
                    @yield('content')
                </div>
                
                <!-- Footer -->
                <div class="app-wrapper-footer">
                    <div class="app-footer">
                        <div class="app-footer__inner">
                            <div class="app-footer-left">
                                <div class="footer-dots">
                                    <div class="dropdown">
                                        <a aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dot-btn-wrapper">
                                            <i class="dot-btn-icon lnr-bullhorn icon-gradient bg-mean-fruit"></i>
                                            <div class="badge badge-dot badge-abs badge-dot-sm badge-danger">1</div>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-xl rm-pointers dropdown-menu">
                                            <div class="dropdown-menu-header mb-0">
                                                <div class="dropdown-menu-header-inner bg-deep-blue">
                                                    <div class="menu-header-image opacity-1" style="background-image: url('{{ asset('kero/assets/images/menu-header.png') }}');"></div>
                                                    <div class="menu-header-content text-white">
                                                        <h5 class="menu-header-title">Notifications</h5>
                                                        <h6 class="menu-header-subtitle">You have 1 unread message</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="app-footer-right">
                                <div class="header-dots">
                                    <div class="dropdown">
                                        <a aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dot-btn-wrapper">
                                            <i class="dot-btn-icon lnr-heart-pulse icon-gradient bg-mean-fruit"></i>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-xl rm-pointers dropdown-menu">
                                            <div class="dropdown-menu-header">
                                                <div class="dropdown-menu-header-inner bg-tabs-gradient">
                                                    <div class="menu-header-image opacity-1" style="background-image: url('{{ asset('kero/assets/images/menu-header.png') }}');"></div>
                                                    <div class="menu-header-content text-white">
                                                        <h5 class="menu-header-title">Quick Actions</h5>
                                                        <h6 class="menu-header-subtitle">Manage transportation</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('kero/assets/scripts/main.07a59de7b920cd76b874.js') }}"></script>
    <script>
        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.querySelector('.hamburger');
            const sidebar = document.querySelector('.app-sidebar');
            
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('closed-sidebar');
                });
            }
            
            // Mobile sidebar toggle
            const mobileToggle = document.querySelector('.mobile-toggle-nav');
            if (mobileToggle) {
                mobileToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('closed-sidebar');
                });
            }
        });
    </script>
</body>
</html> 