<div>
    <div wire:ignore.self class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    @if($order)
                    <div class="row gx-5 gx-xl-10" wire:loading.class="opacity-50">
                        <!--begin::Col-->
                        <div class="col-sm-12 mb-5">
                            <!--begin::List widget 1-->
                            <div class="card card-flush shadow-none h-lg-100">
                                <!--begin::Header-->
                                <div class="card-header pt-5">

                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bold text-gray-900">Order Details</span>
                                        <span class="text-gray-500 mt-1 fw-semibold fs-6">#-{{ $order->id }}</span>
                                    </h3>
                                </div>
                                <!--end::Header-->
                                <!--begin::Body-->
                                <div class="card-body py-5">
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack">
                                        <!--begin::Section-->
                                        <div class="text-gray-700 fw-semibold fs-6 me-2">
                                            Order Status
                                            <!--end::Section-->
                                            <div class="d-inline-block">
                                                <span class="text-gray-900 fw-bolder fs-6">
                                                    @if($status == 'pending')
                                                    <span class="badge badge-light-warning">Pending</span>
                                                    @elseif($status == 'processing')
                                                    <span class="badge badge-light-primary">Processing</span>
                                                    @elseif($status == 'confirmed')
                                                    <span class="badge badge-light-success">Confirmed</span>
                                                    @elseif($status == 'cancelled')
                                                    <span class="badge badge-light-danger">Cancelled</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center">
                                            <select
                                                class="form-select form-select-sm"
                                                wire:model.live="status"
                                                wire:change="updateStatus"
                                                id="orderStatusSelect"
                                            >
                                                <option value="pending">Pending</option>
                                                <option value="processing">Processing</option>
                                                <option value="confirmed">Confirmed</option>
                                                <option value="cancelled">Cancelled</option>
                                            </select>
                                        </div>
                                        <!--end::Statistics-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Separator-->
                                    <div class="separator separator-dashed my-3"></div>
                                    <!--end::Separator-->
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack">
                                        <!--begin::Section-->
                                        <div class="text-gray-700 fw-semibold fs-6 me-2">Customer</div>

                                        <a href="#" class="d-flex flex-row gap-2" title="{{ $order->user->name . ' - ' . $order->user->phone . ' - ' . $order->user->email }}" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                            <div class="d-flex align-items-senter">
                                                <span class="text-gray-900 fw-bolder fs-6">{{ $order->user->username }}</span>
                                            </div>
                                            <div class="d-flex align-items-senter">
                                                <span class="text-gray-900 fw-bolder fs-6">{{ $order->user->phone }}</span>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="separator separator-dashed my-3"></div>

                                    <div class="d-flex flex-stack">
                                        <!--begin::Section-->
                                        <div class="text-gray-700 fw-semibold fs-6 me-2">Tax Exemption</div>
                                        <div class="d-flex align-items-senter">
                                            <span class="text-gray-900 fw-bolder fs-6">
                                                @if($order->tax_exemption_status)
                                                <span class="badge badge-light-success"><i class="ki-outline ki-double-check fs-1 text-success"></i></span>
                                                @else
                                                <span class="badge badge-light-danger"><i class="ki-outline ki-cross fs-1 text-danger"></i></span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    <div class="separator separator-dashed my-3"></div>

                                    <div class="d-flex flex-stack">
                                        <!--begin::Section-->
                                        <div class="text-gray-700 fw-semibold fs-6 me-2">Payment Plan</div>
                                        <!--end::Section-->
                                        <!--begin::Statistics-->
                                        <div class="d-flex align-items-senter">
                                            <span class="text-gray-900 fw-bolder fs-6">
                                                {{ $order->payment_plan }}
                                            </span>
                                            <!--end::Number-->
                                        </div>
                                        <!--end::Statistics-->
                                    </div>
                                    <!--end::Item-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::LIst widget 1-->
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-sm-12">
                            <!--begin::List widget 2-->
                            <div class="card card-flush shadow-none h-lg-100 mb-5">
                                <!--begin::Header-->
                                <div class="card-header py-5">
                                    <!--begin::Title-->
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bold text-gray-900">Unit Details</span>
                                    </h3>
                                </div>
                                <!--end::Header-->
                                <!--begin::Body-->
                                <div class="card-body py-5">
                                    <div class="d-flex flex-stack">
                                        <!--begin::Wrapper-->
                                        <div class="d-flex align-items-center me-3">
                                            <!--begin::Icon-->
                                            <div class="symbol symbol-40px symbol-circle me-3">
                                                <span class="symbol-label" style="background: rgb(234, 234, 234);">
                                                    <i class="ki-outline ki-clipboard text-dark fs-1"></i>
                                                </span>
                                            </div>
                                            <!--end::Icon-->
                                            <!--begin::Section-->
                                            <div class="flex-grow-1">
                                                <a href="#" class="text-gray-800 text-hover-primary fs-5 fw-bold lh-0">{{ $order->unit->project->title }}</a>
                                                <span class="text-gray-500 fw-semibold d-block fs-6">Unit {{ $order->unit->title }}</span>
                                            </div>
                                            <!--end::Section-->
                                        </div>
                                        <!--end::Wrapper-->
                                        <div class="d-flex align-items-center w-100 mw-90px">
                                            <a href="{{ route('developer.projects.show', $order->unit->project->slug) }}" target="_blank" class="btn btn-light btn-sm">View <i class="ki-outline ki-eye ms-2"></i></a>
                                        </div>
                                    </div>
                                    <div class="my-5">
                                        <div class="d-flex justify-content-between flex-stack">

                                            <div class="text-gray-700 fw-semibold fs-6 me-2">Total Amount</div>

                                            <div class="d-flex align-items-center">
                                                <i class="ki-outline ki-wallet fs-2 text-success me-2"></i>
                                                <span class="text-gray-900 fw-bolder fs-6 me-2">{{ number_format($order->unit->total_amount, 2) }}</span>
                                                <span class="text-gray-500 fw-bold fs-6">
                                                    ( Price: {{ number_format($order->unit->unit_price, 2) }} )
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-stack justify-content-between mt-5">

                                        <div class="d-flex flex-stack">
                                            <div class="text-gray-700 fw-semibold fs-6 me-2">Floor <i class="ki-outline ki-building-o ms-2"></i></div>
                                            <div class="d-flex align-items-senter">
                                                <span class="text-gray-900 fw-bolder fs-6 me-2">{{ $order->unit->floor }}</span>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-stack">
                                            <div class="text-gray-700 fw-semibold fs-6 me-2">Space</div>
                                            <div class="d-flex align-items-senter">
                                                <span class="text-gray-900 fw-bolder fs-6 me-2">{{ $order->unit->total_area }} m²</span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="d-flex flex-stack justify-content-between mt-5">

                                        <div class="d-flex flex-stack">
                                            <div class="text-gray-700 fw-semibold fs-6 me-2">Building</div>
                                            <div class="d-flex align-items-senter">
                                                <span class="text-gray-900 fw-bolder fs-6 me-2">{{ $order->unit->building_number }}</span>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-stack">
                                            <div class="text-gray-700 fw-semibold fs-6 me-2">Unit number</div>
                                            <div class="d-flex align-items-senter">
                                                <span class="text-gray-900 fw-bolder fs-6 me-2">{{ $order->unit->unit_number }}</span>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-stack">
                                            <div class="text-gray-700 fw-semibold fs-6 me-2">Unit type</div>
                                            <div class="d-flex align-items-senter">
                                                <span class="text-gray-900 fw-bolder fs-6 me-2">{{ $order->unit->unit_type }}</span>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <!--end::Col-->
                    </div>
                    @endif
                    <p wire:loading> <i class="spinner-border spinner-border-sm fs-2 text-muted"></i> Loading...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="closeModal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function () {
    $('#orderStatusSelect').select2();

    $('#orderStatusSelect').on('change', function (e) {
        var data = $(this).val();
        @this.set('status', data);
    });
});
</script>
@endpush
