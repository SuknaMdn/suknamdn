<div>
    <!-- Order Details Modal -->
    @livewire('developer.components.order-details-modal')

    <!--begin::Table widget 8-->
    <div class="card h-xl-100">
        <!--begin::Header-->
        <div class="card-header position-relative py-0 border-bottom-2 pe-3">
            <h3 class="card-title">Orders</h3>
            <!--begin::Actions-->
            <div class="card-toolbar">
                <div class="d-flex justify-content-between">

                    <span class="spinner-border spinner-border-sm me-4 m-auto" wire:loading wire:target="filterPaymentPlan, filterStatus, filterProject, searchTerm"></span>

                    <div class="d-flex align-items-center position-relative">
                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                        <input
                            wire:model.live.debounce.300ms="searchTerm"
                            type="text"
                            class="form-control form-control-solid w-250px ps-13 rounded h-100"
                            placeholder="Search Orders"
                        />
                    </div>

                    <div class="d-flex align-items-center" wire:ignore>
                        <div class=" me-3 ms-3" style="min-width: 200px;">
                            <select
                                wire:model.live="filterStatus"
                                data-control="select2"
                                class="form-select form-select-solid"
                                multiple="multiple"
                                id="status-filter">
                                <option value="" disabled selected hidden>Select Status</option>
                                @foreach($statusOptions as $status)
                                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class=" me-3" style="min-width: 200px;" wire:ignore>
                            <select
                                wire:model.live="filterPaymentPlan"
                                data-control="select2"
                                class="form-select form-select-solid"
                                aria-placeholder="Select Payment Plan"
                                multiple="multiple"
                                id="payment-plan-filter">
                                <option value="" disabled selected hidden>Select Payment Plan</option>
                                @foreach($paymentPlanOptions as $plan)
                                    <option value="{{ $plan }}">{{ $plan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class=" me-3" style="min-width: 200px;" wire:ignore>
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
                                <th class="min-w-150px p-0 text-muted">Unit</th>
                                <th class="min-w-200px p-0 text-muted">Customer</th>
                                <th class="min-w-100px p-0 text-muted">Status</th>
                                <th class="min-w-80px p-0 text-muted">Payment Plan</th>
                            </tr>
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody>
                            @foreach ($orders as $order)

                            <tr>
                                <td class="min-w-175px">
                                    <div class="position-relative ps-6 pe-3 py-2">
                                        <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 {{ $order->status == 'pending' ? 'bg-warning' : ($order->status == 'processing' ? 'bg-info' : ($order->status == 'confirmed' ? 'bg-success' : 'bg-danger')) }}"></div>
                                        <a data-bs-toggle="modal" data-bs-target="#orderDetailsModal" wire:click="openOrderDetails({{ $order->id }})" class="mb-1 text-gray-900 text-hover-primary fw-bold cursor-pointer">
                                            <div class="border border-gray-400 border-dashed rounded min-w-100px w-100 py-2 px-4 me-2">

                                                <span class="text-muted">Unit</span> <span class="fs-6 text-gray-700 fw-bold">{{ $order->unit->title }}</span>
                                                @if ($order->unit->case == '1')
                                                    <span class="badge badge-light-success fs-7">Paid</span>
                                                @else
                                                    <span class="badge badge-light-danger fs-7">Unpaid</span>
                                                @endif

                                                <div class="fw-semibold text-gray-500">Project: {{ $order->unit->project->title }}</div>
                                            </div>
                                        </a>
                                        <div class="fs-7 text-muted fw-bold mt-3">Created on {{ $order->created_at->format('d M Y h:i A') }}</div>
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
                                    <span class="badge badge-light-warning fs-7">{{ $order->status }}</span>
                                    @elseif ($order->status == 'processing')
                                    <span class="badge badge-light-info fs-7">{{ $order->status }}</span>
                                    @elseif ($order->status == 'confirmed')
                                    <span class="badge badge-light-success fs-7">{{ $order->status }}</span>
                                    @elseif ($order->status == 'cancelled')
                                    <span class="badge badge-light-danger fs-7">{{ $order->status }}</span>
                                    @endif
                                </td>
                                <td class="min-w-125px px-0">

                                    <div class="fs-7 d-flex align-items-center gap-2">
                                        @if ($order->payment_plan == 'bank')
                                        <i class="ki-outline ki-credit-cart fs-2 fw-bold"></i>
                                        @elseif ($order->payment_plan == 'cash')
                                        <i class="ki-outline ki-wallet fs-2 fw-bold"></i>

                                        @endif
                                        <span class="fs-5">{{ $order->payment_plan }}</span>
                                    </div>
                                </td>
                                <td class="min-w-150px px-0">
                                    <div class="mb-2 fw-bold">{{ $order->updated_at->format('d M Y h:i A') }}</div>
                                    <div class="fs-7 fw-bold text-muted">Last Update</div>
                                </td>
                                <td class="text-end px-0">
                                    <button type="button" class="btn btn-icon btn-sm btn-light btn-active-primary w-25px h-25px" data-bs-toggle="modal" data-bs-target="#orderDetailsModal" wire:click="openOrderDetails({{ $order->id }})">
                                        <i class="ki-outline ki-black-right fs-2 text-muted"></i>
                                        <span class="spinner-border spinner-border-sm me-4 text-white" wire:loading wire:target="openOrderDetails({{ $order->id }})"></span>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <!--end::Table body-->
                    </table>
                    <!--end::Table-->
                </div>

            </div>

            <div class=" d-flex align-items-center justify-content-between">
                <div>{{ $orders->links('pagination::bootstrap-4') }}</div>
                <div>
                    @if($currentRoute !== 'developer.orders')
                        <a href="{{ route('developer.orders') }}" class="btn btn-sm btn-light me-2">Show All</a>
                    @endif
                </div>
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
            placeholder: 'Select Status',
        });
        $('#status-filter').on('change', function (e) {
            var data = $('#status-filter').select2("val");
            @this.set('filterStatus', data);
        });

        $('#payment-plan-filter').select2({
            placeholder: 'Select Payment Plan',
        });
        $('#payment-plan-filter').on('change', function (e) {
            var data = $('#payment-plan-filter').select2("val");
            @this.set('filterPaymentPlan', data);
        });

        $('#project-filter').select2({
            placeholder: 'Select Project',
        });
        $('#project-filter').on('change', function (e) {
            var data = $('#project-filter').select2("val");
            @this.set('filterProject', data);
        });
    });
</script>
@endpush
