<div dir="rtl">

    <form class="form" novalidate="novalidate" wire:submit.prevent="login">

        <!-- العنوان -->
        <div class="text-center mb-11">
            {{-- الشعار --}}
            <img src="{{ asset('storage/' . $siteLogo) }}" alt="الشعار" width="100px" class="m-auto">
        </div>

        @if (session()->has('error'))
            <div class="alert alert-danger text-center mb-3 mt-3">
                {{ session('error') }}
            </div>
        @endif

        <!-- البريد الإلكتروني -->
        <div class="fv-row mb-8">
            <input type="email" placeholder="البريد الإلكتروني" wire:model="email" class="form-control bg-transparent text-end">
            @error('email')
                <span class="text-danger d-block text-end">{{ $message }}</span>
            @enderror
        </div>

        <!-- كلمة المرور -->
        <div class="fv-row mb-3">
            <input type="password" placeholder="كلمة المرور" wire:model="password" class="form-control bg-transparent text-end">
            @error('password')
                <span class="text-danger d-block text-end">{{ $message }}</span>
            @enderror
        </div>

        <!-- تذكّرني + نسيت كلمة المرور -->
        <div class="col-12">
            <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8 justify-content-between">
                <label class="form-check form-check-custom form-check-inline form-check-solid ms-5">
                    <input class="form-check-input" type="checkbox" id="remember" wire:model="remember" value="1" />
                    <span class="fw-semibold pe-2 fs-6">تذكرني</span>
                </label>
                <a href="{{ route('developer.forgot-password') }}" class="text-primary fw-bold">نسيت كلمة المرور؟</a>
            </div>
        </div>

        <!-- زر تسجيل الدخول -->
        <div class="d-grid mb-10">
            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                <span class="indicator-label">تسجيل الدخول</span>
                <span class="indicator-progress" wire:loading>
                    يرجى الانتظار...
                    <span class="spinner-border spinner-border-sm align-middle me-2"></span>
                </span>
            </button>
        </div>
        
    </form>

</div>
