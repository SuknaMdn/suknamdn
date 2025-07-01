<div>
@if ($enables_payment_plan ==1)
<div class="onboarding-float shadow-lg" dir="rtl" style="position: fixed; bottom: 20px; right: 20px; width: 360px; z-index: 1050; background: #fff; border-radius: 1rem; overflow: hidden;" id="onboardingCard">
    <div class="bg-light px-5 py-4 d-flex justify-content-between align-items-center border-bottom">
        <h4 class="fw-bold text-dark mb-0">
            دليل إتمام الطلب
            <span class="onboarding-collapsed badge badge-light-primary" id="onboardingbadge">الخطوة {{ $currentStep }} من 3</span>
        </h4>
        <button class="btn btn-sm btn-icon btn-light" onclick="toggleOnboardingPanel()">
            <i class="ki-outline ki-arrow-up fs-2"></i>
        </button>
    </div>

    <div class="px-5 py-4" id="onboardingContent">
        <!-- Step Indicator -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <span class="badge badge-light-{{ $currentStep == 1 ? 'primary' : 'dark' }}">الخطوة {{ $currentStep }} من 3</span>
            <div class="progress w-50 bg-light" style="height: 6px;">
                <div class="progress-bar bg-primary" style="width: {{ $currentStep * 33.3 }}%"></div>
            </div>
        </div>

        <!-- Step Content -->
        @if($currentStep == 1)
            <div>
                <h5 class="fw-semibold mb-2">عقد الاستصناع</h5>
                @if($order->istisna_contract_url)
                    <a href="{{ asset('storage/' . $order->istisna_contract_url) }}" target="_blank" class="btn btn-sm btn-light-dark w-100 mb-2">
                        <i class="ki-outline ki-file-down fs-4 ms-2"></i> تحميل العقد
                    </a>
                @else
                    <input type="file" class="form-control form-control-sm mb-2" wire:model="istisnaContractFile" accept=".pdf">
                    @error('istisnaContractFile') <div class="text-danger mt-1 fs-8">{{ $message }}</div> @enderror
                    <button wire:click="uploadIstisnaContract" class="btn btn-sm btn-primary w-100" wire:loading.attr="disabled">
                        <span wire:loading.remove><i class="ki-outline ki-file-up fs-4 ms-2"></i> رفع العقد</span>
                        <span wire:loading><span class="spinner-border spinner-border-sm"></span> جاري الرفع...</span>
                    </button>
                @endif

                <hr class="my-4" />

                @if($order->price_quote_url)
                    <h5 class="fw-semibold mb-2">عرض السعر</h5>
                    <a href="{{ asset('storage/' . $order->price_quote_url) }}" target="_blank" class="btn btn-sm btn-light-dark w-100 mb-2">
                        <i class="ki-outline ki-file-down fs-4 ms-2"></i> تحميل عرض السعر
                    </a>
                @elseif($order->payment_plan == 'bank')
                    <h5 class="fw-semibold mb-2">عرض السعر</h5>

                    <input type="file" class="form-control form-control-sm mb-2" wire:model="priceQuoteFile" accept=".pdf">
                    @error('priceQuoteFile') <div class="text-danger mt-1 fs-8">{{ $message }}</div> @enderror
                    <button wire:click="uploadPriceQuote" class="btn btn-sm btn-primary w-100" wire:loading.attr="disabled">
                        <span wire:loading.remove><i class="ki-outline ki-file-up fs-4 ms-2"></i> رفع عرض السعر</span>
                        <span wire:loading><span class="spinner-border spinner-border-sm"></span> جاري الرفع...</span>
                    </button>
                @endif
            </div>
        @elseif($currentStep == 2)
            <div>
                <label class="form-label">نسبة الإنجاز</label>
                <div class="d-flex align-items-center mb-3" dir="ltr">
                    <input type="number" wire:model="completionPercentage" class="form-control form-control-sm me-2" min="0" max="100" />
                    <button class="btn btn-sm btn-icon btn-light" wire:click="updateProjectCompletion">
                        <i class="ki-outline ki-check fs-2"></i>
                    </button>
                </div>

                <div class="progress h-10px bg-light">
                    <div class="progress-bar bg-success" style="width: {{ $completionPercentage }}%"></div>
                </div>

                <hr class="my-4" />

                {{-- <h6 class="fw-bold mb-3">الدفعات</h6>
                <div class="mb-3" style="max-height: 200px; overflow-y: auto;">
                    @foreach($order->installments as $installment)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <div class="fw-semibold fs-7">{{ $installment->milestone?->name }}</div>
                                <div class="text-muted fs-8">{{ number_format($installment->amount, 2) }} ر.س</div>
                            </div>
                            <div>
                                @if($installment->status !== 'paid')
                                    <button class="btn btn-sm btn-icon btn-light-success" wire:click="confirmPayment({{ $installment->id }})">
                                        <i class="ki-outline ki-check-circle fs-2"></i>
                                    </button>
                                @else
                                    <span class="badge badge-light-success">مدفوع</span>
                                @endif
                            </div> 
                        </div>
                    @endforeach
                </div> --}}
            </div>
        @elseif($currentStep == 3)
            <div class="text-center">
                <i class="ki-outline ki-check-circle fs-5x text-success mb-4"></i>
                <h4 class="fw-bold text-dark mb-3">تم إتمام الطلب؟</h4>
                @if($order->status != 'completed')
                    <button class="btn btn-success w-100 mb-3" wire:click="openStatusModal">تأكيد الإتمام</button>
                @else
                    <span class="badge badge-success">مكتمل</span>
                @endif
            </div>
        @endif

        <div class="d-flex justify-content-between mt-4 w-100">
            @if($currentStep > 1)
                <button class="btn btn-sm btn-light" wire:click="previousStep">السابق</button>
            @endif
            @if($currentStep < 3)
                <button class="btn btn-sm btn-primary" wire:click="nextStep">التالي</button>
            @endif
        </div>
    </div>
</div>
@endif
</div>

@push('styles')
<style>
    .onboarding-float {
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
</style>
@endpush
@push('styles')
<style>
    .fixed-left-onboarding {
        transition: all 0.3s ease;
    }
    .onboarding-collapsed {
        display: none
    }
    @media (max-width: 1399.98px) {
        .fixed-left-onboarding:not(.onboarding-collapsed):hover {
            transform: translateX(0);
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function toggleOnboardingPanel() {
        const card = document.getElementById('onboardingContent');
        card.classList.toggle('onboarding-collapsed');
        const badge = document.getElementById('onboardingbadge');
        badge.classList.toggle('onboarding-collapsed');
       
    }
</script>
@endpush