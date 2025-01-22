<div>
    <!-- Order Details Modal -->
    @livewire('developer.components.order-details-modal')

    <!--begin::Table widget 8-->
    <div class="card h-xl-100" dir="rtl">
        <!--begin::Header-->
        <div class="card-header position-relative border-bottom-2 ps-3">
            <h3 class="card-title">الطلبات</h3>
            <!--begin::Actions-->
            <div class="card-toolbar">
                <div class="d-flex justify-content-between">

                    <span class="spinner-border spinner-border-sm ms-4 m-auto" wire:loading wire:target="filterPaymentPlan, filterStatus, filterProject, searchTerm,dateRange"></span>

                    <div class="row g-3 align-items-center" wire:ignore>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                            <div class="d-flex align-items-center position-relative">
                                <i class="ki-outline ki-magnifier fs-3 position-absolute me-5"></i>
                                <input
                                    wire:model.live.debounce.300ms="searchTerm"
                                    type="text"
                                    class="form-control form-control-solid pe-13 rounded"
                                    style="height: 42px !important;"
                                    placeholder="ابحث ..."
                                />
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4 col-lg-2" dir="ltr">
                            <select
                                wire:model.live="filterStatus"
                                data-control="select2"
                                class="form-select form-select-solid"
                                multiple="multiple"
                                id="status-filter">
                                <option value="" disabled selected hidden>اختر الحالة</option>
                                @foreach($statusOptions as $status)
                                    <option value="{{ $status }}">{{ ucfirst(__("front.$status")) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4 col-lg-2" dir="ltr" wire:ignore>
                            <select
                                wire:model.live="filterPaymentPlan"
                                data-control="select2"
                                class="form-select form-select-solid"
                                aria-placeholder="Select Payment Plan"
                                multiple="multiple"
                                id="payment-plan-filter">
                                <option value="" disabled selected hidden>اختر خطة الدفع</option>
                                @foreach($paymentPlanOptions as $plan)
                                    <option value="{{ $plan }}">{{ __("front.$plan") }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4 col-lg-2" dir="ltr" wire:ignore>
                            <select
                                wire:model.live="filterProject"
                                data-control="select2"
                                class="form-select form-select-solid"
                                aria-placeholder="Select Project"
                                multiple="multiple"
                                id="project-filter">
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                            <div class="input-group" dir="ltr">
                                <input class="form-control form-control-solid rounded rounded-end-0"
                                placeholder="اختر التاريخ"
                                id="kt_ecommerce_sales_flatpickr"
                                wire:model.live="dateRange"
                                />
                                <button class="btn btn-icon btn-light" id="kt_ecommerce_sales_flatpickr_clear">
                                    <i class="ki-outline ki-cross fs-2"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--begin::Body-->
        <div class="card-body">

            <div class="tab-content mb-2">
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table align-middle">
                        <!--begin::Table head-->
                        <thead>
                            <tr>
                                <th class="min-w-150px p-0 text-muted">الوحدة</th>
                                <th class="min-w-200px p-0 text-muted">العميل</th>
                                <th class="min-w-100px p-0 text-muted">الحالة</th>
                                <th class="min-w-200px p-0 text-muted">ملاحظة</th>
                                <th class="min-w-80px p-0 text-muted">خطة الدفع</th>
                                <th class="min-w-80px p-0 text-muted">تم الإنشاء في</th>
                            </tr>
                        </thead>
                        <!--end::Table head-->
                        <tbody>
                            @forelse ($orders as $order)
                            <tr>
                                <td class="min-w-175px">
                                    <div class="position-relative ps-6 ps-3 py-2">
                                        <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 {{ $order->status == 'pending' ? 'bg-warning' : ($order->status == 'processing' ? 'bg-info' : ($order->status == 'confirmed' ? 'bg-success' : 'bg-danger')) }}"></div>
                                        <a data-bs-toggle="modal" data-bs-target="#orderDetailsModal" wire:click="openOrderDetails({{ $order->id }})" class="mb-1 text-gray-900 text-hover-primary fw-bold cursor-pointer">
                                            <div class="border border-gray-400 border-dashed rounded py-2 px-4 me-2" style="max-width: fit-content;">

                                                <span class="text-muted">الوحدة</span> <span class="fs-6 text-gray-700 fw-bold">{{ $order->unit->title }}</span>
                                                @if ($order->unit->case == '1')
                                                    <span class="badge badge-light-success fs-7">مباع</span>
                                                @else
                                                    <span class="badge badge-light-dark fs-7">غير مباع</span>
                                                @endif
                                                <div class="fw-semibold text-gray-500">المشروع: {{ $order->unit->project->title }}</div>
                                            </div>
                                        </a>
                                    </div>
                                </td>
                                <td class="px-0">
                                    <!--begin::Icons-->
                                    <div class="d-flex gap-2 mb-2">
                                        {{ $order->user->username }}
                                    </div>
                                    <!--end::Icons-->
                                    <div class="fs-7 text-muted fw-bold">
                                        @if ($order->user->email)
                                        {{ $order->user->email }}
                                        <br>
                                        @endif
                                        @if ($order->user->phone)
                                        {{ $order->user->phone }}
                                        @endif
                                    </div>
                                </td>
                                <td class="px-0">
                                    @if ($order->status == 'pending')
                                    <span class="badge badge-light-warning fs-7"> قيد الانتطار </span>
                                    @elseif ($order->status == 'processing')
                                    <span class="badge badge-light-info fs-7"> جاري العمل عليه </span>
                                    @elseif ($order->status == 'confirmed')
                                    <span class="badge badge-light-success fs-7"> مكتمل </span>
                                    @elseif ($order->status == 'cancelled')
                                    <span class="badge badge-light-danger fs-7"> تم الغائة </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fs-7 text-muted fw-bold">
                                        {{ $order->note ? $order->note : 'لا يوجد ملاحظات' }}
                                    </div>
                                </td>
                                <td class="min-w-125px px-0">

                                    <div class="fs-7 d-flex align-items-center gap-2">
                                        @if ($order->payment_plan == 'bank')
                                            <i class="ki-outline ki-credit-cart fs-2 fw-bold"></i>
                                            <span class="fs-5">بنك</span>
                                        @elseif ($order->payment_plan == 'cash')
                                            <i class="ki-outline ki-wallet fs-2 fw-bold"></i>
                                            <span class="fs-5">كاش</span>

                                        @endif
                                    </div>
                                </td>
                                <td class="min-w-150px px-0">
                                    <div class="mb-2 fw-bold">{{ $order->created_at->format('Y-m-d h A') }}</div>
                                </td>
                                <td class="text-end px-0">
                                    <button type="button" class="btn btn-icon btn-sm btn-light btn-active-primary w-25px h-25px" data-bs-toggle="modal" data-bs-target="#orderDetailsModal" wire:click="openOrderDetails({{ $order->id }})">
                                        <i class="ki-outline ki-black-right fs-2 text-muted"></i>
                                        <span class="spinner-border spinner-border-sm me-4 text-white" wire:loading wire:target="openOrderDetails({{ $order->id }})"></span>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <div style="height: 117px;">
                                <h1 class="h1 text-center m-auto">لا يوجد طلبات</h1>
                            </div>
                            @endforelse
                        </tbody>
                        <!--end::Table body-->
                    </table>
                    <!--end::Table-->
                </div>

            </div>

            <div class="w-100 d-flex align-items-center justify-content-between">
                <div class="w-100">{{ $orders->links('pagination::bootstrap-4') }}</div>
            </div>
            <!--end::Action-->
        </div>
        <!--end: Card Body-->
    </div>
    <!--end::Table widget 8-->
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        $('#status-filter').select2({
            placeholder: 'اختر الحالة',
        });
        $('#status-filter').on('change', function (e) {
            var data = $('#status-filter').select2("val");
            @this.set('filterStatus', data);
        });

        $('#payment-plan-filter').select2({
            placeholder: 'اختر خطة الدفع',
        });
        $('#payment-plan-filter').on('change', function (e) {
            var data = $('#payment-plan-filter').select2("val");
            @this.set('filterPaymentPlan', data);
        });

        $('#project-filter').select2({
            placeholder: 'اختر المشروع',
        });
        $('#project-filter').on('change', function (e) {
            var data = $('#project-filter').select2("val");
            @this.set('filterProject', data);
        });

        // تهيئة flatpickr
        var flatpickrInstance = flatpickr("#kt_ecommerce_sales_flatpickr", {
            mode: "range",
            dateFormat: "Y-m-d",
            onChange: function(selectedDates, dateStr, instance) {
                @this.set('dateRange', dateStr); // Pass the date range to Livewire
            }
        });

        // زر مسح التواريخ
        document.querySelector('#kt_ecommerce_sales_flatpickr_clear').addEventListener('click', function () {
            flatpickrInstance.clear();
            Livewire.emit('updatedDateRange', '');  // مسح التواريخ في Livewire أيضاً
        });
    });

</script>
@endpush
