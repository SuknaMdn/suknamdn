<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">

        <meta name="application-name" content="{{ config('app.name') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>
        <link href="{{ asset('developer/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

		<link rel="shortcut icon" href="{{ asset('developer/media/logos/favicon.svg') }}" />

		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />

		<link href="{{ asset('developer/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('developer/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('developer/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('developer/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('developer/css/custom.css') }}" rel="stylesheet" type="text/css" />
        <link href='https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.css' rel='stylesheet' />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
        @vite('resources/css/app.css')
        @livewireStyles

        @stack('styles')

    </head>

    <body id="kt_app_body" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="false" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
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
                        {{-- @livewire('partials.developer.sidebar') --}}

                        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                            <div class="d-flex flex-column flex-column-fluid">
								<!--begin::Toolbar-->

                                {{ $slot }}

                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

		<!--begin::Javascript-->
		<script>var hostUrl = "assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="{{ asset('developer/plugins/global/plugins.bundle.js') }}"></script>
		<script src="{{ asset('developer/js/scripts.bundle.js') }}"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Vendors Javascript(used for this page only)-->
		<script src="{{ asset('developer/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
		<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>
		<script src="{{ asset('developer/plugins/custom/datatables/datatables.bundle.js') }}"></script>
		<!--end::Vendors Javascript-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="{{ asset('developer/js/widgets.bundle.js') }}"></script>
		<script src="{{ asset('developer/js/custom/widgets.js') }}"></script>
		<script src="{{ asset('developer/js/custom/apps/chat/chat.js') }}"></script>
		<script src="{{ asset('developer/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
		<script src="{{ asset('developer/js/custom/utilities/modals/create-app.js') }}"></script>
		<script src="{{ asset('developer/js/custom/utilities/modals/create-project/type.js') }}"></script>
		<script src="{{ asset('developer/js/custom/utilities/modals/create-project/budget.js') }}"></script>
		<script src="{{ asset('developer/js/custom/utilities/modals/create-project/settings.js') }}"></script>
		<script src="{{ asset('developer/js/custom/utilities/modals/create-project/team.js') }}"></script>
		<script src="{{ asset('developer/js/custom/utilities/modals/create-project/targets.js') }}"></script>
		<script src="{{ asset('developer/js/custom/utilities/modals/create-project/files.js') }}"></script>
		<script src="{{ asset('developer/js/custom/utilities/modals/create-project/complete.js') }}"></script>
		<script src="{{ asset('developer/js/custom/utilities/modals/create-project/main.js') }}"></script>
		<script src="{{ asset('developer/js/custom/utilities/modals/users-search.js') }}"></script>
		<!--end::Custom Javascript-->

        <script src="{{ asset('developer/js/custom.js') }}"></script>
        <script src='https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.js'></script>

        @stack('scripts')
        {{-- <script src="{{ asset('developer/js/custom/dashboard.js') }}"></script> --}}

        @livewire('notifications')

        @vite('resources/js/app.js')
        @livewireScripts
    </body>
</html>
