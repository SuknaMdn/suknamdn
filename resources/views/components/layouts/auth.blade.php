<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">

        <meta name="application-name" content="{{ config('app.name') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
		<link rel="shortcut icon" href="{{ asset('developer/media/logos/favicon.svg') }}" />

        @vite('resources/css/app.css')
        @livewireStyles
        <link href="{{ asset('developer/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('developer/css/custom.css') }}" rel="stylesheet" type="text/css" />
    </head>

    <body id="kt_body" class="app-blank bgi-size-cover bgi-attachment-fixed bgi-position-center">
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
        <div class="d-flex flex-column flex-root" id="kt_app_root">
            <!--begin::Page bg image-->
            <style>body { background-image: url("{{ asset('developer/media/auth/bg10.jpeg') }}"); } [data-bs-theme="dark"] body { background-image: url("{{ asset('developer/media/auth/bg10-dark.jpeg') }}"); }</style>
            <!--end::Page bg image-->
            <!--begin::Authentication - Sign-in -->
            <div class="d-flex flex-column flex-lg-row flex-column-fluid">
                <!--begin::Aside-->

                <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center m-auto justify-content-center p-12">
                    <!--begin::Wrapper-->
                    <div class="bg-body d-flex flex-column flex-center rounded-4 w-md-600px p-10">
                        <!--begin::Content-->
                        <div class="d-flex flex-center flex-column align-items-stretch h-lg-100 w-md-400px">

                            {{ $slot }}

                            {{-- footer --}}
                            <div class="d-flex flex-stack">
                                <!--begin::Languages-->
                                <div class="me-10">
                                    <!--begin::Toggle-->
                                    <button class="btn btn-flex btn-link btn-color-gray-700 btn-active-color-primary rotate fs-base" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                        <img data-kt-element="current-lang-flag" class="w-20px h-20px rounded me-3" src="{{ asset('developer/media/flags/united-states.svg') }}" alt="" />
                                        <span data-kt-element="current-lang-name" class="me-1">English</span>
                                        <i class="ki-outline ki-down fs-5 text-muted rotate-180 m-0"></i>
                                    </button>
                                    <!--end::Toggle-->
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-4 fs-7" data-kt-menu="true" id="kt_auth_lang_menu">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link d-flex px-5" data-kt-lang="English">
                                                <span class="symbol symbol-20px me-4">
                                                    <img data-kt-element="lang-flag" class="rounded-1" src="{{ asset('developer/media/flags/saudi-arabia.svg') }}" alt="" />
                                                </span>
                                                <span data-kt-element="lang-name">عربي</span>
                                            </a>
                                        </div>

                                    </div>
                                    <!--end::Menu-->
                                </div>
                                <!--end::Languages-->
                                <!--begin::Links-->
                                <div class="d-flex fw-semibold text-primary fs-base gap-5">
                                    <a href="pages/team.html" target="_blank">Terms</a>
                                    <a href="pages/contact.html" target="_blank">Contact Us</a>
                                </div>
                                <!--end::Links-->
                            </div>
                            <!--end::Footer-->
                        </div>
                        <!--end::Content-->
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Authentication - Sign-in-->
        </div>


        @livewire('notifications')

        @vite('resources/js/app.js')
        @livewireScripts

		<script>var hostUrl = "assets/";</script>

        <script src="{{ asset('developer/plugins/global/plugins.bundle.js') }}"></script>
		<script src="{{ asset('developer/js/scripts.bundle.js') }}"></script>
		<script src="{{ asset('developer/js/custom/authentication/sign-in/general.js') }}"></script>
    </body>
</html>
