<header class="app-header header-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
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
    <div class="app-header__content">
        <div class="app-header-left">
            <div class="search-wrapper">
                <div class="input-holder">
                    <input type="text" class="search-input" placeholder="Type to search">
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
                                    <img width="42" class="rounded-circle" src="{{ asset('kero/assets/images/avatars/1.jpg') }}" alt="">
                                    <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                </a>
                                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                    <a href="{{ route('admin.profile.index') }}" class="dropdown-item">
                                        <i class="fa fa-user mr-2"></i>Profile
                                    </a>
                                    <a href="{{ route('admin.settings.index') }}" class="dropdown-item">
                                        <i class="fa fa-cog mr-2"></i>Settings
                                    </a>
                                    <div tabindex="-1" class="dropdown-divider"></div>
                                    <a href="{{ route('logout') }}" class="dropdown-item">
                                        <i class="fa fa-sign-out-alt mr-2"></i>Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content-left  ml-3 header-user-info">
                            <div class="widget-heading">
                                {{ auth()->user()->name }}
                            </div>
                            <div class="widget-subheading">
                                {{ auth()->user()->email }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
