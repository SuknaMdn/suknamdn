<div class="mb-10" dir="rtl">
    <div class="card-header">
        <h3 class="card-title text-end">تعديل مشروع</h3>
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
                                <div class="col-md-6">
                                    <label class="form-label text-end d-block">عنوان المشروع*</label>
                                    <input type="text" class="form-control text-end" wire:model.defer="title" required>
                                    @error('title') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-end d-block">الرابط المختصر*</label>
                                    <input type="text" class="form-control text-end" wire:model.defer="slug" readonly>
                                    @error('slug') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="mb-5">
                                <label class="form-label text-end d-block">الوصف</label>
                                <textarea class="form-control text-end" wire:model.defer="description" rows="5"></textarea>
                                @error('description') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                            </div>

                            <div class="row mb-5">
                                <div class="col-md-6">
                                    <label class="form-label text-end d-block">المطور*</label>
                                    <select class="form-select text-end" wire:model.defer="developer_id" required>
                                        <option value="">اختر المطور</option>
                                        @foreach($developers as $developer)
                                            <option value="{{ $developer->id }}">{{ $developer->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('developer_id') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-end d-block">نوع العقار*</label>
                                    <select class="form-select text-end" wire:model.defer="property_type_id" required>
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
                                <textarea class="form-control text-end" wire:model.defer="address" required rows="2"></textarea>
                                @error('address') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-5">
                                <label class="form-label text-end d-block">الموقع على الخريطة</label>
                                <div id="map" style="height: 300px;" wire:ignore></div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-end d-block">خط العرض*</label>
                                        <input type="text" class="form-control text-end" wire:model.live="latitude" required>
                                        @error('latitude') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-end d-block">خط الطول*</label>
                                        <input type="text" class="form-control text-end" wire:model.live="longitude" required>
                                        @error('longitude') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Plan -->
                    <div class="card  mb-5">
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
                                        <input type="number" class="form-control text-end" wire:model.defer="completion_percentage" min="0" max="100">
                                        @error('completion_percentage') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-end d-block">مكتب التصميم</label>
                                        <input type="text" class="form-control text-end" wire:model.defer="architect_office_name">
                                        @error('architect_office_name') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-end d-block">المشرف على البناء</label>
                                        <input type="text" class="form-control text-end" wire:model.defer="construction_supervisor_office">
                                        @error('construction_supervisor_office') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-end d-block">المقاول الرئيسي</label>
                                        <input type="text" class="form-control text-end" wire:model.defer="main_contractor">
                                        @error('main_contractor') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="text-end">الدفعات</h4>
                                        <button type="button" class="btn btn-sm btn-primary" wire:click="addPaymentMilestone">
                                            <i class="fas fa-plus"></i> إضافة دفعة
                                        </button>
                                    </div>
                                    
                                    @foreach($paymentMilestones as $index => $milestone)
                                        <div class="card  mb-3">
                                            <div class="card-header">
                                                <div class="card-title flex align-items-center justify-content-between w-100">
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
                                                        <input type="text" class="form-control text-end" wire:model.defer="paymentMilestones.{{ $index }}.name">
                                                        @error('paymentMilestones.'.$index.'.name') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label text-end d-block">النسبة %</label>
                                                        <input type="number" class="form-control text-end" wire:model.defer="paymentMilestones.{{ $index }}.percentage" min="1" max="100">
                                                        @error('paymentMilestones.'.$index.'.percentage') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label text-end d-block">شرط الإنجاز</label>
                                                        <input type="text" class="form-control text-end" wire:model.defer="paymentMilestones.{{ $index }}.completion_milestone">
                                                        @error('paymentMilestones.'.$index.'.completion_milestone') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info text-end">
                                    خطة الدفع غير مفعلة. يرجى تفعيلها لضبط مراحل الدفع.
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
                                    <input class="form-check-input" type="checkbox" wire:model.defer="is_active">
                                </div>
                                <div class="form-check form-switch mb-3 flex justify-content-between align-items-center">
                                    <label class="form-check-label">مشروع مميز</label>
                                    <input class="form-check-input" type="checkbox" wire:model.defer="is_featured">
                                </div>
                                <div class="mb-3" hidden>
                                    <label class="form-label text-end d-block">الغرض</label>
                                    <select class="form-select text-end" wire:model.defer="purpose">
                                        <option value="sale">بيع</option>
                                        <option value="rent">إيجار</option>
                                        <option value="invest">استثمار</option>
                                    </select>
                                    @error('purpose') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-end d-block">ترخيص الإعلان</label>
                                    <input type="text" class="form-control text-end" wire:model.defer="AdLicense">
                                    @error('AdLicense') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="mb-5">
                                <label class="form-label text-end d-block">صور المشروع</label>
                                <input type="file" class="form-control" wire:model.live="images" multiple>
                                @error('images') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                                
                                @if($existingImages || $images)
                                    <div class="mt-3">
                                        <div class="row g-3">
                                            @foreach($existingImages as $index => $image)
                                                <div class="col-6">
                                                    <div class="position-relative">
                                                        <img src="{{ Storage::disk('public')->url($image) }}" class="img-fluid rounded" alt="Preview">
                                                        <button type="button" class="btn btn-sm btn-icon btn-danger position-absolute top-0 start-0 m-1" style="width: 20px;height: 20px;" wire:click="removeImage({{ $index }})">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                            
                                            @foreach($images as $index => $image)
                                                <div class="col-6">
                                                    <div class="position-relative">
                                                        <img src="{{ $image->temporaryUrl() }}" class="img-fluid rounded" alt="Preview">
                                                        <button type="button" class="btn btn-sm btn-icon btn-danger position-absolute top-0 start-0 m-1" style="width: 20px;height: 20px;" wire:click="removeImage({{ $index + count($existingImages) }})">
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
                                <input type="text" class="form-control text-end" wire:model.defer="video" placeholder="https://youtube.com/...">
                                @error('video') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-5">
                                <label class="form-label text-end d-block">رابط الجولة الافتراضية</label>
                                <input type="text" class="form-control text-end" wire:model.defer="threedurl" placeholder="https://...">
                                @error('threedurl') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-5">
                                <label class="form-label text-end d-block">ملف PDF</label>
                                @if($mediaPDF)
                                    <div class="mb-2 text-end">
                                        <span class="badge badge-light-primary">{{ basename($mediaPDF) }}</span>
                                        <a href="{{ Storage::disk('public')->url($mediaPDF) }}" target="_blank" class="btn btn-sm btn-icon btn-primary me-1" style="width: 20px;height: 20px;">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                @endif
                                <input type="file" class="form-control" wire:model.live="newMediaPDF" accept=".pdf">
                                @error('newMediaPDF') <span class="text-danger d-block text-end">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer flex justify-content-start">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> حفظ التغييرات
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script>

document.addEventListener('DOMContentLoaded', function () {
    let map, marker;
    
    function initializeEditMap() {
        const mapElement = document.getElementById('map');
        if (!mapElement || mapElement.hasAttribute('data-map-initialized')) {
            return;
        }
        
        mapElement.setAttribute('data-map-initialized', 'true');
        
        // Get existing coordinates from the Livewire component
        let existingLat = @this.get('latitude');
        let existingLng = @this.get('longitude');
        
        // Fallback to default if no coordinates exist
        const defaultLat = 24.7136;
        const defaultLng = 46.6753;
        
        let currentLat = existingLat || defaultLat;
        let currentLng = existingLng || defaultLng;
        
        // Parse to numbers and validate
        currentLat = parseFloat(currentLat);
        currentLng = parseFloat(currentLng);
        
        // Validate coordinates
        if (isNaN(currentLat) || isNaN(currentLng)) {
            currentLat = defaultLat;
            currentLng = defaultLng;
        }
        
        console.log('Initializing map with coordinates:', currentLat, currentLng);
        
        // Initialize map with existing coordinates
        map = L.map(mapElement).setView([currentLat, currentLng], 15);
        
        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        // Add marker at existing position
        marker = L.marker([currentLat, currentLng], { 
            draggable: true 
        }).addTo(map);
        
        // Update coordinates function - using the Livewire method
        function updateCoordinates(lat, lng) {
            const roundedLat = parseFloat(lat.toFixed(6));
            const roundedLng = parseFloat(lng.toFixed(6));
            
            console.log('Updating coordinates via Livewire method:', roundedLat, roundedLng);
            
            // Call the Livewire method
            @this.call('updateMapCoordinates', roundedLat, roundedLng);
        }
        
        // Handle marker drag
        marker.on('dragend', function (e) {
            const position = marker.getLatLng();
            updateCoordinates(position.lat, position.lng);
        });
        
        // Handle map click
        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            updateCoordinates(e.latlng.lat, e.latlng.lng);
        });
        
        console.log('Map initialized for EDIT page');
    }
    
    // Initialize map with a delay to ensure Livewire is ready
    setTimeout(initializeEditMap, 100);
    
    // Listen for coordinate changes from input fields
    document.addEventListener('livewire:updated', function() {
        if (map && marker) {
            const lat = @this.get('latitude');
            const lng = @this.get('longitude');
            
            if (lat && lng && !isNaN(parseFloat(lat)) && !isNaN(parseFloat(lng))) {
                const currentPos = marker.getLatLng();
                const newLat = parseFloat(lat);
                const newLng = parseFloat(lng);
                
                // Only update if coordinates actually changed significantly
                if (Math.abs(currentPos.lat - newLat) > 0.00001 || 
                    Math.abs(currentPos.lng - newLng) > 0.00001) {
                    marker.setLatLng([newLat, newLng]);
                    map.setView([newLat, newLng], map.getZoom());
                    console.log('Map marker updated from input fields:', newLat, newLng);
                }
            }
        }
    });
    
    // Handle custom Livewire events (if needed)
    Livewire.on('updateMapCoordinates', function(data) {
        if (map && marker && data.lat && data.lng) {
            const lat = parseFloat(data.lat);
            const lng = parseFloat(data.lng);
            
            if (!isNaN(lat) && !isNaN(lng)) {
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng], 15);
                console.log('Map updated via Livewire event:', lat, lng);
            }
        }
    });
});

// Fallback for older Livewire versions
document.addEventListener('livewire:load', function() {
    setTimeout(() => {
        const mapElement = document.getElementById('map');
        if (mapElement && !mapElement.hasAttribute('data-map-initialized')) {
            document.dispatchEvent(new Event('DOMContentLoaded'));
        }
    }, 200);
});

</script>
@endpush