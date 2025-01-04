<div>
    <div dir="rtl">
        <div id="kt_app_toolbar" class="app-toolbar pb-7">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <div class="page-title d-flex flex-column justify-content-center gap-1 ms-3">
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7">

                        <li class="breadcrumb-item text-gray-700 fw-bold lh-1 mx-n1">
                            <a href="{{ route('developer.dashboard') }}" class="text-hover-primary">
                                <i class="ki-outline ki-home text-gray-700 fs-6"></i>
                            </a>
                        </li>

                        <li class="breadcrumb-item me-2">
                            <i class="ki-outline ki-left fs-7 text-gray-700"></i>
                        </li>

                        <li class="breadcrumb-item text-gray-700 fw-bold lh-1 mx-n1">لوحة التحكم</li>
                        <li class="breadcrumb-item">
                            <i class="ki-outline ki-left fs-7 text-gray-700"> </i>
                        </li>
                        <li class="breadcrumb-item text-gray-500 mx-n1">العملاء</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-7" dir="rtl">

        <!--begin::Search-->
        <div class="col-lg-6 col-xl-4">
            <!--begin::Contacts-->
            <div class="card card-flush" id="kt_contacts_list">
                <div class="card-header pt-7" id="kt_chat_contacts_header">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2>المجموعات</h2>
                        <span class="spinner-border spinner-border-sm me-5" wire:loading wire:target="selectedGroup"></span>
                    </div>
                    <div class="card-toolbar">
                        <!--begin::Tabs-->
                        <ul class="nav" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a wire:click="$set('selectedGroup', 'all')" class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light fw-bold px-4 me-1 {{ $selectedGroup === 'all' ? 'text-active-primary active' : '' }}">
                                    كل العملاء
                                    <div class="badge badge-light-primary">{{ $groups['all'] }}</div>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a wire:click="$set('selectedGroup', 'buyers')" class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light fw-bold px-4 me-1 {{ $selectedGroup === 'buyers' ? 'text-active-primary active' : '' }}">قام بالشراء
                                    <div class="badge badge-light-primary">{{ $groups['buyers'] }}</div>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a wire:click="$set('selectedGroup', 'active')" class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light fw-bold px-4 me-1 {{ $selectedGroup === 'active' ? 'text-active-primary active' : '' }}">متفاعل
                                    <div class="badge badge-light-primary">{{ $groups['active'] }}</div>
                                </a>
                            </li>
                        </ul>
                        <!--end::Tabs-->
                    </div>
                    <!--end::Card title-->
                </div>

                <!--begin::Card header-->
                <div class="card-header pt-7" id="kt_contacts_list_header">
                    <!--begin::Form-->
                    <form class="d-flex align-items-center position-relative w-100 m-0" autocomplete="off">
                        <!--begin::Icon-->
                        <i class="ki-outline ki-magnifier fs-3 text-gray-500 position-absolute top-50 me-3 translate-middle-y"></i>
                        <!--end::Icon-->
                        <!--begin::Input-->
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-solid pe-10" placeholder="بحث" />
                        <!--end::Input-->
                        <span class="spinner-border spinner-border-sm position-absolute top-40 ms-5" wire:loading wire:target="search" style="left: 10px;"></span>
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-5" id="kt_contacts_list_body">
                    <!--begin::List-->
                    <div class="scroll-y me-n5 pe-5 h-300px h-xl-auto" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_header, #kt_toolbar, #kt_footer, #kt_contacts_list_header" data-kt-scroll-wrappers="#kt_content, #kt_contacts_list_body" data-kt-scroll-stretch="#kt_contacts_list, #kt_contacts_main" data-kt-scroll-offset="5px">
                        @foreach($users as $user)
                            <!--begin::User-->
                            <div class="d-flex flex-stack py-4" wire:key="'{{ $user->id }}'" style="cursor: pointer">
                                <!--begin::Details-->
                                <div class="d-flex align-items-center text-right" wire:click="loadUser('{{ $user->id }}')" >
                                    <div class="symbol symbol-40px symbol-circle">
                                        <span class="symbol-label bg-light-warning text-warning fs-6 fw-bolder">
                                            {{ $user->name == '' ? Str::substr($user->name, 0, 1) : Str::substr($user->username,0,1) }}
                                        </span>
                                    </div>
                                    <div class="me-4">
                                        <a class="fs-6 fw-bold text-gray-900 text-hover-primary mb-2">{{ $user->name == '' ? $user->name : $user->username }}</a>
                                        <div class="fw-semibold fs-7 text-muted">{{ $user->email }}</div>
                                    </div>
                                    <!--end::Details-->
                                </div>
                                <div class="d-flex align-items-center text-right" dir="rtl">
                                    <div class="ms-4">
                                        <div class="fw-semibold fs-7 text-muted">{{ $user->phone }}</div>
                                    </div>
                                    <!--end::Details-->
                                </div>
                                <!--end::Details-->
                            </div>
                        @endforeach
                    </div>
                    <!--end::List-->
                </div>
                <!--end::Card body-->
                {{ $users->links() }}
            </div>
            <!--end::Contacts-->
        </div>
        <!--end::Search-->
        <!--begin::Content-->
        <div class="col-xl-8">
            <!--begin::Contacts-->
            @if($selectedUserModel)
                <div
                class="card card-flush h-lg-100"
                id="kt_contacts_main"
                wire:loading.class="disabled">

                    <!--begin::Card header-->
                    <div class="card-header pt-7" id="kt_chat_contacts_header">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <i class="ki-outline ki-badge fs-1 ms-2"></i>
                            <h2>{{ $selectedUserModel->name }}</h2>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-5">
                        <!--begin::Profile-->
                        <div class="d-flex gap-7 align-items-center">
                            <div class="d-flex flex-column gap-2">
                                <h3 class="mb-0">{{ $selectedUserModel->username }}</h3>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ki-outline ki-sms fs-2"></i>
                                    <a href="#" class="text-muted text-hover-primary">{{ $selectedUserModel->email }}</a>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ki-outline ki-phone fs-2"></i>
                                    <a href="#" class="text-muted text-hover-primary">{{ $selectedUserModel->phone }}</a>
                                </div>
                            </div>
                            <!--end::Contact details-->
                        </div>
                        <!--end::Profile-->
                        <!--begin:::Tabs-->
                        <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x fs-6 fw-semibold mt-6 mb-8 gap-2">
                            <!--begin:::Tab item-->
                            <li class="nav-item">
                                <a class="nav-link text-active-primary d-flex align-items-center pb-4 me-0 ms-3 active" data-bs-toggle="tab" href="#kt_contact_view_general">
                                <i class="ki-outline ki-home fs-4 me-1"></i>البيانات الاساسية</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary d-flex align-items-center me-0 ms-3 pb-4" data-bs-toggle="tab" href="#kt_contact_view_activity">
                                <i class="ki-outline ki-save-2 fs-4 me-1"></i>النشاطات</a>
                            </li>
                            <!--end:::Tab item-->
                        </ul>
                        <!--end:::Tabs-->
                        <!--begin::Tab content-->
                        <div class="tab-content" id="">
                            <!--begin:::Tab pane-->
                            <div class="tab-pane fade show active" id="kt_contact_view_general" role="tabpanel">
                                <!--begin::Additional details-->
                                <div class="d-flex flex-column gap-5 mt-7">
                                    <!--begin::Company name-->
                                    <div class="d-flex flex-column gap-1">
                                        <div class="fw-bold text-muted">Company Name</div>
                                        <div class="fw-bold fs-5">Keenthemes Inc</div>
                                    </div>
                                    <!--end::Company name-->
                                    <!--begin::City-->
                                    <div class="d-flex flex-column gap-1">
                                        <div class="fw-bold text-muted">City</div>
                                        <div class="fw-bold fs-5">Melbourne</div>
                                    </div>
                                    <!--end::City-->
                                    <!--begin::Country-->
                                    <div class="d-flex flex-column gap-1">
                                        <div class="fw-bold text-muted">Country</div>
                                        <div class="fw-bold fs-5">Australia</div>
                                    </div>
                                    <!--end::Country-->
                                    <!--begin::Notes-->
                                    <div class="d-flex flex-column gap-1">
                                        <div class="fw-bold text-muted">Notes</div>
                                        <p>Emma Smith joined the team on September 2019 as a junior associate. She soon showcased her expertise and experience in knowledge and skill in the field, which was very valuable to the company. She was promptly promoted to senior associate on July 2020.
                                        <br />
                                        <br />Emma Smith now heads a team of 5 associates and leads the company's sales growth by 7%.</p>
                                    </div>
                                    <!--end::Notes-->
                                </div>
                                <!--end::Additional details-->
                            </div>
                            <!--end:::Tab pane-->

                            <div class="tab-pane fade" id="kt_contact_view_activity" role="tabpanel">
                                <!--begin::Timeline-->
                                <div class="timeline-label">
                                    <!--begin::Item-->
                                    <div class="timeline-item">
                                        <!--begin::Label-->
                                        <div class="timeline-label fw-bold text-gray-800 fs-6">08:42</div>
                                        <!--end::Label-->
                                        <!--begin::Badge-->
                                        <div class="timeline-badge">
                                            <i class="ki-outline ki-abstract-8 text-warning fs-1"></i>
                                        </div>
                                        <!--end::Badge-->
                                        <!--begin::Text-->
                                        <div class="fw-mormal timeline-content text-muted ps-3">Outlines keep you honest. And keep structure</div>
                                        <!--end::Text-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="timeline-item">
                                        <!--begin::Label-->
                                        <div class="timeline-label fw-bold text-gray-800 fs-6">10:00</div>
                                        <!--end::Label-->
                                        <!--begin::Badge-->
                                        <div class="timeline-badge">
                                            <i class="ki-outline ki-abstract-8 text-success fs-1"></i>
                                        </div>
                                        <!--end::Badge-->
                                        <!--begin::Content-->
                                        <div class="timeline-content d-flex">
                                            <span class="fw-bold text-gray-800 ps-3">AEOL meeting</span>
                                        </div>
                                        <!--end::Content-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="timeline-item">
                                        <!--begin::Label-->
                                        <div class="timeline-label fw-bold text-gray-800 fs-6">14:37</div>
                                        <!--end::Label-->
                                        <!--begin::Badge-->
                                        <div class="timeline-badge">
                                            <i class="ki-outline ki-abstract-8 text-danger fs-1"></i>
                                        </div>
                                        <!--end::Badge-->
                                        <!--begin::Desc-->
                                        <div class="timeline-content fw-bold text-gray-800 ps-3">Make deposit
                                        <a href="#" class="text-primary">USD 700</a>. to ESL</div>
                                        <!--end::Desc-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="timeline-item">
                                        <!--begin::Label-->
                                        <div class="timeline-label fw-bold text-gray-800 fs-6">16:50</div>
                                        <!--end::Label-->
                                        <!--begin::Badge-->
                                        <div class="timeline-badge">
                                            <i class="ki-outline ki-abstract-8 text-primary fs-1"></i>
                                        </div>
                                        <!--end::Badge-->
                                        <!--begin::Text-->
                                        <div class="timeline-content fw-mormal text-muted ps-3">Indulging in poorly driving and keep structure keep great</div>
                                        <!--end::Text-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="timeline-item">
                                        <!--begin::Label-->
                                        <div class="timeline-label fw-bold text-gray-800 fs-6">21:03</div>
                                        <!--end::Label-->
                                        <!--begin::Badge-->
                                        <div class="timeline-badge">
                                            <i class="ki-outline ki-abstract-8 text-danger fs-1"></i>
                                        </div>
                                        <!--end::Badge-->
                                        <!--begin::Desc-->
                                        <div class="timeline-content fw-semibold text-gray-800 ps-3">New order placed
                                        <a href="#" class="text-primary">#XF-2356</a>.</div>
                                        <!--end::Desc-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="timeline-item">
                                        <!--begin::Label-->
                                        <div class="timeline-label fw-bold text-gray-800 fs-6">16:50</div>
                                        <!--end::Label-->
                                        <!--begin::Badge-->
                                        <div class="timeline-badge">
                                            <i class="ki-outline ki-abstract-8 text-primary fs-1"></i>
                                        </div>
                                        <!--end::Badge-->
                                        <!--begin::Text-->
                                        <div class="timeline-content fw-mormal text-muted ps-3">Indulging in poorly driving and keep structure keep great</div>
                                        <!--end::Text-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="timeline-item">
                                        <!--begin::Label-->
                                        <div class="timeline-label fw-bold text-gray-800 fs-6">21:03</div>
                                        <!--end::Label-->
                                        <!--begin::Badge-->
                                        <div class="timeline-badge">
                                            <i class="ki-outline ki-abstract-8 text-danger fs-1"></i>
                                        </div>
                                        <!--end::Badge-->
                                        <!--begin::Desc-->
                                        <div class="timeline-content fw-semibold text-gray-800 ps-3">New order placed
                                        <a href="#" class="text-primary">#XF-2356</a>.</div>
                                        <!--end::Desc-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="timeline-item">
                                        <!--begin::Label-->
                                        <div class="timeline-label fw-bold text-gray-800 fs-6">10:30</div>
                                        <!--end::Label-->
                                        <!--begin::Badge-->
                                        <div class="timeline-badge">
                                            <i class="ki-outline ki-abstract-8 text-success fs-1"></i>
                                        </div>
                                        <!--end::Badge-->
                                        <!--begin::Text-->
                                        <div class="timeline-content fw-mormal text-muted ps-3">Finance KPI Mobile app launch preparion meeting</div>
                                        <!--end::Text-->
                                    </div>
                                    <!--end::Item-->
                                </div>
                                <!--end::Timeline-->
                            </div>
                            <!--end:::Tab pane-->
                        </div>
                        <!--end::Tab content-->
                    </div>
                    <!--end::Card body-->
                </div>
            @else
                <div class="text-center card card-flush h-lg-100">
                    <div class="card-body pt-5">
                        <img src="{{ asset('developer/media/auth/membership.png') }}" class="m-auto mt-4" width="200px" alt="">
                        <div class="text-center m-auto">
                            <span class="spinner-border spinner-border-sm" wire:loading wire:target="loadUser"></span>
                        </div>
                        <div class="text-gray-800 text-center">
                            اختر مستخدمًا لعرض التفاصيل
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <!--end::Content-->
    </div>
</div>
