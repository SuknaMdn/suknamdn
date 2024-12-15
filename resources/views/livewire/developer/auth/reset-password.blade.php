<div>
    <form class="form w-100" novalidate="novalidate" wire:submit.prevent="save">
        <!--begin::Heading-->
        <div class="text-center mb-11">
            {{-- logo --}}
            <img src="{{ asset($siteLogo) }}" alt="logo" width="100px" class="m-auto">
        </div>
        <!--begin::Heading-->

        <!-- Hidden Token Field -->
        <input type="hidden" wire:model="token">

        <!--begin::Input group-->
        <div class="fv-row mb-3">
            <input type="email" placeholder="Email" readonly wire:model="email" class="form-control bg-transparent" required>
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!--begin::Input group-->
        <div class="fv-row mb-3">
            <input type="password" placeholder="Password" wire:model="password" class="form-control bg-transparent" required>
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!--begin::Input group-->
        <div class="fv-row mb-3">
            <input type="password" placeholder="Confirm Password" wire:model="password_confirmation" class="form-control bg-transparent" required>
            @error('password_confirmation')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!--begin::Submit button-->
        <div class="d-grid mb-10">
            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                <!--begin::Indicator label-->
                <span class="indicator-label">Reset Password</span>
                <!--end::Indicator label-->
                <!--begin::Indicator progress-->
                <span class="indicator-progress" wire:loading>Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
                <!--end::Indicator progress-->
            </button>
        </div>
        <!--end::Submit button-->

        <!--begin::Sign up-->
        <div class="text-gray-500 text-center fw-semibold fs-6">
            <a href="{{ route('login') }}" class="link-primary">Back to Login</a>
        </div>
        <!--end::Sign up-->
    </form>
</div>
