
<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
    <head>
        <base href="../../" />
		<title>@yield('title')</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
        <link href="{{ asset('developer/plugins/global/plugins.bundle.css') }}" rel="stylesheet">
        <link href="{{ asset('developer/css/style.bundle.css') }}" rel="stylesheet">
        <!--end::Global Stylesheets Bundle-->
		<script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="auth-bg bgi-size-cover bgi-position-center bgi-no-repeat" style="background: #a19f9f00 !important">
		<!--begin::Theme mode setup on page load-->
		<script>
			var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }
		</script>

        <div class="card card-flush w-lg-650px py-5">
            <div class="card-body py-15 py-lg-20">
                <h1 class="fw-bolder fs-2hx text-gray-900 mb-4">@yield('code')</h1>
                <div class="fw-semibold fs-6 text-gray-500 mb-7">@yield('message').</div>

                <div class="mb-3">
                    <img src="{{ asset('developer/media/illustrations/unitedpalms-1/13.png') }}" class="mw-100 mh-300px theme-light-show" alt="" />
                    <img src="{{ asset('developer/media/illustrations/unitedpalms-1/13-dark.png') }}" class="mw-100 mh-300px theme-dark-show" alt="" />
                </div>
            </div>
        </div>

		<script>var hostUrl = "assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
        <script src="{{ asset('developer/plugins/global/plugins.bundle.js') }}"></script>
		<script src="{{ asset('developer/js/scripts.bundle.js') }}"></script>
		<!--end::Global Javascript Bundle-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>
