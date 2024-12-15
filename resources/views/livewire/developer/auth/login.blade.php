<div>

    <form class="form w-100" novalidate="novalidate" wire:submit.prevent="login">

        <!--begin::Heading-->
        <div class="text-center mb-11">
            {{-- logo --}}
            <img src="{{ asset($siteLogo) }}" alt="logo" width="100px" class="m-auto">
        </div>
        <!--begin::Heading-->
        @if (session()->has('error'))
            <div class="alert alert-danger text-center mb-3 mt-3">
                {{ session('error') }}
            </div>
        @endif
        <!--begin::Input group=-->
        <div class="fv-row mb-8">
            <!--begin::Email-->
            <input type="email" placeholder="Email" wire:model="email" class="form-control bg-transparent">
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
            <!--end::Email-->
        </div>
        <!--end::Input group=-->
        <div class="fv-row mb-3">
            <!--begin::Password-->
            <input type="password" placeholder="Password" wire:model="password" class="form-control bg-transparent">
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
            <!--end::Password-->
        </div>
        <!--end::Input group=-->
        <div class="col-12">
            <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">

                <label class="form-check form-check-custom form-check-inline form-check-solid me-5">
                    <input class="form-check-input" type="checkbox" id="remember" wire:model="remember" value="1" />
                    <span class="fw-semibold ps-2 fs-6">remember me</span>
                </label>
                <a href="{{ route('developer.forgot-password') }}">forgot password</a>
            </div> <!-- /.agreement-checkbox -->
        </div>

        <!--begin::Submit button-->
        <div class="d-grid mb-10">
            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                <!--begin::Indicator label-->
                <span class="indicator-label">Sign In</span>
                <!--end::Indicator label-->
                <!--begin::Indicator progress-->
                <span class="indicator-progress" wire:loading>Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                <!--end::Indicator progress-->
            </button>
        </div>
        <!--end::Submit button-->
        <!--begin::Sign up-->
        <div class="text-gray-500 text-center fw-semibold fs-6">Not a Member yet?
        <!--end::Sign up-->
    </form>

</div>
