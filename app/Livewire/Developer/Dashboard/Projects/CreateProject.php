<?php

namespace App\Livewire\Developer\Dashboard\Projects;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Project;
use App\Models\Developer;
use App\Models\City;
use App\Models\ProjectType;
use App\Models\State;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;

#[Title('إضافة مشروع جديد')]

class CreateProject extends Component
{

    use WithFileUploads;
    use LivewireAlert;
    public $title;
    public $slug;
    public $description;
    public $developer_id;
    public $property_type_id;
    public $is_active = true;
    public $is_featured = false;
    public $purpose = 'sale';
    public $video;
    public $city_id;
    public $state_id;
    public $area_range_from;
    public $area_range_to;
    public $building_style;
    public $address;
    public $images = [];
    public $threedurl;
    public $mediaPDF;
    public $AdLicense;
    public $latitude = 24.7136;
    public $longitude = 46.6753;
    
    // Payment plan fields
    public $enables_payment_plan = false;
    public $completion_percentage = 0;
    public $architect_office_name;
    public $construction_supervisor_office;
    public $main_contractor;
    public $istisna_contract_details;
    
    // Payment milestones
    public $paymentMilestones = [];
    
    // For dependent dropdowns
    public $stateOptions = [];

    protected $rules = [
        'title' => 'required|string|max:255',
        'slug' => 'required|string|unique:projects,slug',
        'description' => 'nullable|string',
        'property_type_id' => 'required|exists:project_types,id',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'purpose' => 'required|in:sale,rent,invest',
        'video' => 'nullable|url',
        'city_id' => 'required|exists:cities,id',
        'state_id' => 'required|exists:states,id',
        'area_range_from' => 'nullable|numeric',
        'area_range_to' => 'nullable|numeric',
        'building_style' => 'nullable|string|max:255',
        'address' => 'required|string|max:255',
        'images.*' => 'image|max:2048',
        'threedurl' => 'nullable|url',
        'mediaPDF' => 'nullable|file|mimes:pdf|max:5120',
        'AdLicense' => 'nullable|string|max:255',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
        
        // Payment plan validation
        'enables_payment_plan' => 'boolean',
        'completion_percentage' => 'nullable|numeric|min:0|max:100',
        'architect_office_name' => 'nullable|string|max:255',
        'construction_supervisor_office' => 'nullable|string|max:255',
        'main_contractor' => 'nullable|string|max:255',
        'istisna_contract_details' => 'nullable|string',

        'paymentMilestones.*.name' => 'required_if:enables_payment_plan,1|string|max:255',
        'paymentMilestones.*.percentage' => 'required_if:enables_payment_plan,1|numeric|min:1|max:100',
        'paymentMilestones.*.completion_milestone' => 'required_if:enables_payment_plan,1|string|max:255',

    ];

    public function mount()
    {
        // Initialize with one empty payment milestone
        $this->paymentMilestones = [
            ['name' => '', 'percentage' => '', 'completion_milestone' => '']
        ];
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'title') {
            $this->slug = Str::slug($this->title);
        }
        
