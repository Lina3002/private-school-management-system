<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'School Management') }} - Admin</title>
    
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
        @include('partials.admin-header')
        <div class="app-main">
            <div class="app-sidebar-wrapper">
                @include('partials.admin-sidebar')
            </div>
            <div class="app-main__outer">
                <div class="app-main__inner">
                    @yield('admin-content')
                </div>
                
                @include('partials.admin-footer')
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
