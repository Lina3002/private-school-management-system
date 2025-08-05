@php
    // Kero asset path helper
    $asset = fn($path) => asset('kero/assets/' . $path);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard</title>
    <link href="{{ asset('main.07a59de7b920cd76b874.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/favicon.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @stack('styles')
</head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        @include('partials.superadmin-header')
        <div class="app-main">
            <div class="app-sidebar-wrapper">
                @include('partials.superadmin-sidebar')
            </div>
            <div class="app-main__outer">
                <div class="app-main__inner">
                    @yield('content')
                </div>
                @include('partials.superadmin-footer')
            </div>
        </div>
    </div>
    <script src="{{ $asset('js/main.js') }}"></script>
    <!-- jQuery and Bootstrap JS for modal functionality -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    @include('schools.partials.delete-modal')
</body>
</html>
