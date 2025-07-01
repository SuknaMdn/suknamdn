
<div class="mb-10" dir="rtl">
    <div class="card-header">
        <h3 class="card-title text-end">إنشاء مشروع جديد</h3>
    </div>
    @if($errors->has('server'))
        <div class="alert alert-danger">
            {{ $errors->first('server') }}
        </div>
    @endif
    <form wire:submit.prevent="submit" class="form">
        <div class="card-body fs-6 py-15 text-gray-700">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Basic Information -->
                    <div class="card  mb-5">
                        <div class="card-header">
                            <h3 class="card-title text-end">المعلومات الأساسية</h3>
                        </div>
                        <div class="card-body">
                            <div class="row mb-5">
                                <div class="col-md-12">
                                    <label class="form-label text-end d-block">عنوان المشروع*</label>
                                    <input type="text" class="form-control text-end" wire:model.live="title" required>
                                    @error('title') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                </div>
                                {{-- <div class="col-md-6">
                                    <label class="form-label text-end d-block">الرابط المختصر*</label>
                                    <input type="text" class="form-control text-end" wire:model.live="slug" readonly>
                                    @error('slug') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                </div> --}}
                            </div>

                            <div class="mb-5">
                                <label class="form-label text-end d-block">الوصف</label>
                                <textarea class="form-control text-end" wire:model.live="description" rows="5"></textarea>
                                @error('description') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                            </div>

                            <div class="row mb-5">
                                <div class="col-md-6">
                                    <label class="form-label text-end d-block">المطور*</label>
                                    {{ auth()->user()->developer->name }}
                                    @if(auth()->user()->isDeveloper())
                                        <span class="badge badge-light-success">مطور</span>
                                    @else
                                        <span class="badge badge-light-danger">ليس مطوراً</span>
                                    @endif
                                    {{-- <select class="form-select text-end" wire:model.live="developer_id" required>
                                        <option value="">اختر المطور</option>
                                        @foreach($developers as $developer)
                                            <option value="{{ $developer->id }}">{{ $developer->name }}</option>
                                        @endforeach
                                    </select> --}}
                                    @error('developer_id') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-end d-block">نوع العقار*</label>
                                    <select class="form-select text-end" wire:model.live="property_type_id" required>
                                        <option value="">اختر نوع العقار</option>
                                        @foreach($propertyTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('property_type_id') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location Information -->
                    <div class="card mb-5">
                        <div class="card-header">
                            <h3 class="card-title text-end">معلومات الموقع</h3>
                        </div>
                        <div class="card-body">
                            <div class="row mb-5">
                                <div class="col-md-6">
                                    <label class="form-label text-end d-block">المدينة*</label>
                                    <select class="form-select text-end" wire:model.live="city_id" required>
                                        <option value="">اختر المدينة</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('city_id') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-end d-block">المنطقة*</label>
                                    <select class="form-select text-end" wire:model.live="state_id" required>
                                        <option value="">اختر المنطقة</option>
                                        @foreach($stateOptions as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('state_id') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="mb-5">
                                <label class="form-label text-end d-block">العنوان*</label>
                                <textarea class="form-control text-end" wire:model.live="address" required rows="2"></textarea>
                                @error('address') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                            </div>

                            <div class="">
                                <label class="form-label text-end d-block">الموقع على الخريطة</label>
                                <div id="map" style="height: 300px;" wire:ignore></div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Plan -->
                    <div class="card mb-5">
                        @if($errors->has('payment'))
                            <div class="alert alert-danger">{{ $errors->first('payment') }}</div>
                        @endif
                        <div class="card-header justify-content-between align-items-center">
                            <h3 class="">خطة الدفع</h3>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" wire:model.live="enables_payment_plan">
                                <label class="form-check-label">تفعيل خطة الدفع</label>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($enables_payment_plan)
                                <div class="row mb-5">
                                    <div class="col-md-3">
                                        <label class="form-label text-end d-block">نسبة الإنجاز %</label>
                                        <input type="number" class="form-control text-end" wire:model.live="completion_percentage" min="0" max="100">
                                        @error('completion_percentage') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-end d-block">مكتب التصميم</label>
                                        <input type="text" class="form-control text-end" wire:model.live="architect_office_name">
                                        @error('architect_office_name') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-end d-block">المشرف على البناء</label>
                                        <input type="text" class="form-control text-end" wire:model.live="construction_supervisor_office">
                                        @error('construction_supervisor_office') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-end d-block">المقاول الرئيسي</label>
                                        <input type="text" class="form-control text-end" wire:model.live="main_contractor">
                                        @error('main_contractor') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="mb-5">
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="text-end">الدفعات</h4>
                                        <button type="button" class="btn btn-sm btn-primary" wire:click="addPaymentMilestone">
                                            <i class="fas fa-plus p-0 ms-2"></i> إضافة دفعة
                                        </button>
                                    </div>
                                    
                                    @foreach($paymentMilestones as $index => $milestone)
                                        <div class="card mb-3">
                                            <div class="card-header">
                                                <div class="card-title d-flex align-items-center justify-content-between w-100">
                                                    <h5 class="ms-5">دفعة #{{ $index + 1 }}</h5>
                                                    @if($index > 0)
                                                        <button type="button" class="btn btn-sm btn-danger" wire:click="removePaymentMilestone({{ $index }})">
                                                            <i class="fas fa-trash p-0"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label class="form-label text-end d-block">الاسم</label>
                                                        <input type="text" class="form-control text-end" wire:model.live="paymentMilestones.{{ $index }}.name">
                                                        @error('paymentMilestones.'.$index.'.name') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label text-end d-block">النسبة %</label>
                                                        <input type="number" class="form-control text-end" wire:model.live="paymentMilestones.{{ $index }}.percentage" min="1" max="100">
                                                        @error('paymentMilestones.'.$index.'.percentage') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label text-end d-block">شرط الإنجاز</label>
                                                        <input type="text" class="form-control text-end" wire:model.live="paymentMilestones.{{ $index }}.completion_milestone">
                                                        @error('paymentMilestones.'.$index.'.completion_milestone') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info text-end mb-0">
                                    خطة الدفع غير مفعلة. يرجى تفعيلها لضبط الدفعات.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Left Column -->
                <div class="col-lg-4">
                    <!-- Status & Media -->
                    <div class="card  mb-5">
                        <div class="card-header">
                            <h3 class="card-title text-end">الحالة والوسائط</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-5">
                                <div class="form-check form-switch mb-3 flex justify-content-between align-items-center">
                                    <label class="form-check-label">المشروع نشط</label>
                                    <input class="form-check-input" type="checkbox" wire:model.live="is_active">
                                </div>
                                <div class="form-check form-switch mb-5 flex justify-content-between align-items-center">
                                    <label class="form-check-label">مشروع مميز</label>
                                    <input class="form-check-input" type="checkbox" wire:model.live="is_featured">
                                </div>
                                <div class="mb-3" hidden>
                                    <label class="form-label text-end">الغرض</label>
                                    <select class="form-select text-end" wire:model.live="purpose">
                                        <option value="sale">بيع</option>
                                        <option value="rent">إيجار</option>
                                        <option value="invest">استثمار</option>
                                    </select>
                                    @error('purpose') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-end d-block">ترخيص الإعلان</label>
                                    <input type="text" class="form-control text-end" wire:model.live="AdLicense">
                                    @error('AdLicense') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="mb-5">
                                <label class="form-label text-end d-block">صور المشروع</label>
                                <input type="file" class="form-control" wire:model.live="images" multiple>
                                @error('images') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                
                                @if($images)
                                    <div class="mt-3">
                                        <div class="row g-3">
                                            @foreach($images as $index => $image)
                                                <div class="col-6">
                                                    <div class="position-relative">
                                                        <img src="{{ $image->temporaryUrl() }}" class="img-fluid rounded" alt="Preview">
                                                        <button type="button" class="btn btn-sm btn-icon btn-danger position-absolute top-0 start-0 m-1" wire:click="removeImage({{ $index }})">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-5">
                                <label class="form-label text-end d-block">رابط الفيديو</label>
                                <input type="text" class="form-control text-end" wire:model.live="video" placeholder="https://youtube.com/...">
                                @error('video') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-5">
                                <label class="form-label text-end d-block">رابط الجولة الافتراضية</label>
                                <input type="text" class="form-control text-end" wire:model.live="threedurl" placeholder="https://...">
                                @error('threedurl') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                            </div>

                            <div class="">
                                <label class="form-label text-end d-block">ملف PDF</label>
                                <input type="file" class="form-control" wire:model.live="mediaPDF" accept=".pdf">
                                @error('mediaPDF') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                @if($mediaPDF)
                                    <div class="mt-2 text-end">
                                        <span class="badge badge-light-primary">{{ $mediaPDF->getClientOriginalName() }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer flex justify-content-start">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save p-0 ms-2"></i> حفظ المشروع
            </button>
        </div>
    </form>
</div>
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

<script>
// Updated script for Livewire 3.x compatibility
document.addEventListener('DOMContentLoaded', function () {
    // Wait for the map container to be available
    const mapContainer = document.getElementById('map');
    if (!mapContainer) {
        console.error('Map container not found');
        return;
    }

    // Initialize the map
    const map = L.map('map').setView([24.7136, 46.6753], 12);
    
    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Add draggable marker
    let marker = L.marker([24.7136, 46.6753], {draggable: true}).addTo(map);

    // Handle marker drag
    marker.on('dragend', function (e) {
        const { lat, lng } = marker.getLatLng();
        console.log('Marker dragged to:', lat, lng);
        
        // Update Livewire properties
        @this.set('latitude', lat.toFixed(6));
        @this.set('longitude', lng.toFixed(6));
    });

    // Handle map click
    map.on('click', function (e) {
        const { lat, lng } = e.latlng;
        console.log('Map clicked at:', lat, lng);
        
        // Move marker to clicked location
        marker.setLatLng([lat, lng]);
        
        // Update Livewire properties
        @this.set('latitude', lat.toFixed(6));
        @this.set('longitude', lng.toFixed(6));
    });

    // Listen for Livewire events to update map
    Livewire.on('setMapLocation', (event) => {
        const { lat, lng } = event;
        if (lat && lng) {
            marker.setLatLng([lat, lng]);
            map.setView([lat, lng], 15);
        }
    });

    // Optional: Listen for coordinate changes from input fields
    document.addEventListener('livewire:updated', function () {
        const latInput = document.querySelector('[wire\\:model\\.live="latitude"]');
        const lngInput = document.querySelector('[wire\\:model\\.live="longitude"]');
        
        if (latInput && lngInput) {
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);
            
            if (!isNaN(lat) && !isNaN(lng)) {
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng], map.getZoom());
            }
        }
    });
});

// Alternative approach for older Livewire versions
document.addEventListener('livewire:load', function () {
    // This is for Livewire 2.x compatibility
    if (typeof Livewire !== 'undefined' && !document.getElementById('map-initialized')) {
        // Add a flag to prevent double initialization
        const mapContainer = document.getElementById('map');
        if (mapContainer) {
            mapContainer.setAttribute('id', 'map-initialized');
        }
    }
});
</script>
@endpush