<div>
    @livewire('developer.components.order-details-modal')

    <div class="overflow-auto pb-5">
        <div class="d-flex align-items-center border border-dashed border-gray-300 rounded min-w-700px p-7">
            <!--begin::Item-->
            @if($project->images)
            @foreach($project->images as $image)
                <div class="overlay me-10">
                    <div class="overlay-wrapper">
                        <img alt="img" class="rounded w-150px" src="{{ asset('storage/' . $image) }}">
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
    <!--begin::Navbar-->
    <div class="card mb-6 mb-xl-9">
        <div class="card-body pt-9 pb-0">
            <!--begin::Details-->
            <div class="d-flex flex-wrap flex-sm-nowrap mb-6">
                <!--begin::Image-->
                <div class="d-flex flex-center flex-shrink-0 bg-light rounded w-100px h-100px w-lg-150px h-lg-150px me-7 mb-4 p-3">
                    <img class="w-100" src="{{ asset('storage/' . $project->qr_code) }}" alt="image" />
                </div>
                <!--end::Image-->
                <div class="flex-grow-1">

                    <!--begin::Head-->
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                        <!--begin::Details-->
                        <div class="d-flex flex-column">
                            <!--begin::Status-->
                            <div class="d-flex align-items-center mb-1">
                                <a href="#" class="text-gray-800 text-hover-primary fs-2 fw-bold me-3">{{ $project->title }}</a>
                                <span class="badge badge-light-{{ $project->is_active ? 'success' : 'danger' }} me-auto">{{ $project->is_active ? 'Active' : 'Not Action' }}</span>
                            </div>
                            <!--end::Status-->
                            <!--begin::Description-->
                            <div class="d-flex flex-wrap fw-semibold mb-4 fs-5 text-gray-500" style="max-width: 500px">{!! $project->description !!}</div>
                            <!--end::Description-->
                        </div>
                        <!--end::Details-->
                        <!--begin::Actions-->
                        <div class="d-flex mb-4">
                            {{-- <a href="#" class="btn btn-sm btn-light-dark me-3" data-bs-toggle="modal" data-bs-target="#kt_modal_new_target">Add Target</a> --}}
                            <!--begin::Menu-->
                            <div class="me-0">
                                <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    <i class="ki-solid ki-dots-horizontal fs-2x"></i>
                                </button>
                                <div wire:loading wire:target="inactiveProject, activeProject" class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></div>

                                <!--begin::Menu 3-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3" data-kt-menu="true">
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3">تعديل</a>
                                    </div>
                                    <div class="menu-item px-3 my-1">
                                        @if ($project->is_active)
                                            <a wire:click="inactiveProject" class="menu-link text-danger px-3">غير نشط</a>
                                        @else
                                            <a wire:click="activeProject" class="menu-link text-primary px-3">نشط</a>
                                        @endif
                                        @endif
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu 3-->
                            </div>
                            <!--end::Menu-->
                        </div>
                        <!--end::Actions-->
                    </div>
                    <!--end::Head-->
                    <!--begin::Info-->
                    <div class="d-flex flex-wrap justify-content-start">
                        <!--begin::Stats-->
                        <div class="d-flex flex-wrap">
                            <!--begin::Stat-->
                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <!--begin::Number-->
                                <div class="d-flex align-items-center">
                                    <div class="fs-4 fw-bold">{{ $project->created_at->format('d M, Y') }}</div>
                                </div>
                                <!--end::Number-->
                                <!--begin::Label-->
                                <div class="fw-semibold fs-6 text-gray-500">تم التعديل في</div>
                                <!--end::Label-->
                            </div>
                            <!--end::Stat-->
                            <!--begin::Stat-->
                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <!--begin::Number-->
                                <div class="d-flex align-items-center">
                                    <i class="ki-outline ki-sort fs-3 text-dark me-2"></i>
                                    <span class="me-2">الكل</span> <div class="fs-4 fw-bold me-2" data-kt-countup="true" data-kt-countup-value="{{ $project->units->count() }}">0</div>
                                </div>
                                <!--end::Number-->
                                <!--begin::Label-->
                                <div class="fw-semibold fs-6 text-gray-500">الوحدات <span class="badge badge-light-success fw-bold px-4 py-1">Sold <div class="ms-2" data-kt-countup="true" data-kt-countup-value="{{ $project->units->where('case', 1)->count() }}">0</div> </span></div>
                                <!--end::Label-->
                            </div>
                            <!--begin::Stat-->
                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <!--begin::Number-->
                                <div class="d-flex align-items-center">
                                    <i class="ki-outline ki-user fs-3 text-dark me-2"></i>
                                    <div class="fs-4 fw-bold">{{ $project->developer->name }}</div>
                                </div>
                                <!--end::Number-->
                                <!--begin::Label-->
                                <div class="fw-semibold fs-6 text-gray-500">المطور</div>
                                <!--end::Label-->
                            </div>
                            <!--end::Stat-->
                        </div>

                    </div>
                    <!--end::Info-->
                </div>
                <!--end::Wrapper-->
            </div>
        </div>
    </div>
    <!--end::Navbar-->

    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Col-->
        <div class="col-xl-4">
            <!--begin::List widget 11-->
            <div class="card card-flush h-xl-100">
                <!--begin::Header-->
                <div class="card-header pt-7 mb-3">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">طلبات المشروع</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-6">كل {{ $orders->count() }} الطلبات</span>
                    </h3>
                    <!--end::Title-->
                    <!--begin::Toolbar-->
                    <div class="card-toolbar">
                        <a href="{{ route('developer.orders', ['filterProject[0]' => $project->id]) }}" class="btn btn-sm btn-light" data-bs-toggle='tooltip' data-bs-dismiss='click' data-bs-custom-class="tooltip-inverse" title="show in new page">عرض كل الطلبات</a>
                    </div>
                    <!--end::Toolbar-->
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body pt-4">

                    @foreach ($orders as $order)
                    <div class="d-flex flex-stack">
                        <!--begin::Section-->
                        <div class="d-flex align-items-center me-5">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-40px me-4">
                                <span class="symbol-label">
                                    <i class="ki-outline ki-flag text-gray-600 fs-1"></i>
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Content-->
                            <div class="me-5">
                                <!--begin::Title-->
                                <a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">{{ $order->user->username }}</a>
                                <!--end::Title-->
                                <!--begin::Desc-->
                                <span class="text-gray-500 fw-semibold fs-7 d-block text-start ps-0">{{ $order->user->phone }}</span>
                                <!--end::Desc-->
                            </div>
                            <div class="me-5">
                                <!--begin::Title-->
                                <a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">{{ $order->unit->title }}</a>
                                <!--end::Title-->
                                <!--begin::Desc-->
                                <span class="text-gray-500 fw-semibold fs-7 d-block text-start ps-0">SAR {{ $order->unit->total_amount }}</span>
                                <!--end::Desc-->
                            </div>

                            <!--end::Content-->
                        </div>
                        <!--end::Section-->
                        <!--begin::Wrapper-->
                        <div class="text-gray-500 fw-bold fs-7 text-end">
                            <!--begin::Number-->
                            <span class="text-gray-800 fw-bold fs-6 d-block">
                                @if ($order->status == 'pending')
                                    <span class="badge badge-light-warning">قيد الانتظار</span>
                                @elseif ($order->status == 'processing')
                                    <span class="badge badge-light-primary">قيد المعالجة</span>
                                @elseif ($order->status == 'confirmed')
                                    <span class="badge badge-light-success">مؤكد</span>
                                @elseif ($order->status == 'canceled')
                                    <span class="badge badge-light-danger">ملغي</span>
                                @endif
                                @endif
                            </span>
                            <!--end::Number-->
                        </div>
                        <!--end::Wrapper-->
                        <a href="#" data-bs-toggle="modal" data-bs-target="#orderDetailsModal" wire:click="openOrderDetails({{ $order->id }})"><i class="ki-outline ki-eye fs-3 text-gray-900"></i></a>
                    </div>
                    <div class="separator separator-dashed my-5"></div>
                    @endforeach
                </div>
                <!--end::Body-->
            </div>
            <!--end::List widget 11-->
        </div>
        <div class="col-xl-8">
            <!--begin::Table widget 8-->
            <div class="card h-xl-100">
                <!--begin::Header-->
                <div class="card-header position-relative py-0 border-bottom-2">
                    <!--begin::Nav-->
                    <ul class="nav nav-stretch nav-pills nav-pills-custom d-flex mt-3">
                        @foreach ($buildingNumbers as $buildingNumber)
                        <li class="nav-item p-0 ms-0 me-8">
                            <!--begin::Nav link-->
                            <a class="nav-link btn btn-color-muted px-0 {{ $loop->first ? 'show active' : '' }}" data-bs-toggle="tab" href="#kt_table_{{$buildingNumber}}">
                                <!--begin::Title-->
                                <span class="nav-text fw-semibold fs-4 mb-3">{{ $buildingNumber }}</span>
                                <!--end::Title-->
                                <!--begin::Bullet-->
                                <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-n100 bg-primary rounded"></span>
                                <!--end::Bullet-->
                            </a>
                            <!--end::Nav link-->
                        </li>
                        @endforeach
                    </ul>
                    <!--end::Nav-->
                    <!--begin::Toolbar-->
                    <div class="card-toolbar">
                        <a href="#" class="btn btn-sm btn-light me-2" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">اضافة وحدة</a>
                    </div>
                    <!--end::Toolbar-->
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <!--begin::Tab Content (ishlamayabdi)-->
                    <div class="tab-content mb-2">
                        @foreach($buildingNumbers as $buildingNumber)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="kt_table_{{$buildingNumber}}">
                            <!--begin::Table container-->
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle">
                                    <!--begin::Table head-->
                                    <thead>
                                        <tr>
                                            <th class="min-w-150px p-0"></th>
                                            <th class="min-w-200px p-0"></th>
                                            <th class="min-w-100px p-0"></th>
                                            <th class="min-w-80px p-0"></th>
                                        </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody>
                                        @forelse ($units as $unit)
                                        @if($unit->building_number == $buildingNumber)
                                        <tr>
                                            <td class="fs-6 fw-bold text-gray-800">{{ $unit->title }}</td>
                                            <td class="fs-6 fw-bold text-gray-500">الحالة:
                                                <span class="text-gray-800">
                                                    @if ($unit->case == 1)
                                                        <span class="badge badge-light-success">مباعة</span>
                                                    @else
                                                        <span class="badge badge-light-danger">غير مباعة</span>
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="fs-6 fw-bold text-gray-500">السعر:
                                                <span class="text-gray-800">SAR {{ $unit->unit_price }}</span>
                                                <span class="text-gray-800"><span class="text-info">+</span> <span class="badge badge-light-primary">{{ $unit->property_tax }} %</span></span>
                                                <span class="text-gray-800">= SAR {{ $unit->total_amount }}</span>
                                            </td>
                                            <td class="pe-0 text-end">
                                                <button class="btn btn-sm btn-light">عرض</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-light rounded text-gray-600 fs-8 fw-bold px-3 py-2" colspan="4">
                                                <div class="d-flex flex-column">
                                                    <span class="text-gray-900"><span class="text-dark fw-bold">المبني</span> : {{ $unit->building_number }} - {{ $unit->unit_number }} <span class="text-dark fw-bold">الطابق: {{ $unit->floor }}</span></span>
                                                    <span class="text-gray-900"><span class="text-dark fw-bold">نوع الوحدة</span> : {{ $unit->unit_type }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">لا يوجد وحدات</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::Table container-->
                        </div>
                        @endforeach
                    </div>
                    <!--end::Tab Content-->
                    <!--begin::Action-->
                    <div class="float-end">
                        {{-- <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">Call Sick for Today</a> --}}
                    </div>
                    <!--end::Action-->
                </div>
                <!--end: Card Body-->
            </div>
            <!--end::Table widget 8-->
        </div>
        <!--end::Col-->
    </div>

</div>

