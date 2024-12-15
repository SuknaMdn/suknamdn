{{-- <div> --}}
    <div class="app-navbar-item ms-1 ms-lg-4">

        <div class="btn btn-icon btn-custom w-35px h-35px w-md-40px h-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
            <i class="ki-outline ki-calendar fs-1"></i>
        </div>

        <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true" id="kt_menu_notifications">

            <div class="d-flex flex-column bgi-no-repeat rounded-top" style="background:#000000">

                <h3 class="text-white fw-semibold px-9 mt-10 mb-6">Notifications
                <span class="fs-8 opacity-75 ps-3">{{ auth()->user()->unreadNotifications->count() }} unread</span></h3>


                <ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-semibold px-9">

                    <li class="nav-item">
                        <a class="nav-link text-white opacity-75 opacity-state-100 pb-4 active" data-bs-toggle="tab" href="#kt_topbar_notifications_2">Updates</a>
                    </li>
                </ul>

            </div>


            <div class="tab-content">


                <div class="tab-pane fade show active" id="kt_topbar_notifications_2" role="tabpanel">

                    <div class="scroll-y mh-325px my-5 px-8">

                        <div class="d-flex flex-stack py-4">

                            <div class="d-flex align-items-center">

                                <div class="symbol symbol-35px me-4">
                                    <span class="symbol-label bg-light-primary">
                                        <i class="ki-outline ki-abstract-28 fs-2 text-primary"></i>
                                    </span>
                                </div>


                                <div class="mb-0 me-2">
                                    <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">Project Alice</a>
                                    <div class="text-gray-500 fs-7">Phase 1 development</div>
                                </div>

                            </div>


                            <span class="badge badge-light fs-8">1 hr</span>
                            <button wire:click="markAsRead" class="btn btn-sm btn-light fw-bold btn-active-light-primary ms-2">Mark as read</button>
                        </div>

                        <div class="d-flex flex-stack py-4">

                            <div class="d-flex align-items-center">

                                <div class="symbol symbol-35px me-4">
                                    <span class="symbol-label bg-light-danger">
                                        <i class="ki-outline ki-information fs-2 text-danger"></i>
                                    </span>
                                </div>


                                <div class="mb-0 me-2">
                                    <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">HR Confidential</a>
                                    <div class="text-gray-500 fs-7">Confidential staff documents</div>
                                </div>

                            </div>


                            <span class="badge badge-light fs-8">2 hrs</span>

                        </div>

                        <div class="d-flex flex-stack py-4">

                            <div class="d-flex align-items-center">

                                <div class="symbol symbol-35px me-4">
                                    <span class="symbol-label bg-light-warning">
                                        <i class="ki-outline ki-briefcase fs-2 text-warning"></i>
                                    </span>
                                </div>


                                <div class="mb-0 me-2">
                                    <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">Company HR</a>
                                    <div class="text-gray-500 fs-7">Corporeate staff profiles</div>
                                </div>

                            </div>


                            <span class="badge badge-light fs-8">5 hrs</span>

                        </div>

                        <div class="d-flex flex-stack py-4">

                            <div class="d-flex align-items-center">

                                <div class="symbol symbol-35px me-4">
                                    <span class="symbol-label bg-light-success">
                                        <i class="ki-outline ki-abstract-12 fs-2 text-success"></i>
                                    </span>
                                </div>


                                <div class="mb-0 me-2">
                                    <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">Project Redux</a>
                                    <div class="text-gray-500 fs-7">New frontend admin theme</div>
                                </div>

                            </div>


                            <span class="badge badge-light fs-8">2 days</span>

                        </div>

                        <div class="d-flex flex-stack py-4">

                            <div class="d-flex align-items-center">

                                <div class="symbol symbol-35px me-4">
                                    <span class="symbol-label bg-light-primary">
                                        <i class="ki-outline ki-colors-square fs-2 text-primary"></i>
                                    </span>
                                </div>


                                <div class="mb-0 me-2">
                                    <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">Project Breafing</a>
                                    <div class="text-gray-500 fs-7">Product launch status update</div>
                                </div>

                            </div>


                            <span class="badge badge-light fs-8">21 Jan</span>

                        </div>

                        <div class="d-flex flex-stack py-4">

                            <div class="d-flex align-items-center">

                                <div class="symbol symbol-35px me-4">
                                    <span class="symbol-label bg-light-info">
                                        <i class="ki-outline ki-picture fs-2 text-info"></i>
                                    </span>
                                </div>


                                <div class="mb-0 me-2">
                                    <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">Banner Assets</a>
                                    <div class="text-gray-500 fs-7">Collection of banner images</div>
                                </div>

                            </div>


                            <span class="badge badge-light fs-8">21 Jan</span>

                        </div>

                        <div class="d-flex flex-stack py-4">

                            <div class="d-flex align-items-center">

                                <div class="symbol symbol-35px me-4">
                                    <span class="symbol-label bg-light-warning">
                                        <i class="ki-outline ki-color-swatch fs-2 text-warning"></i>
                                    </span>
                                </div>


                                <div class="mb-0 me-2">
                                    <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">Icon Assets</a>
                                    <div class="text-gray-500 fs-7">Collection of SVG icons</div>
                                </div>

                            </div>


                            <span class="badge badge-light fs-8">20 March</span>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

{{-- </div> --}}
