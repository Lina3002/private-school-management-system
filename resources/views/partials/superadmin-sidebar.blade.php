<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <a href="{{ route('superadmin.dashboard') }}" data-toggle="tooltip" data-placement="bottom" title="SuperAdmin Dashboard" class="logo-src"></a>
        <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
            <span class="hamburger-box">
                <span class="hamburger-inner"></span>
            </span>
        </button>
    </div>
    <div class="scrollbar-sidebar scrollbar-container">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading">Menu</li>
                <li class="{{ request()->routeIs('superadmin.dashboard') ? 'mm-active' : '' }}">
                    <a href="{{ route('superadmin.dashboard') }}" class="{{ request()->routeIs('superadmin.dashboard') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-rocket"></i>
                        Dashboard
                    </a>
                </li>
                <li class="app-sidebar__heading">Schools</li>
                <li class="{{ request()->routeIs('superadmin.schools.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('superadmin.schools.index') }}">
                        <i class="metismenu-icon pe-7s-home"></i>
                        Manage Schools
                    </a>
                </li>
                <li class="app-sidebar__heading">Accounts</li>
                <li class="{{ request()->routeIs('superadmin.users.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('superadmin.users.index') }}">
                        <i class="metismenu-icon pe-7s-users"></i>
                        User Management
                    </a>
                </li>
                <li class="{{ request()->routeIs('superadmin.roles_permissions.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('superadmin.roles_permissions.index') }}">
                        <i class="metismenu-icon pe-7s-id"></i>
                        Roles & Permissions
                    </a>
                </li>
                <li class="app-sidebar__heading">Platform</li>
                <li class="{{ request()->routeIs('superadmin.settings.index') ? 'mm-active' : '' }}">
                    <a href="{{ route('superadmin.settings.index') }}">
                        <i class="metismenu-icon pe-7s-tools"></i>
                        Platform Settings
                    </a>
                </li>
                <li class="{{ request()->routeIs('superadmin.logs') ? 'mm-active' : '' }}">
                    <a href="{{ route('superadmin.logs') }}">
                        <i class="metismenu-icon pe-7s-note2"></i>
                        Logs & Monitoring
                    </a>
                </li>
                <li class="{{ request()->routeIs('superadmin.billing') ? 'mm-active' : '' }}">
                    <a href="{{ route('superadmin.billing') }}">
                        <i class="metismenu-icon pe-7s-cash"></i>
                        Billing & Profits
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
