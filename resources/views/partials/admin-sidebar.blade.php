<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <a href="{{ route('admin.dashboard') }}" data-toggle="tooltip" data-placement="bottom" title="School Admin Dashboard" class="logo-src"></a>
    </div>
    <div class="scrollbar-sidebar scrollbar-container">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading">Menu</li>
                <li class="{{ request()->routeIs('admin.dashboard') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-rocket"></i>
                        Dashboard
                    </a>
                </li>
                
                <li class="app-sidebar__heading">School Management</li>
                <li class="{{ request()->routeIs('admin.users.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.users.index') }}">
                        <i class="metismenu-icon pe-7s-users"></i>
                        User Management
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.classes.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.classes.index') }}">
                        <i class="metismenu-icon pe-7s-home"></i>
                        Class Management
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.subjects.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.subjects.index') }}">
                        <i class="metismenu-icon pe-7s-book"></i>
                        Subject Management
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.students.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.students.index') }}">
                        <i class="metismenu-icon pe-7s-gleam"></i>
                        Student Management
                    </a>
                </li>
                
                <li class="app-sidebar__heading">Academic</li>
                <li class="{{ request()->routeIs('admin.grades.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.grades.index') }}">
                        <i class="metismenu-icon pe-7s-medal"></i>
                        Grade Management
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.timetables.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.timetables.index') }}">
                        <i class="metismenu-icon pe-7s-clock"></i>
                        Timetable Management
                    </a>
                </li>
                
                <li class="{{ request()->routeIs('admin.attendance.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.attendance.index') }}">
                        <i class="metismenu-icon pe-7s-check"></i>
                        Attendance Management
                    </a>
                </li>
                
                <li class="app-sidebar__heading">Services</li>
                <li class="{{ request()->routeIs('admin.transport.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.transport.index') }}">
                        <i class="metismenu-icon pe-7s-car"></i>
                        Transportation
                    </a>
                </li>
                
                <li class="app-sidebar__heading">Administration</li>
                <li class="{{ request()->routeIs('admin.billing') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.billing') }}">
                        <i class="metismenu-icon pe-7s-cash"></i>
                        Billing & Profits
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.logs') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.logs') }}">
                        <i class="metismenu-icon pe-7s-note2"></i>
                        Logs & Monitoring
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.settings.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.settings.index') }}">
                        <i class="metismenu-icon pe-7s-tools"></i>
                        School Settings
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
