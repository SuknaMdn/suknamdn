<div wire:ignore.self class="modal fade" id="orderDetailsModal" tabindex="-1" aria-hidden="true" dir="rtl">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header flex justify-content-between align-items-center">
                <h2 class="modal-title fs-3">تفاصيل الطلب #{{ $order->id ?? '' }}</h2>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Body -->
            <div class="modal-body py-6 px-6" wire:loading.class="opacity-50">
                @if($order)
                <div class="d-flex flex-column">
                    <!-- Order Status Card -->
                    <div class="card mb-6">
                        <div class="card-header border-0 py-6">
                            <div class="card-title">
                                <h3 class="fw-bold m-0">حالة الطلب</h3>
                            </div>
                            <div class="card-toolbar">
                                <select class="form-select form-select-solid" wire:model.live="status" wire:change="updateStatus">
                                    <option value="pending">قيد الانتظار</option>
                                    <option value="processing">قيد المعالجة</option>
                                    <option value="confirmed">مؤكد</option>
                                    <option value="cancelled">ملغى</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="d-flex align-items-center mb-5">
                                <div class="symbol symbol-50px ms-5">
                                    <span class="symbol-label bg-light-{{ $status == 'pending' ? 'warning' : ($status == 'processing' ? 'primary' : ($status == 'confirmed' ? 'success' : 'danger')) }}">
                                        <i class="ki-outline ki-abstract-{{ $status == 'pending' ? '26' : ($status == 'processing' ? '28' : ($status == 'confirmed' ? '38' : '39')) }} fs-2x text-{{ $status == 'pending' ? 'warning' : ($status == 'processing' ? 'primary' : ($status == 'confirmed' ? 'success' : 'danger')) }}"></i>
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-600 fw-semibold fs-6">الحالة الحالية</span>
                                    <span class="fw-bold fs-4 text-gray-800">
                                        @if($status == 'pending')
                                        قيد الانتظار
                                        @elseif($status == 'processing')
                                        قيد المعالجة
                                        @elseif($status == 'confirmed')
                                        مؤكد
                                        @elseif($status == 'cancelled')
                                        ملغى
                                        @endif
                                    </span>
                                </div>
                            </div>
                            
                            <div class="separator separator-dashed my-5"></div>
                            
                            <div class="row g-5">
                                <div class="col-md-6">
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-600 fw-semibold fs-6">العميل</span>
                                        <div class="d-flex align-items-center mt-2">
                                            <div class="symbol symbol-35px symbol-circle ms-3">
                                                <span class="symbol-label bg-light-dark">
                                                    <i class="ki-outline ki-profile-circle fs-2 text-dark"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <a href="#" class="text-gray-800 text-hover-dark fw-bold fs-6">{{ $order->user->name }}</a>
                                                <span class="text-gray-500 fw-semibold d-block">{{ $order->user->phone }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-600 fw-semibold fs-6">خطة الدفع</span>
                                        <span class="fw-bold fs-6 text-gray-800 mt-2">
                                            {{ $order->payment_plan == 'cash' ? 'كاش' : ($order->payment_plan == 'bank' ? 'بنك' : ($order->payment_plan == 'mortgage' ? 'رهن عقاري' : 'غير محدد')) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-600 fw-semibold fs-6">تاريخ الطلب</span>
                                        <span class="fw-bold fs-6 text-gray-800 mt-2">{{ $order->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-600 fw-semibold fs-6">الإعفاء الضريبي</span>
                                        <span class="fw-bold fs-6 text-gray-800 mt-2">
                                            @if($order->tax_exemption_status)
                                            <span class="badge badge-light-success">نعم</span>
                                            @else
                                            <span class="badge badge-light-danger">لا</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Unit Details Card -->
                    <div class="card">
                        <div class="card-header border-0 py-6  d-flex justify-content-between align-items-center">
                            <div class="card-title">
                                <h3 class="fw-bold m-0">تفاصيل الوحدة</h3>
                            </div>
                            <div class="card-toolbar">
                                <a href="{{ route('developer.projects.show', $order->unit->project->slug) }}" target="_blank" class="btn btn-sm btn-light-dark">
                                    عرض المشروع <i class="ki-outline ki-eye fs-2 me-2"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="d-flex flex-stack mb-8">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-60px symbol-circle ms-4">
                                        <span class="symbol-label bg-light-dark">
                                            <i class="ki-outline ki-home-2 fs-2x text-dark"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <a href="#" class="fs-4 fw-bold text-gray-800 text-hover-primary">{{ $order->unit->project->title }}</a>
                                        <span class="text-gray-600 fw-semibold">الوحدة {{ $order->unit->title }}</span>
                                    </div>
                                </div>
                                <div class="d-flex flex-column text-end">
                                    <span class="text-gray-600 fw-semibold">السعر الإجمالي</span>
                                    <span class="fw-bold fs-3 text-gray-800">{{ number_format($order->unit->total_amount, 2) }} ر.س</span>
                                </div>
                            </div>
                            
                            <div class="separator separator-dashed my-5"></div>
                            
                            <div class="row g-5">
                                <div class="col-md-4">
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-600 fw-semibold fs-6">نوع الوحدة</span>
                                        <span class="fw-bold fs-6 text-gray-800 mt-2">{{ $order->unit->unit_type }}</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-600 fw-semibold fs-6">رقم الوحدة</span>
                                        <span class="fw-bold fs-6 text-gray-800 mt-2">{{ $order->unit->unit_number }}</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-600 fw-semibold fs-6">العمارة</span>
                                        <span class="fw-bold fs-6 text-gray-800 mt-2">{{ $order->unit->building_number }}</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-600 fw-semibold fs-6">الطابق</span>
                                        <span class="fw-bold fs-6 text-gray-800 mt-2">{{ $order->unit->floor }}</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-600 fw-semibold fs-6">المساحة</span>
                                        <span class="fw-bold fs-6 text-gray-800 mt-2">{{ $order->unit->total_area }} م²</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-600 fw-semibold fs-6">سعر الوحدة</span>
                                        <span class="fw-bold fs-6 text-gray-800 mt-2">{{ number_format($order->unit->unit_price, 2) }} ر.س</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
            </div>
            
            <!-- Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" wire:click="closeModal">إغلاق</button>
                @if($order)
                <a href="{{ route('developer.orders.show', $order) }}" class="btn btn-primary">عرض التفاصيل الكاملة</a>
                @endif
            </div>
        </div>
    </div>
</div>