<div>
    <div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="auto" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">

        <div id="kt_app_sidebar_menu" data-kt-menu="true" class="menu menu-sub-indention menu-rounded menu-column menu-active-bg menu-title-gray-600 menu-icon-gray-500 menu-state-primary menu-arrow-gray-500 fw-semibold fs-6 py-4 py-lg-6 ms-lg-n7 px-2 px-lg-0">
            <div id="kt_app_sidebar_menu_wrapper" class="hover-scroll-y px-1 px-lg-5" data-kt-sticky="true" data-kt-sticky-name="app-sidebar-menu-sticky" data-kt-sticky-offset="{default: false, xl: '500px'}" data-kt-sticky-release="#kt_app_stats" data-kt-sticky-width="250px" data-kt-sticky-left="auto" data-kt-sticky-top="100px" data-kt-sticky-animation="false" data-kt-sticky-zindex="95" data-kt-scroll="true" data-kt-scroll-activate="{default: true, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_header, #kt_app_header" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="20px">

                <div class="menu-item">

                    <div class="menu-content">
                        <span class="menu-section fs-5 fw-bolder ps-1 py-1">Main</span>
                    </div>

                </div>


                <div class="menu-item">

                    <a class="menu-link {{ request()->is('developer/projects') ? 'active' : '' }} " href="{{ route('developer.projects') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">Projects</span>
                    </a>

                </div>


                <div class="menu-item">

                    <a class="menu-link" href="dashboards/ecommerce.html">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">eCommerce</span>
                    </a>

                </div>


                <div class="menu-item">

                    <a class="menu-link" href="dashboards/projects.html">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">Projects</span>
                    </a>

                </div>


            </div>
        </div>

    </div>
</div>
