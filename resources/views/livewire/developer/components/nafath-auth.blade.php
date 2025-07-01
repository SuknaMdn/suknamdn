<div>
    @if($showPopup && ($isRequired || !$user->national_id))
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show d-block" dir="rtl" tabindex="-1" wire:key="nafath-popup-{{ $user->id }}"
            @if(!$isRequired) @click.self="$wire.hide()" @endif>
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <!-- Close button (only if not required) -->
                    @if(!$isRequired)
                        <div class="modal-header">
                            <div class="btn btn-sm btn-icon btn-active-color-primary" wire:click="hide">
                                <i class="ki-outline ki-cross fs-1"></i>
                            </div>
                        </div>
                    @endif
                        
                    <div class="modal-body py-10 px-lg-17">
                        <div class="text-center mb-13">
                            <!-- Nafath Logo -->
                            <div class="symbol symbol-70px symbol-circle mb-5">
                                <div class="symbol-label bg-success">
                                    <i class="ki-outline ki-shield-tick fs-2x text-white"></i>
                                </div>
                            </div>
                            <h2 class="fw-bold text-success mb-3">{{ $title }}</h2>
                        </div>

                        <!-- Success Message -->
                        @if(session('nafath-success'))
                            <div class="alert alert-dismissible bg-light-success d-flex flex-column flex-sm-row p-5 mb-10">
                                <i class="ki-outline ki-check-circle fs-2hx text-success ms-4 mb-5 mb-sm-0"></i>
                                <div class="d-flex flex-column">
                                    <h4 class="fw-semibold">تم بنجاح</h4>
                                    <span>{{ session('nafath-success') }}</span>
                                </div>
                            </div>
                        @endif

                        <!-- Error Messages -->
                        @error('nafath')
                            <div class="alert alert-dismissible bg-light-danger d-flex flex-column flex-sm-row p-5 mb-10">
                                <i class="ki-outline ki-cross-circle fs-2hx text-danger ms-4 mb-5 mb-sm-0"></i>
                                <div class="d-flex flex-column">
                                    <h4 class="fw-semibold">خطأ</h4>
                                    <span>{{ $message }}</span>
                                </div>
                            </div>
                        @enderror

                        @if($nafathStatus === 'PENDING')
                            <div class="mb-10">
                                <p class="text-gray-700 mb-6 text-center">{{ $description }}</p>
                                
                                <div class="mb-6">
                                    <label class="form-label fs-6 fw-semibold text-gray-700 mb-2 text-right">رقم الهوية الوطنية</label>
                                    <input 
                                        type="text" 
                                        wire:model="nationalId"
                                        class="form-control form-control-solid text-center"
                                        placeholder="أدخل رقم الهوية الوطنية"
                                        maxlength="10"
                                        pattern="[0-9]{10}"
                                        dir="ltr"
                                    >
                                    @error('nationalId')
                                        <div class="fv-plugins-message-container invalid-feedback">
                                            <div data-field="nationalId">{{ $message }}</div>
                                        </div>
                                    @enderror
                                </div>
                                
                                <button 
                                    wire:click="initiateNafathAuth"
                                    wire:loading.attr="disabled"
                                    class="btn btn-success w-100 "
                                >
                                    <span wire:loading.remove wire:target="initiateNafathAuth">بدء التحقق بنفاذ</span>
                                    <span wire:loading wire:target="initiateNafathAuth">
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        جاري التحقق...
                                    </span>
                                </button>
                            </div>

                        @elseif($nafathStatus === 'WAITING')
                            <div class="mb-10 text-center">
                                <div class="spinner spinner-primary spinner-lg mb-6"></div>
                                <p class="text-gray-700 mb-6">يرجى فتح تطبيق نفاذ على هاتفك والموافقة على طلب التحقق</p>
                                
                                {{-- @if($nafathRequestId)
                                    <div class="alert alert-primary mb-6">
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-800 fw-semibold">رقم الطلب:</span>
                                            <span class="text-gray-600">{{ $nafathRequestId }}</span>
                                        </div>
                                    </div>
                                @endif
                                 --}}
                                
                                @if($random)
                                    <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-center h-xl-100 bg-success">
                                        <div class="card-body text-center m-auto">
                                            <div class="d-flex align-items-center">
                                                <span class="fs-4hx text-white fw-bold pb-0">{{ $random }}</span>           
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <p class="text-gray-600 my-4">جاري انتظار الموافقة...</p>
                                <div class="d-flex gap-3">
                                    <button 
                                        wire:click="resetNafath"
                                        class="btn btn-light btn-flex flex-center flex-grow-1"
                                    >
                                        <i class="ki-outline ki-cross fs-2 ms-2"></i> إلغاء
                                    </button>
                                </div>
                            </div>

                        @elseif($nafathStatus === 'COMPLETED')
                            <div class="mb-10 text-center">
                                <i class="ki-outline ki-check-circle fs-4x text-success mb-6"></i>
                                <p class="fw-semibold text-gray-700 mb-6">تم التحقق من الهوية بنجاح!</p>
                                <button 
                                    wire:click="hide"
                                    class="btn btn-success w-100"
                                >
                                    <i class="ki-outline ki-arrow-right fs-2 me-2"></i> متابعة
                                </button>
                            </div>

                        @elseif($nafathStatus === 'REJECTED' || $nafathStatus === 'EXPIRED')
                            <div class="mb-10 text-center">
                                <i class="ki-outline ki-cross-circle fs-4x text-danger mb-6"></i>
                                <p class="fw-semibold text-gray-700 mb-6">
                                    {{ $nafathStatus === 'EXPIRED' ? 'انتهت صلاحية طلب التحقق' : 'فشل في التحقق من الهوية' }}
                                </p>
                                <div class="d-flex gap-3">
                                    <button 
                                        wire:click="resetNafath"
                                        class="btn btn-success btn-flex flex-center flex-grow-1"
                                    >
                                        <i class="ki-outline ki-reload fs-2 me-2"></i> إعادة المحاولة
                                    </button>
                                    @if(!$isRequired)
                                        <button 
                                            wire:click="hide"
                                            class="btn btn-light btn-flex flex-center flex-grow-1"
                                        >
                                            <i class="ki-outline ki-cross fs-2 me-2"></i> إغلاق
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Footer info -->
                        <div class="text-center mt-10">
                            <span class="text-gray-500 fw-semibold">خدمة التحقق من الهوية الوطنية الموحدة</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@script
<script>
    let statusCheckInterval;

    $wire.on('start-nafath-status-check', () => {
        // Clear any existing interval
        if (statusCheckInterval) {
            clearInterval(statusCheckInterval);
        }
        
        // Start checking status every 3 seconds
        statusCheckInterval = setInterval(() => {
            $wire.checkNafathStatus();
        }, 3000);
    });

    $wire.on('stop-nafath-status-check', () => {
        if (statusCheckInterval) {
            clearInterval(statusCheckInterval);
            statusCheckInterval = null;
        }
    });

    // Clean up interval when component is destroyed
    document.addEventListener('livewire:navigating', () => {
        if (statusCheckInterval) {
            clearInterval(statusCheckInterval);
            statusCheckInterval = null;
        }
    });
</script>
@endscript