        if ($propertyName === 'city_id') {
            $this->stateOptions = State::where('city_id', $this->city_id)->pluck('name', 'id')->toArray();
            $this->state_id = null;
        }
    }

    public function addPaymentMilestone()
    {
        $this->paymentMilestones[] = [
            'name' => '',
            'percentage' => '',
            'completion_milestone' => ''
        ];
    }

    public function removePaymentMilestone($index)
    {
        unset($this->paymentMilestones[$index]);
        $this->paymentMilestones = array_values($this->paymentMilestones);
    }

    public function removeImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images);
    }

    public function updateMapCoordinates($lat, $lng)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
    }

    public function submit()
    {
        try {
            $this->validate();

            // تحقق يدوي لخطة الدفع
            if ($this->enables_payment_plan) {
                if (
                    $this->completion_percentage === null ||
                    empty($this->architect_office_name) ||
                    empty($this->construction_supervisor_office) ||
                    empty($this->main_contractor)
                ) {
                    // إضافة خطأ مخصص إذا كانت الحقول المطلوبة لخطة الدفع فارغة
                    $this->alert('error', 'جميع الحقول الخاصة بخطة الدفع مطلوبة.');
                    $this->addError('payment', 'جميع الحقول الخاصة بخطة الدفع مطلوبة.');
                    return;
                }

                foreach ($this->paymentMilestones as $index => $milestone) {
                    if (
                        empty($milestone['name']) ||
                        empty($milestone['percentage']) ||
                        empty($milestone['completion_milestone'])
                    ) {
                        $this->addError("paymentMilestones.$index.name", 'تفاصيل مرحلة الدفع مطلوبة.');
                        return;
                    }
                }
            }

            // رفع الصور
            $imagePaths = [];
            foreach ($this->images as $image) {
                $imagePaths[] = $image->store('projects', 'public');
            }

            $pdfPath = $this->mediaPDF ? $this->mediaPDF->store('projects/pdf', 'public') : null;

            // Create the project
            $project = Project::create([
                'title' => $this->title,
                'slug' => $this->slug,
                'description' => $this->description,
                'developer_id' => auth()->user()->isDeveloper() ?auth()->user()->developer->id : $this->developer_id,
                'property_type_id' => $this->property_type_id,
                'is_active' => $this->is_active,
                'is_featured' => $this->is_featured,
                'purpose' => $this->purpose,
                'video' => $this->video,
                'city_id' => $this->city_id,
                'state_id' => $this->state_id,
                'area_range_from' => $this->area_range_from,
                'area_range_to' => $this->area_range_to,
                'building_style' => $this->building_style,
                'address' => $this->address,
                'images' => $imagePaths,
                'threedurl' => $this->threedurl,
                'mediaPDF' => $pdfPath,
                'AdLicense' => $this->AdLicense,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'user_id' => auth()->id(),
                
                // Payment plan fields
                'enables_payment_plan' => $this->enables_payment_plan,
                'completion_percentage' => $this->completion_percentage,
                'architect_office_name' => $this->architect_office_name,
                'construction_supervisor_office' => $this->construction_supervisor_office,
                'main_contractor' => $this->main_contractor,
                'istisna_contract_details' => $this->istisna_contract_details,
            ]);

            if ($this->enables_payment_plan && !empty($this->paymentMilestones)) {
                foreach ($this->paymentMilestones as $i => $milestone) {
                    $project->paymentMilestones()->create([
                        'name' => $milestone['name'],
                        'percentage' => $milestone['percentage'],
                        'completion_milestone' => $milestone['completion_milestone'],
                        'order' => $i + 1,
                    ]);
                }
            }

            $this->alert('success', 'تم إنشاء المشروع بنجاح.');

            session()->flash('message', 'تم إنشاء المشروع بنجاح');
            return redirect()->route('developer.projects');
        } catch (\Throwable $e) {
            $this->addError('server', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    // public function submit()
    // {
    //     $this->validate();

    //     // Handle file uploads
    //     $imagePaths = [];
    //     foreach ($this->images as $image) {
    //         $imagePaths[] = $image->store('projects', 'public');
    //     }

    //     $pdfPath = $this->mediaPDF ? $this->mediaPDF->store('projects/pdf', 'public') : null;

    //     // Create the project
    //     $project = Project::create([
    //         'title' => $this->title,
    //         'slug' => $this->slug,
    //         'description' => $this->description,
    //         'developer_id' => auth()->user()->isDeveloper() ?auth()->user()->developer->id : $this->developer_id,
    //         'property_type_id' => $this->property_type_id,
    //         'is_active' => $this->is_active,
    //         'is_featured' => $this->is_featured,
    //         'purpose' => $this->purpose,
    //         'video' => $this->video,
    //         'city_id' => $this->city_id,
    //         'state_id' => $this->state_id,
    //         'area_range_from' => $this->area_range_from,
    //         'area_range_to' => $this->area_range_to,
    //         'building_style' => $this->building_style,
    //         'address' => $this->address,
    //         'images' => $imagePaths,
    //         'threedurl' => $this->threedurl,
    //         'mediaPDF' => $pdfPath,
    //         'AdLicense' => $this->AdLicense,
    //         'latitude' => $this->latitude,
    //         'longitude' => $this->longitude,
    //         'user_id' => auth()->id(),
            
    //         // Payment plan fields
    //         'enables_payment_plan' => $this->enables_payment_plan,
    //         'completion_percentage' => $this->completion_percentage,
    //         'architect_office_name' => $this->architect_office_name,
    //         'construction_supervisor_office' => $this->construction_supervisor_office,
    //         'main_contractor' => $this->main_contractor,
    //         'istisna_contract_details' => $this->istisna_contract_details,
    //     ]);

    //     // Save payment milestones if enabled
    //     if ($this->enables_payment_plan && !empty($this->paymentMilestones)) {
    //         if (
    //             $this->completion_percentage === null ||
    //             empty($this->architect_office_name) ||
    //             empty($this->construction_supervisor_office) ||
    //             empty($this->main_contractor)
    //         ) {
    //             $this->addError('payment', 'جميع الحقول الخاصة بخطة الدفع مطلوبة.');
    //             return;
    //         }

    //         foreach ($this->paymentMilestones as $milestone) {
    //             $project->paymentMilestones()->create([
    //                 'name' => $milestone['name'],
    //                 'percentage' => $milestone['percentage'],
    //                 'completion_milestone' => $milestone['completion_milestone'],
    //                 'order' => count($this->paymentMilestones),
    //             ]);
    //         }
    //     }

    //     session()->flash('message', 'تم إنشاء المشروع بنجاح');
    //     return redirect()->back();
    // }

    public function render()
    {
        return view('livewire.developer.dashboard.projects.create-project', [
            'developers' => Developer::all(),
            'propertyTypes' => ProjectType::all(),
            'cities' => City::all(),
        ])->layout('components.layouts.developer', ['direction' => 'rtl']);
    }

}
