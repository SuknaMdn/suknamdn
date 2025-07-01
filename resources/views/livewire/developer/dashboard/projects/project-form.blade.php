<div>
    <form wire:submit.prevent="save">
        <!--begin::Card-->
        <div class="card" dir="rtl">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <h3 class="fw-bold m-0">إعدادات المشروع</h3>
                    </div>
                </div>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body py-4">
                <!--begin::Form-->
                <div class="form-group row mb-8">
                    <!--begin::Project Basic Information-->
                    <div class="col-lg-12">
                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2 required">عنوان المشروع</label>
                            <input type="text" class="form-control form-control-solid mb-3 mb-lg-0" 
                                placeholder="أدخل عنوان المشروع" wire:model="title" />
                            @error('title') 
                                <div class="fv-plugins-message-container">
                                    <div class="fv-help-block">
                                        <span role="alert" class="text-danger">{{ $message }}</span>
                                    </div>
                                </div>
                            @enderror
                        </div>

                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">وصف المشروع</label>
                            <textarea name="project_description" class="form-control form-control-solid" 
                                    rows="3" placeholder="أدخل وصف مختصر للمشروع" wire:model.live="project.description"></textarea>
                            @error('project.description') 
                                <div class="fv-plugins-message-container">
                                    <div class="fv-help-block">
                                        <span role="alert" class="text-danger">{{ $message }}</span>
                                    </div>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <!--end::Project Basic Information-->
                </div>

                <!--begin::Separator-->
                <div class="separator separator-dashed my-10"></div>
                <!--end::Separator-->

                <!--begin::Payment Plan Toggle-->
                <div class="form-group row mb-8">
                    <div class="col-lg-12">
                        <div class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" value="" id="enables_payment_plan" 
                                wire:model.live="project.enables_payment_plan" />
                            <label class="form-check-label fw-semibold text-gray-800 me-3" for="enables_payment_plan">
                                تفعيل نظام البيع على الخارطة (خطط الدفع)
                            </label>
                        </div>
                        <div class="text-muted fs-7 mt-2">
                            تفعيل هذا الخيار سيمكنك من إنشاء جدول دفعات مرتبط بمراحل إنجاز المشروع
                        </div>
                    </div>
                </div>
                <!--end::Payment Plan Toggle-->

                <!--begin::Payment Plan Section (Conditional)-->
                @if ($project->enables_payment_plan)
                <div class="card border border-1 border-dashed border-primary">
                    <div class="card-body p-8">
                        <!--begin::Section Header-->
                        <div class="d-flex align-items-center mb-8">

                            <div class="flex-grow-1">
                                <h3 class="text-gray-800 fw-bold mb-1">معلومات البيع على الخارطة</h3>
                                <div class="text-muted fw-semibold fs-7">قم بإدخال تفاصيل المشروع وجدول الدفعات</div>
                            </div>
                        </div>
                        <!--end::Section Header-->

                        <!--begin::Project Details Row-->
                        <div class="row g-6 mb-8">
                            <div class="col-md-6">
                                <div class="fv-row">
                                    <label class="fw-semibold fs-6 mb-2 required">نسبة الإنجاز الحالية (%)</label>
                                    <div class="input-group input-group-solid">
                                        <input type="number" class="form-control form-control-solid" 
                                            placeholder="0" min="0" max="100" wire:model.live="project.completion_percentage" />
                                        <span class="input-group-text">%</span>
                                    </div>
                                    @error('project.completion_percentage') 
                                        <div class="fv-plugins-message-container">
                                            <div class="fv-help-block">
                                                <span role="alert" class="text-danger">{{ $message }}</span>
                                            </div>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="fv-row">
                                    <label class="fw-semibold fs-6 mb-2">مكتب التصميم المعماري</label>
                                    <input type="text" class="form-control form-control-solid" 
                                        placeholder="اسم مكتب التصميم" wire:model.live="project.architect_office_name" />
                                    @error('project.architect_office_name') 
                                        <div class="fv-plugins-message-container">
                                            <div class="fv-help-block">
                                                <span role="alert" class="text-danger">{{ $message }}</span>
                                            </div>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!--end::Project Details Row-->

                        <!--begin::Additional Info Row-->
                        <div class="row g-6 mb-8">
                            <div class="col-md-6">
                                <div class="fv-row">
                                    <label class="fw-semibold fs-6 mb-2">اسم المقاول</label>
                                    <input type="text" class="form-control form-control-solid" 
                                        placeholder="اسم الشركة المقاولة" wire:model.live="project.contractor_name" />
                                    @error('project.contractor_name') 
                                        <div class="fv-plugins-message-container">
                                            <div class="fv-help-block">
                                                <span role="alert" class="text-danger">{{ $message }}</span>
                                            </div>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="fv-row">
                                    <label class="fw-semibold fs-6 mb-2">المشرف على المشروع</label>
                                    <input type="text" class="form-control form-control-solid" 
                                        placeholder="اسم المشرف" wire:model.live="project.supervisor_name" />
                                    @error('project.supervisor_name') 
                                        <div class="fv-plugins-message-container">
                                            <div class="fv-help-block">
                                                <span role="alert" class="text-danger">{{ $message }}</span>
                                            </div>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!--end::Additional Info Row-->

                        <!--begin::Separator-->
                        <div class="separator separator-dashed my-8"></div>
                        <!--end::Separator-->

                        <!--begin::Payment Milestones-->
                        <div class="d-flex align-items-center justify-content-between mb-6">
                            <div class="d-flex align-items-center">
                                <h3 class="text-gray-800 fw-bold mb-0 me-3">جدول دفعات المشروع</h3>
                                <span class="badge badge-light-primary fs-7">{{ count($paymentMilestones) }} دفعة</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary " wire:click.prevent="addMilestoneRow" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="addMilestoneRow">
                                    <span>إضافة دفعة جديدة</span>
                                </span>
                                <span wire:loading wire:target="addMilestoneRow">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    جاري الإضافة...
                                </span>
                            </button>
                        </div>

                        @error('paymentMilestones') 
                            <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                                <!--begin::Svg Icon | path: icons/duotune/general/gen044.svg-->
                                <span class="svg-icon svg-icon-2hx svg-icon-danger me-4">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                        <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="currentColor" />
                                        <rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="currentColor" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                                <div class="d-flex flex-column">
                                    <h4 class="mb-1 text-danger">خطأ في البيانات</h4>
                                    <span>{{ $message }}</span>
                                </div>
                            </div>
                        @enderror

                        <!--begin::Milestones List-->
                        <div class="scroll-y mh-300px px-7 py-3">
                            @foreach ($paymentMilestones as $index => $milestone)
                                <div class="border border-gray-300 border-dashed rounded p-6 mb-6" wire:key="milestone-{{ $loop->index }}-{{ now()->timestamp }}">
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-35px symbol-circle me-4">
                                                <span class="symbol-label bg-light-primary text-primary fs-7 fw-bold">
                                                    {{ $index + 1 }}
                                                </span>
                                            </div>
                                            <h4 class="text-gray-800 fw-bold mb-0">الدفعة رقم {{ $index + 1 }}</h4>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-icon btn-light-danger" 
                                                wire:click.prevent="removeMilestoneRow({{ $index }})" 
                                                wire:loading.attr="disabled"
                                                wire:target="removeMilestoneRow"
                                                data-bs-toggle="tooltip" title="حذف الدفعة"
                                                wire:confirm="هل أنت متأكد من حذف هذه الدفعة؟">
                                            <span wire:loading.remove wire:target="removeMilestoneRow">
                                                <!--begin::Svg Icon | path: icons/duotune/general/gen027.svg-->
                                                <span class="svg-icon svg-icon-2">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor" />
                                                        <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor" />
                                                        <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                            </span>
                                            <span wire:loading wire:target="removeMilestoneRow">
                                                <span class="spinner-border spinner-border-sm"></span>
                                            </span>
                                        </button>
                                    </div>

                                    <div class="row g-4">
                                        <div class="col-md-5">
                                            <div class="fv-row">
                                                <label class="fw-semibold fs-6 mb-2 required">اسم الدفعة</label>
                                                <input type="text" class="form-control form-control-solid" 
                                                    placeholder="مثال: الدفعة الأولى" 
                                                    wire:model.live="paymentMilestones.{{ $index }}.name" />
                                                @error("paymentMilestones.{$index}.name") 
                                                    <div class="fv-plugins-message-container">
                                                        <div class="fv-help-block">
                                                            <span role="alert" class="text-danger">{{ $message }}</span>
                                                        </div>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="fv-row">
                                                <label class="fw-semibold fs-6 mb-2 required">النسبة</label>
                                                <div class="input-group input-group-solid">
                                                    <input type="number" class="form-control form-control-solid" 
                                                        placeholder="10" min="0" max="100"
                                                        wire:model.live="paymentMilestones.{{ $index }}.percentage" />
                                                    <span class="input-group-text">%</span>
                                                </div>
                                                @error("paymentMilestones.{$index}.percentage") 
                                                    <div class="fv-plugins-message-container">
                                                        <div class="fv-help-block">
                                                            <span role="alert" class="text-danger">{{ $message }}</span>
                                                        </div>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="fv-row">
                                                <label class="fw-semibold fs-6 mb-2">شرط الاستحقاق</label>
                                                <input type="text" class="form-control form-control-solid" 
                                                    placeholder="مثال: عند نسبة إنجاز 20%" 
                                                    wire:model.live="paymentMilestones.{{ $index }}.completion_milestone" />
                                                @error("paymentMilestones.{$index}.completion_milestone") 
                                                    <div class="fv-plugins-message-container">
                                                        <div class="fv-help-block">
                                                            <span role="alert" class="text-danger">{{ $message }}</span>
                                                        </div>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!--end::Milestones List-->

                        @if(empty($paymentMilestones))
                            <div class="card bg-light-info">
                                <div class="card-body text-center py-12">
                                    <!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
                                    <span class="svg-icon svg-icon-3x svg-icon-info mb-5">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.3" d="M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15ZM13 12H11C10.5 12 10 11.5 10 11V8C10 7.5 10.4 7 11 7H13C13.6 7 14 7.4 14 8V11C14 11.5 13.6 12 13 12Z" fill="currentColor"/>
                                            <path d="M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14ZM20 15H14V16C14 17.1 13.1 18 12 18C10.9 18 10 17.1 10 16V15H4C2.9 15 2 14.1 2 13V7C2 5.9 2.9 5 4 5H20C21.1 5 22 5.9 22 7V13C22 14.1 21.1 15 20 15Z" fill="currentColor"/>
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                    <h3 class="text-gray-800 fw-bold mb-3">لا توجد دفعات محددة</h3>
                                    <div class="text-gray-600 fw-semibold fs-6 mb-5">
                                        قم بإضافة دفعة جديدة لبدء إنشاء جدول الدفعات
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!--end::Payment Milestones-->
                    </div>
                </div>
                @endif
                <!--end::Payment Plan Section-->
            </div>
            <!--end::Card body-->

            <!--begin::Card footer-->
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <button type="reset" class="btn btn-light btn-active-light-primary me-2">إلغاء</button>
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save">
                    <span class="indicator-label" wire:loading.remove wire:target="save">
                        حفظ المشروع
                    </span>
                    <span class="indicator-progress" wire:loading wire:target="save">
                        يرجى الانتظار...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
            <!--end::Card footer-->
        </div>
        <!--end::Card-->
    </form>
</div>