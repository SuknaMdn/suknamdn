<div>

    {{-- Page Toolbar --}}
    <div class="toolbar py-5 py-lg-8" id="kt_toolbar">
        <div class="container-xxl d-flex flex-stack flex-wrap">
            <div class="d-flex align-items-center py-1">
                <a href="{{ route('developer.orders') }}" class="btn btn-sm btn-flex btn-dark">
                    <i class="ki-outline ki-arrow-right fs-3"></i>
                    العودة للطلبات
                </a>
            </div>
            <div class="page-title d-flex flex-column justify-content-center ms-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">
                    <span class="page-desc text-muted fs-2 fw-semibold me-3">#{{ $order->id }}</span>
                    تفاصيل الطلب
                </h1>
            </div>
        </div>
    </div>
    @livewire('developer.components.order-onboarding', ['order' => $order], key($order->id))
    {{-- Order Overview Cards --}}
    <div class="row g-5 g-xl-10 mb-5" dir="rtl">
        {{-- Order Status Card --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
            <div class="card card-flush bg-dark h-100">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <span class="text-white opacity-75 pb-1 fw-semibold fs-6">حالة الطلب</span>
                        <span class="fw-bold text-white ms-2 mb-2">{{ $order->status_translated }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Unit Info Card --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
            <div class="card card-flush h-100">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-dark ms-2 lh-1 ls-n2">{{ number_format($order->unit->total_amount) }}</span>
                        <span class="text-gray-500 pt-1 fw-semibold fs-6"><img src="{{ asset('developer/Saudi_Riyal_Symbol.png') }}" width="10px" alt="">- قيمة الوحدة</span>
                    </div>
                </div>
                <div class="card-body pt-2 pb-4 d-flex align-items-center">
                    <div class="d-flex flex-column content-justify-center flex-row-fluid">
                        <div class="d-flex fw-semibold align-items-center">
                            <div class="bullet w-8px h-3px rounded-2 bg-dark ms-3"></div>
                            <div class="text-gray-500 flex-1 fs-6">{{ $order->unit->title }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Progress (if applicable) --}}
        @if ($order->unit->project->enables_payment_plan && !empty($this->financialSummary))
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
            <div class="card card-flush h-100">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ $this->financialSummary['percentage'] }}%</span>
                        <span class="text-gray-500 pt-1 fw-semibold fs-6">نسبة السداد</span>
                    </div>
                </div>
                <div class="card-body pt-2 pb-4 d-flex align-items-center">
                    <div class="d-flex flex-column content-justify-center flex-row-fluid">
                        <div class="progress h-6px w-100 bg-light-dark">
                            <div class="progress-bar bg-dark" role="progressbar" style="width: {{ $this->financialSummary['percentage'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Next Due Payment --}}
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
            <div class="card card-flush h-100">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        @if($this->nextDueInstallment)
                            <span class="fs-2hx fw-bold text-danger ms-2">{{ number_format($this->nextDueInstallment->amount) }}</span>
                            <span class="text-gray-500 pt-1 fw-semibold fs-6"><img src="{{ asset('developer/Saudi_Riyal_Symbol.png') }}" width="10px" alt="">- الدفعة القادمة</span>
                        @elseif ($this->financialSummary < 0)
                            <span class="fs-2hx fw-bold text-dark ms-2">✓</span>
                            <span class="text-gray-500 pt-1 fw-semibold fs-6">مكتمل السداد</span>
                        @else
                            <span class="fs-1hx fw-bold text-dark ms-2">لا توجد دفعات</span>
                            <span class="text-gray-500 pt-1 fw-semibold fs-6">لا توجد دفعات مستحقة</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Main Content Row --}}
    <div class="row g-5 g-xl-10" dir="rtl">
        
        {{-- Order Details --}}
        <div class="col-xl-4">
            <div class="card card-flush h-xl-100">
                <div class="card-body">
                    <div class="py-2">
                        <div class="d-flex align-items-center mb-4">
                            <div class="symbol symbol-40px symbol-circle ms-3">
                                <span class="symbol-label bg-light-dark text-dark fs-1 fw-bold">
                                    {{ substr($order->user->username, 0, 1) }}
                                </span>
                            </div>
                            <div class="d-flex flex-column flex-grow-1">
                                <a href="#" class="text-dark text-hover-primary fs-4 fw-bold mb-0">{{ $order->user->username }}</a>
                                <span class="text-muted fw-semibold">{{ $order->user->email }}</span>
                                <span class="text-muted fw-semibold">{{ $order->user->phone }}</span>
                            </div>
                        </div>

                        <div class="separator separator-dashed mb-7"></div>
                        
                        {{-- Unit Details --}}
                        <div class="mb-8">
                            <h4 class="fw-bold text-dark mb-5">تفاصيل الوحدة</h4>
                            <div class="row g-5">
                                <div class="col-6">
                                    <div class="fw-semibold text-gray-600 fs-7">اسم الوحدة</div>
                                    <div class="fw-bold text-dark fs-6">{{ $order->unit->title }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="fw-semibold text-gray-600 fs-7">المساحة</div>
                                    <div class="fw-bold text-dark fs-6">{{ $order->unit->total_area }} م²</div>
                                </div>
                                <div class="col-6">
                                    <div class="fw-semibold text-gray-600 fs-7">سعر الوحدة</div>
                                    <div class="fw-bold text-dark fs-6">{{ number_format($order->unit->unit_price) }} <img src="{{ asset('developer/Saudi_Riyal_Symbol.png') }}" width="10px" alt=""></div>
                                </div>
                                <div class="col-6">
                                    <div class="fw-semibold text-gray-600 fs-7">المشروع</div>
                                    <div class="fw-bold text-dark fs-6">{{ $order->unit->project->title }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Management & Documents --}}
        <div class="col-xl-8">
            {{-- Payment Management Section --}}
            @if ($order->unit->project->enables_payment_plan)
            <div class="card card-flush mb-5 mb-xl-10">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <h3 class="fw-bold text-dark m-0">الإدارة المالية</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    {{-- Installments Table --}}
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th class="min-w-200px">الدفعة</th>
                                    <th class="min-w-100px">المبلغ</th>
                                    <th class="min-w-100px">الحالة</th>
                                    <th class="min-w-100px text-end">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @forelse ($order->installments as $installment)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-45px ms-5">
                                                <span class="symbol-label bg-light-dark text-dark">
                                                    <i class="ki-outline ki-wallet fs-2x"></i>
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-start flex-column">
                                                <a href="#" class="text-dark fw-bold text-hover-primary fs-6">{{ $installment->milestone?->name }}</a>
                                                <span class="text-muted fw-semibold text-muted d-block fs-7">{{ $installment->milestone?->completion_milestone }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bold d-block fs-6">{{ number_format($installment->amount, 2) }} <img src="{{ asset('developer/Saudi_Riyal_Symbol.png') }}" width="10px" alt=""></span>
                                    </td>
                                    <td>
                                        <span class="badge badge-light-{{ $installment->status_color }} fs-7 fw-semibold">
                                            {{ $installment->status_translated }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-end flex-shrink-0">
                                            @if ($installment->receipt_url)
                                                <a href="{{ asset('storage/' . $installment->receipt_url) }}" target="_blank" class="btn btn-icon btn-bg-light btn-active-color-dark btn-sm ms-1">
                                                    <i class="ki-outline ki-eye fs-3"></i>
                                                </a>
                                            @endif
                                            @if ($installment->status != 'paid' && $installment->receipt_url)
                                                <button wire:click="confirmPayment({{ $installment->id }})" 
                                                        wire:confirm="هل أنت متأكد من تأكيد هذه الدفعة؟" 
                                                        class="btn btn-icon btn-bg-light btn-active-color-dark btn-sm"
                                                        wire:loading.attr="disabled"
                                                        wire:target="confirmPayment({{ $installment->id }})">
                                                    <span wire:loading.remove wire:target="confirmPayment({{ $installment->id }})">
                                                        <i class="ki-outline ki-check fs-3"></i>
                                                    </span>
                                                    <span wire:loading wire:target="confirmPayment({{ $installment->id }})">
                                                        <span class="spinner-border spinner-border-sm"></span>
                                                    </span>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-10">
                                        <div class="fs-4 fw-bolder text-gray-700 mb-2 mt-5">لا توجد دفعات</div>
                                        <div class="fs-6 text-gray-500">لم يتم إنشاء جدول دفعات لهذا الطلب بعد.</div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            {{-- Documents Section --}}
            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <h3 class="fw-bold text-dark m-0">المستندات التعاقدية</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-6 g-xl-9">
                        
                        {{-- Istisna Contract Card --}}
                        <div class="col-md-6">
                            <div class="card card-dashed h-100">
                                <div class="card-body d-flex flex-column justify-content-between p-6">
                                    <div>
                                        <div class="fs-5 fw-bold mb-2">عقد الاستصناع</div>
                                        <p class="text-muted fs-7">العقد الرئيسي بين المطور والعميل.</p>
                                    </div>
                                    
                                    @if($order->istisna_contract_url)
                                        <div class="d-flex flex-column gap-3 mt-5">
                                            <a href="{{ asset('storage/' . $order->istisna_contract_url) }}" target="_blank" class="btn btn-sm btn-light-dark">
                                                <i class="ki-outline ki-file-down fs-4 ms-2"></i> تحميل العقد
                                            </a>
                                            <button wire:click="sendNotification('istisna')" class="btn btn-sm btn-light" wire:loading.attr="disabled" wire:target="sendNotification('istisna')">
                                                <i class="ki-outline ki-notification-status fs-4 ms-2"></i> إشعار العميل
                                            </button>
                                        </div>
                                    @else
                                        <div class="mt-5">
                                            <input type="file" class="form-control form-control-sm" wire:model="istisnaContractFile" accept=".pdf">
                                            <div wire:loading wire:target="istisnaContractFile" class="text-muted fs-8 mt-1">جاري التحميل...</div>
                                            @error('istisnaContractFile') <div class="text-danger mt-1 fs-8">{{ $message }}</div> @enderror
                                            
                                            <button wire:click="uploadFile('istisna')" class="btn btn-sm btn-dark w-100 mt-3" wire:loading.attr="disabled" wire:target="uploadFile('istisna')">
                                                <span wire:loading.remove wire:target="uploadFile('istisna')"><i class="ki-outline ki-file-up fs-4 ms-2"></i> رفع الملف</span>
                                                <span wire:loading wire:target="uploadFile('istisna')">جاري الرفع...</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        {{-- Price Quote Card --}}
                        <div class="col-md-6">
                            <div class="card card-dashed h-100">
                                <div class="card-body d-flex flex-column justify-content-between p-6">
                                    <div>
                                        <div class="fs-5 fw-bold mb-2">عرض السعر</div>
                                        <p class="text-muted fs-7">مستند مطلوب للتمويل البنكي.</p>
                                    </div>
                                    
                                    @if($order->price_quote_url)
                                        <div class="d-flex flex-column gap-3 mt-5">
                                            <a href="{{ asset('storage/' . $order->price_quote_url) }}" target="_blank" class="btn btn-sm btn-light-dark">
                                                <i class="ki-outline ki-file-down fs-4 ms-2"></i> تحميل عرض السعر
                                            </a>
                                            <button wire:click="sendNotification('quote')" class="btn btn-sm btn-light" wire:loading.attr="disabled" wire:target="sendNotification('quote')">
                                                <i class="ki-outline ki-notification-status fs-4 ms-2"></i> إشعار العميل
                                            </button>
                                        </div>
                                    @else
                                        <div class="mt-5">
                                            <input type="file" class="form-control form-control-sm" wire:model="priceQuoteFile" accept=".pdf">
                                            <div wire:loading wire:target="priceQuoteFile" class="text-muted fs-8 mt-1">جاري التحميل...</div>
                                            @error('priceQuoteFile') <div class="text-danger mt-1 fs-8">{{ $message }}</div> @enderror
                                            
                                            <button wire:click="uploadFile('quote')" class="btn btn-sm btn-dark w-100 mt-3" wire:loading.attr="disabled" wire:target="uploadFile('quote')">
                                                <span wire:loading.remove wire:target="uploadFile('quote')"><i class="ki-outline ki-file-up fs-4 ms-2"></i> رفع الملف</span>
                                                <span wire:loading wire:target="uploadFile('quote')">جاري الرفع...</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Additional Actions and Timeline --}}
    <div class="row g-5 g-xl-10 mt-5 mb-10" dir="rtl">
        {{-- Actions --}}
        <div class="col-xl-4">
            <div class="card card-flush h-100">
                <div class="card-header">
                    <h3 class="card-title fw-bold text-dark">إجراءات سريعة</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-3">
                        <button class="btn btn-outline btn-outline-dashed btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_update_order">
                            <i class="ki-outline ki-pencil fs-4 ms-2"></i> تحديث حالة الطلب
                        </button>
                        {{-- <button class="btn btn-outline btn-outline-dashed btn-outline-dark btn-sm">
                            <i class="ki-outline ki-sms fs-4 ms-2"></i> إرسال رسالة للعميل
                        </button> --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- Timeline Section --}}
        <div class="col-xl-8" dir="rtl">
            <div class="card card-flush h-100">
                <div class="card-header">
                    <h3 class="card-title fw-bold text-dark">سجل الأنشطة</h3>
                </div>
                <div class="card-body pt-0">
                    <div class="timeline timeline-border-dashed">
                        <div class="timeline-item">
                            <div class="timeline-line"></div>
                            <div class="timeline-icon"><i class="ki-outline ki-check fs-2 text-muted"></i></div>
                            <div class="timeline-content me-4">
                                <div class="fw-bold text-dark">تم إنشاء الطلب</div>
                                <div class="fs-8 text-muted">{{ $order->created_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>
                        
                        @foreach($order->getTimeline() as $event)
                        <div class="timeline-item">
                            <div class="timeline-line"></div>
                            <div class="timeline-icon"><i class="ki-outline {{ $event['icon'] }} fs-2 text-muted"></i></div>
                            <div class="timeline-content me-4">
                                <div class="fw-bold text-dark">{{ $event['text'] }}</div>
                                <div class="fs-8 text-muted">{{ $event['date'] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Update Order Status Modal --}}
    <div class="modal fade" id="kt_modal_update_order" tabindex="-1" aria-hidden="true" wire:ignore.self dir="rtl">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <form wire:submit.prevent="updateOrderStatus">
                    <div class="modal-header">
                        <h2 class="fw-bold">تحديث حالة الطلب</h2>
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </div>
                    </div>
                    <div class="modal-body py-10 px-lg-17">
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">الحالة الجديدة</label>
                            <select wire:model="newStatus" class="form-select form-select-solid" data-hide-search="true" data-placeholder="اختر الحالة">
                                <option value="pending">في الانتظار</option>
                                <option value="processing">قيد المعالجة</option>
                                <option value="completed">مكتمل</option>
                                <option value="cancelled">ملغي</option>
                            </select>
                            @error('newStatus') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                        </div>
                        {{-- <div class="fv-row">
                            <label class="fw-semibold fs-6 mb-2">ملاحظات (اختياري)</label>
                            <textarea wire:model="statusNotes" class="form-control form-control-solid" rows="3" placeholder="إضافة ملاحظات حول التحديث..."></textarea>
                        </div> --}}
                    </div>
                    <div class="modal-footer flex-center">
                        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-dark" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="updateOrderStatus">حفظ التغييرات</span>
                            <span wire:loading wire:target="updateOrderStatus">جاري الحفظ...
                                <span class="spinner-border spinner-border-sm align-middle me-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Close modal on successful update
            Livewire.on('orderStatusUpdated', (event) => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('kt_modal_update_order'));
                modal.hide();
            });

            // Reload page on file upload to refresh asset URLs correctly
            Livewire.on('fileUploaded', (event) => {
                setTimeout(() => {
                    window.location.reload();
                }, 1000); 
            });
        });
    </script>
    @endpush
</div>