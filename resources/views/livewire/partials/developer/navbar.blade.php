<div>
    <div id="kt_app_header" class="app-header border-bottom" data-kt-sticky="true" data-kt-sticky-activate="{default: false, lg: true}" data-kt-sticky-name="app-header-sticky" data-kt-sticky-offset="{default: false, lg: '300px'}" style="z-index: 4">

        <div class="app-container container-fluid d-flex align-items-stretch justify-content-between" id="kt_app_header_container">

            <div class="app-header-logo d-flex align-items-center me-lg-9">

                <div class="btn btn-icon btn-color-gray-500 btn-active-color-primary w-35px h-35px ms-n2 me-2 d-flex d-lg-none" id="kt_app_header_menu_toggle">
                    <i class="ki-outline ki-abstract-14 fs-1"></i>
                </div>

                <a href="{{ route('developer.dashboard') }}">
                    <img alt="Logo" src="{{ asset('storage/' . $siteLogo) }}" style="filter: brightness(0%);" class="h-35px theme-light-show" />
                    <img alt="Logo" src="{{ asset('storage/' . $siteLogo) }}" class="h-35px theme-dark-show" />
                </a>

            </div>

            <div class="d-flex align-items-stretch justify-content-end flex-lg-grow-1">

                <div class="d-flex align-items-stretch" id="kt_app_header_menu_wrapper">

                    <div class="app-header-menu app-header-mobile-drawer align-items-stretch me-lg-9" data-kt-drawer="true" data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_menu_wrapper'}">

                        <div dir="rtl" class="menu menu-rounded menu-column menu-lg-row menu-active-bg menu-title-gray-600 menu-state-gray-900 menu-arrow-gray-500 fw-semibold fw-semibold fs-6 align-items-stretch my-5 my-lg-0 px-2 px-lg-0" id="#kt_app_header_menu" data-kt-menu="true">

                            <a href="{{ route('developer.dashboard') }}" class="menu-item menu-lg-down-accordion me-0 me-lg-2 {{ request()->is('developer/dashboard') ? 'here' : '' }}">
                                <span class="menu-link">
                                    <span class="menu-title">الرئيسية</span>
                                    <span class="menu-arrow d-lg-none"></span>
                                </span>
                            </a>

                            <a href="{{ route('developer.projects') }}" class="menu-item menu-lg-down-accordion me-0 me-lg-2 {{ request()->is('developer/projects') ? 'here' : '' }}">
                                <span class="menu-link">
                                    <span class="menu-title">المشاريع</span>
                                    <span class="menu-arrow d-lg-none"></span>
                                </span>
                            </a>

                            <a href="{{ route('developer.orders') }}" class="menu-item menu-lg-down-accordion me-0 me-lg-2 {{ request()->is('developer/orders') ? 'here' : '' }}">
                                <span class="menu-link">
                                    <span class="menu-title">الطلبات</span>
                                    <span class="menu-arrow d-lg-none"></span>
                                    <span class="menu-badge">
                                        <span class="badge badge-dark badge-circle fw-bold fs-7 me-1 ms-0">{{ $ordersCount }}</span>
                                    </span>
                                </span>
                            </a>

                            {{-- <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item show menu-here-bg menu-lg-down-accordion me-0 me-lg-2">


                                <span class="menu-link">
                                    <span class="menu-title">Services</span>
                                    <span class="menu-arrow d-lg-none"></span>
                                </span>


                                <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown p-0 w-100 w-lg-850px">

                                    <div class="menu-state-bg menu-extended overflow-hidden overflow-lg-visible" data-kt-menu-dismiss="true">

                                        <div class="row">

                                            <div class="col-lg-8 mb-3 mb-lg-0 py-3 px-3 py-lg-6 px-lg-6">

                                                <div class="row">

                                                    <div class="col-lg-6 mb-3">

                                                        <div class="menu-item p-0 m-0">

                                                            <a href="index.html" class="menu-link active">
                                                                <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3">
                                                                    <i class="ki-outline ki-element-11 text-primary fs-1"></i>
                                                                </span>
                                                                <span class="d-flex flex-column">
                                                                    <span class="fs-6 fw-bold text-gray-800">Default</span>
                                                                    <span class="fs-7 fw-semibold text-muted">Reports & statistics</span>
                                                                </span>
                                                            </a>

                                                        </div>

                                                    </div>


                                                    <div class="col-lg-6 mb-3">

                                                        <div class="menu-item p-0 m-0">

                                                            <a href="dashboards/ecommerce.html" class="menu-link">
                                                                <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3">
                                                                    <i class="ki-outline ki-basket text-danger fs-1"></i>
                                                                </span>
                                                                <span class="d-flex flex-column">
                                                                    <span class="fs-6 fw-bold text-gray-800">eCommerce</span>
                                                                    <span class="fs-7 fw-semibold text-muted">Sales reports</span>
                                                                </span>
                                                            </a>

                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="separator separator-dashed mx-5 my-5"></div>

                                                <div class="d-flex flex-stack flex-wrap flex-lg-nowrap gap-2 mx-5">
                                                    <div class="d-flex flex-column me-5">
                                                        <div class="fs-6 fw-bold text-gray-800">Landing Page Template</div>
                                                        <div class="fs-7 fw-semibold text-muted">Onpe page landing template with pricing & others</div>
                                                    </div>
                                                    <a href="landing.html" class="btn btn-sm btn-primary fw-bold">Explore</a>
                                                </div>

                                            </div>


                                            <div class="menu-more bg-light col-lg-4 py-3 px-3 py-lg-6 px-lg-6 rounded-end">

                                                <h4 class="fs-6 fs-lg-4 text-gray-800 fw-bold mt-3 mb-3 ms-4">More Dashboards</h4>

                                                <div class="menu-item p-0 m-0">

                                                    <a href="dashboards/podcast.html" class="menu-link py-2">
                                                        <span class="menu-title">Podcast</span>
                                                    </a>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div> --}}

                            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">

                                <span class="menu-link">
                                    <span class="menu-title">المساعدة</span>
                                    <span class="menu-arrow d-lg-none"></span>
                                </span>


                                <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-200px">

                                    <div class="menu-item">

                                        <a class="menu-link" href="https://preview.keenthemes.com/html/metronic/docs/base/utilities" target="_blank" title="Check out over 200 in-house components, plugins and ready for use solutions" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                            <span class="menu-icon">
                                                <i class="ki-outline ki-rocket fs-2"></i>
                                            </span>
                                            <span class="menu-title">Components</span>
                                        </a>

                                    </div>


                                    <div class="menu-item">

                                        <a class="menu-link" href="https://preview.keenthemes.com/html/metronic/docs" target="_blank" title="Check out the complete documentation" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                            <span class="menu-icon">
                                                <i class="ki-outline ki-abstract-26 fs-2"></i>
                                            </span>
                                            <span class="menu-title">Documentation</span>
                                        </a>

                                    </div>


                                    <div class="menu-item">

                                        <a class="menu-link" href="https://preview.keenthemes.com/metronic8/demo44/layout-builder.html" title="Build your layout and export HTML for server side integration" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                            <span class="menu-icon">
                                                <i class="ki-outline ki-switch fs-2"></i>
                                            </span>
                                            <span class="menu-title">Layout Builder</span>
                                        </a>

                                    </div>


                                    <div class="menu-item">

                                        <a class="menu-link" href="https://preview.keenthemes.com/html/metronic/docs/getting-started/changelog" target="_blank">
                                            <span class="menu-icon">
                                                <i class="ki-outline ki-code fs-2"></i>
                                            </span>
                                            <span class="menu-title">Changelog v8.2.1</span>
                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="app-navbar flex-shrink-0">

                    <div class="app-navbar-item">
                        <a href="#" class="btn btn-flex flex-center btn-sm fw-bold btn-dark py-3 w-40px h-40px w-md-auto disabled" disabled data-bs-toggle="modal" data-bs-target="#kt_modal_upgrade_plan">
                            <i class="ki-outline ki-verify d-inline-flex d-md-none fs-2 p-0 m-0"></i>
                            <span class="d-none d-md-inline ps-lg-1">ترقية الخطة</span>
                        </a>
                    </div>


                    {{-- @livewire('developer.components.search') <!-- Search not used --> --}}

                    @livewire('developer.components.notifications') <!-- Notifications -->

                    <div class="app-navbar-item ms-1 ms-lg-4" id="kt_header_user_menu_toggle" dir="rtl">

                        <div class="cursor-pointer symbol symbol-35px symbol-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                            <img class="symbol symbol-35px symbol-md-40px"
                                 src="{{ $authUser->getFilamentAvatarUrl() ? asset($authUser->getFilamentAvatarUrl()) : asset('developer/media/auth/bg8-dark.jpg') }}" alt="user" />
                        </div>

                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">

                            <div class="menu-item px-3">
                                <div class="menu-content d-flex align-items-center px-3">

                                    <div class="symbol symbol-50px ms-5">
                                        <img alt="Logo" src="{{ $authUser->getFilamentAvatarUrl() ? asset($authUser->getFilamentAvatarUrl()) : asset('developer/media/auth/bg8-dark.jpg') }}" />
                                    </div>

                                    <div class="d-flex flex-column">
                                        <div class="fw-bold d-flex align-items-center fs-5">{{ $authUser->username }}</div>
                                        <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{ $authUser->email }}</a>
                                    </div>

                                </div>
                            </div>


                            <div class="separator my-2"></div>


                            {{-- <div class="menu-item px-5">
                                <a href="{{ route('developer.profile') }}" class="menu-link px-5">My Profile</a>
                            </div> --}}

                            <div class="menu-item px-5">
                                <a href="{{ route('developer.edit-profile') }}" class="menu-link px-5">اعدادات الحساب</a>
                            </div>

                            {{-- <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                                <a href="#" class="menu-link px-5">
                                    <span class="menu-title">My Subscription</span>
                                    <span class="menu-arrow"></span>
                                </a>

                                <div class="menu-sub menu-sub-dropdown w-175px py-4">

                                    <div class="menu-item px-3">
                                        <a href="account/referrals.html" class="menu-link px-5">Referrals</a>
                                    </div>


                                    <div class="menu-item px-3">
                                        <a href="account/billing.html" class="menu-link px-5">Billing</a>
                                    </div>


                                    <div class="menu-item px-3">
                                        <a href="account/statements.html" class="menu-link px-5">Payments</a>
                                    </div>


                                    <div class="menu-item px-3">
                                        <a href="account/statements.html" class="menu-link d-flex flex-stack px-5">Statements
                                        <span class="ms-2 lh-0" data-bs-toggle="tooltip" title="View your statements">
                                            <i class="ki-outline ki-information-5 fs-5"></i>
                                        </span></a>
                                    </div>

                                </div>

                            </div> --}}

                            {{-- <div class="menu-item px-5">
                                <a href="account/statements.html" class="menu-link px-5">My Statements</a>
                            </div> --}}


                            <div class="separator my-2"></div>


                            <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                                <a href="#" class="menu-link px-5">
                                    <span class="menu-title position-relative text-left">
                                        الثيم
                                        <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                                            <i class="ki-outline ki-night-day theme-light-show fs-2"></i>
                                            <i class="ki-outline ki-moon theme-dark-show fs-2"></i>
                                        </span>
                                    </span>
                                </a>

                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-50px" data-kt-menu="true" data-kt-element="theme-mode-menu">

                                    <div class="menu-item px-3 my-0">
                                        <a href="#" class="menu-link px-1 py-2" data-kt-element="mode" data-kt-value="light">
                                            <span class="menu-icon m-0" data-kt-element="icon">
                                                <i class="ki-outline ki-night-day fs-2"></i>
                                            </span>
                                            {{-- <span class="menu-title">
                                                Light
                                            </span> --}}
                                        </a>
                                    </div>


                                    <div class="menu-item px-3 my-0">
                                        <a href="#" class="menu-link px-1 py-2" data-kt-element="mode" data-kt-value="dark">
                                            <span class="menu-icon m-0" data-kt-element="icon">
                                                <i class="ki-outline ki-moon fs-2"></i>
                                            </span>
                                            {{-- <span class="menu-title">
                                                Dark
                                            </span> --}}
                                        </a>
                                    </div>


                                    <div class="menu-item px-3 my-0">
                                        <a href="#" class="menu-link px-1 py-2" data-kt-element="mode" data-kt-value="system">
                                            <span class="menu-icon m-0" data-kt-element="icon">
                                                <i class="ki-outline ki-screen fs-2"></i>
                                            </span>
                                            {{-- <span class="menu-title">
                                                System
                                            </span> --}}
                                        </a>
                                    </div>

                                </div>

                            </div>


                            {{-- <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                                <a href="#" class="menu-link px-5">
                                    <span class="menu-title position-relative text-left">
                                        اللغة
                                    <span class="fs-8 rounded bg-light position-absolute translate-middle-y top-50 end-0">
                                    <img class="w-15px h-15px rounded-1 ms-2" src="{{ asset('developer/media/flags/united-states.svg') }}" alt="" /></span></span>
                                </a>

                                <div class="menu-sub menu-sub-dropdown w-175px py-4">

                                    <div class="menu-item px-3">
                                        <a href="account/settings.html" class="menu-link d-flex px-5 active">
                                        <span class="symbol symbol-20px me-4">
                                            <img class="rounded-1" src="{{ asset('developer/media/flags/united-states.svg') }}" alt="" />
                                        </span>English</a>
                                    </div>


                                    <div class="menu-item px-3">
                                        <a href="account/settings.html" class="menu-link d-flex px-5">
                                        <span class="symbol symbol-20px me-4">
                                            <img class="rounded-1" src="{{ asset('developer/media/flags/saudi-arabia.svg') }}" alt="" />
                                        </span>عربي</a>
                                    </div>

                                </div>

                            </div> --}}

                            <div class="menu-item px-5">
                                <a class="menu-link px-5" wire:click="logout">تسجيل خروج</a>
                            </div>

                        </div>


                    </div>


                    {{-- <div class="app-navbar-item d-flex align-items-center d-lg-none ms-1 me-n2">
                        <a href="#" class="btn btn-icon btn-color-gray-500 btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
                            <i class="ki-outline ki-burger-menu-2 fs-1"></i>
                        </a>
                    </div> --}}

                </div>

            </div>

        </div>

    </div>
</div>
