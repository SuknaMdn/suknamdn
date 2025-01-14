<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">

    <title>{{ $title ?? '' }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('developer/media/logos/favicon.svg') }}" />

    <!-- Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Core CSS -->
    <link href="{{ asset('developer/plugins/global/plugins.bundle.css') }}" rel="stylesheet">
    <link href="{{ asset('developer/css/style.bundle.css') }}" rel="stylesheet">

    <!-- Optional CSS - Load when needed -->
    @stack('pre-styles')
    <link href="{{ asset('developer/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet">
    <link href="{{ asset('developer/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet">
    <link href="{{ asset('developer/css/custom.css') }}" rel="stylesheet">
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.css' rel='stylesheet'>

    @vite('resources/css/app.css')
    @livewireStyles
    @stack('styles')
</head>

<body id="kt_app_body"
    data-kt-app-header-fixed-mobile="true"
    data-kt-app-sidebar-enabled="true"
    data-kt-app-sidebar-fixed="false"
    data-kt-app-sidebar-push-header="true"
    data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true"
    data-kt-app-toolbar-enabled="true"
    class="app-default">

    <!-- Theme Mode Script - Moved to external file -->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if ( document.documentElement ) {
            if ( document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; }
            }
            if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>

    <x-livewire-alert::scripts />

    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            @livewire('partials.developer.navbar')

            <div class="app-wrapper d-flex" id="kt_app_wrapper">
                <div class="app-container container d-flex mt-10">
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <div class="d-flex flex-column flex-column-fluid">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Core JS -->
    <script src="{{ asset('developer/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('developer/js/scripts.bundle.js') }}"></script>

    <!-- Vendor JS -->
    <script src="{{ asset('developer/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}" defer></script>
    <script src="{{ asset('developer/plugins/custom/datatables/datatables.bundle.js') }}" defer></script>

    <!-- Charts - Load async -->
    <script src="https://cdn.amcharts.com/lib/5/index.js" defer></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js" defer></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js" defer></script>
    <script src="https://cdn.amcharts.com/lib/5/radar.js" defer></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js" defer></script>
    <script src="https://cdn.amcharts.com/lib/5/map.js" defer></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js" defer></script>

    <!-- Custom JS -->
    <script src="{{ asset('developer/js/widgets.bundle.js') }}" defer></script>
    <script src="{{ asset('developer/js/custom.js') }}" defer></script>
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.js' defer></script>

    @stack('scripts')
    @livewire('notifications')
    @vite('resources/js/app.js')
    @livewireScripts
</body>
</html>
