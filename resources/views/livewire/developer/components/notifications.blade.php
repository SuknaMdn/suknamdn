{{-- <div> --}}
    <div class="app-navbar-item ms-1 ms-lg-4">

        <div class="btn btn-icon btn-custom w-35px h-35px w-md-40px h-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
            <i class="ki-outline ki-calendar fs-1"></i>
        </div>

        <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true" id="kt_menu_notifications">

            <div class="d-flex flex-column bgi-no-repeat rounded-top text-right" style="background:#000000" dir="rtl">

                <h3 class="text-white fw-semibold px-9 mt-10 mb-6">الاشعارات <span class="fs-8 opacity-75 ps-3">{{ auth()->user()->unreadNotifications->count() }} غير مقروء</span> <button class="btn btn-sm" wire:click="markAsRead"><i class="ki-outline ki-eye fs-1"></i></button> </h3>

                <ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-semibold px-9">
                    <li class="nav-item">
                        <a class="nav-link text-white opacity-75 opacity-state-100 pb-4 active" data-bs-toggle="tab" href="#kt_topbar_notifications_2">اخر الاحداث</a>
                    </li>
                </ul>

            </div>


            <div class="tab-content" dir="rtl">

                <div class="tab-pane fade show active" id="kt_topbar_notifications_2" role="tabpanel">

                    <div class="scroll-y mh-325px my-5 px-8 pb-6">

                        @forelse ($notifications as $notification)

                            <div class="py-4 text-right">

                                <div class="d-flex align-items-start">

                                    <div class="symbol symbol-35px me-4">
                                        <span class="symbol-label bg-light-primary">
                                            <i class="ki-outline {{ $notification->data['icon'] }} fs-2 text-primary"></i>
                                        </span>
                                    </div>

                                    <div class="mb-0 d-flex flex-column me-2">
                                        <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">{{$notification->data['title']}}</a>
                                        <div class="text-gray-500 fs-7">{{ $notification->data['description'] }}</div>
                                    </div>

                                </div>
                                <span class="badge badge-light fs-8 float-start">{{  $notification->created_at->format('m-d / h A') }}</span>
                            </div>

                        @empty
                            <div>
                                <span class="text-center py-10">
                                    <img src="{{ asset('developer/media/illustrations/unitedpalms-1/5-dark.png') }}" alt="">
                                </span>
                            </div>
                        @endforelse


                    </div>

                </div>

            </div>

        </div>
    </div>

{{-- </div> --}}
