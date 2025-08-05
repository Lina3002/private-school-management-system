<div class="app-header header-shadow">
    <div class="app-header__logo">
        <a href="{{ route('superadmin.dashboard') }}" data-toggle="tooltip" data-placement="bottom" title="SuperAdmin Dashboard" class="logo-src"></a>
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
    <div class="app-header__content d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center flex-grow-1">
            <div class="search-wrapper mr-4" style="min-width:260px;">
                <i class="search-icon-wrapper typcn typcn-zoom-outline"></i>
                <input type="text" placeholder="Search..." style="width:100%; min-width:180px;">
            </div>
        </div>
        <div class="app-header-right d-flex align-items-center">
            <div class="header-dots d-flex align-items-center mr-3">
                <div class="dropdown mr-2">
                    <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="p-0 btn btn-link">
                        <i class="typcn typcn-th-large-outline"></i>
                    </button>
                    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-xl rm-pointers dropdown-menu dropdown-menu-right">
                        <div class="dropdown-menu-header">
                            <div class="dropdown-menu-header-inner bg-vicious-stance">
                                <div class="menu-header-image opacity-4" style="background-image: url('{{ asset('kero/assets/images/dropdown-header/city5.jpg') }}');"></div>
                                <div class="menu-header-content text-white">
                                    <h5 class="menu-header-title">Quick Actions</h5>
                                    <h6 class="menu-header-subtitle">Shortcuts</h6>
                                </div>
                            </div>
                        </div>
                        <!-- Add quick action items here if needed -->
                    </div>
                </div>
                <div class="dropdown">
                    <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="p-0 btn btn-link">
                        <i class="typcn typcn-bell"></i>
                        <span class="badge badge-dot badge-dot-sm badge-danger">Notifications</span>
                    </button>
                    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-xl rm-pointers dropdown-menu dropdown-menu-right">
                        <div class="dropdown-menu-header mb-0">
                            <div class="dropdown-menu-header-inner bg-night-sky">
                                <div class="menu-header-image opacity-5" style="background-image: url('{{ asset('kero/assets/images/dropdown-header/city2.jpg') }}');"></div>
                                <div class="menu-header-content text-light">
                                    <h5 class="menu-header-title">Notifications</h5>
                                    <h6 class="menu-header-subtitle">You have new notifications</h6>
                                </div>
                            </div>
                        </div>
                        <!-- Add notification items here if needed -->
                    </div>
                </div>
            </div>
            <div class="widget-content p-0">
                <div class="widget-content-wrapper d-flex align-items-center">
                    <div class="widget-content-left">
                        <div class="btn-group">
                            <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                <img width="42" class="rounded-circle" src="{{ asset('kero/assets/images/avatars/1.jpg') }}" alt="">
                                <i class="fa fa-angle-down ml-2 opacity-8"></i>
                            </a>
                            <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                <a href="#" class="dropdown-item">Profile</a>
                                <a href="#" class="dropdown-item">Settings</a>
                                <div tabindex="-1" class="dropdown-divider"></div>
                                <a href="{{ route('logout') }}" class="dropdown-item">Logout</a>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content-left ml-3 header-user-info">
                        <div class="widget-heading">
                            {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                        </div>
                        <div class="widget-subheading">
                            Super Administrator
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
