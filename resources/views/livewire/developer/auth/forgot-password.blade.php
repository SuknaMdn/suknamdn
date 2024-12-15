<div>

    <form class="form w-100" novalidate="novalidate" wire:submit.prevent="save">

        <!--begin::Heading-->
        <div class="text-center mb-11">
            {{-- logo --}}
            <img src="{{ asset($siteLogo) }}" alt="logo" width="100px" class="m-auto">
        </div>
        <!--begin::Heading-->

        <!--begin::Input group=-->
        <div class="fv-row mb-8">
            <!--begin::Email-->
            <input type="email" placeholder="Email" wire:model="email" class="form-control bg-transparent">
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
            <!--end::Email-->
        </div>

        <!--begin::Submit button-->
        <div class="d-grid mb-10">
            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                <!--begin::Indicator label-->
                <span class="indicator-label">Send Verification Link</span>
                <!--end::Indicator label-->
                <!--begin::Indicator progress-->
                <span class="indicator-progress" wire:loading>Please wait...<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                <!--end::Indicator progress-->
            </button>
        </div>
        <!--end::Submit button-->
    </form>

</div>
