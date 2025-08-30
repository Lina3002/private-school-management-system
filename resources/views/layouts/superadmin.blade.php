@php
    // Kero asset path helper
    $asset = fn($path) => asset('kero/assets/' . $path);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard</title>
    <link href="{{ asset('main.07a59de7b920cd76b874.css') }}" rel="stylesheet">

    <link rel="icon" href="{{ asset('assets/favicon.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @stack('styles')
    <style>
      /* Force remove blur and pointer-events from modal and backdrop */
      .modal,
      .modal-backdrop,
      body.modal-open {
        filter: none !important;
        backdrop-filter: none !important;
        pointer-events: auto !important;
      }
      .modal-backdrop {
        opacity: 0.5 !important; /* Bootstrap default */
      }
    </style>
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
    <!-- jQuery and Bootstrap JS for modal functionality -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ $asset('js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
