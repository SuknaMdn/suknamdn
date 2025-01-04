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
                        <li class="breadcrumb-item text-gray-500 mx-n1">كل الطلبات</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @livewire('developer.dashboard.orders-by-month')
</div>
