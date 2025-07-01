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
#[Title('تعديل المشروع')]

class EditProject extends Component
{
    use WithFileUploads;
    use LivewireAlert;

    public Project $project;
    public $title;
    public $slug;
    public $description;
    public $developer_id;
    public $property_type_id;
    public $is_active;
    public $is_featured;
    public $purpose;
    public $video;
    public $city_id;
    public $state_id;
    public $area_range_from;
    public $area_range_to;
    public $building_style;
    public $address;
    public $images = [];
    public $existingImages = [];
    public $threedurl;
    public $mediaPDF;
    public $newMediaPDF;
    public $AdLicense;
    public $latitude;
    public $longitude;
    
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

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:projects,slug,' . $this->project->id,
            'description' => 'nullable|string',
            'developer_id' => 'required|exists:developers,id',
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
            'newMediaPDF' => 'nullable|file|mimes:pdf|max:5120',
            'AdLicense' => 'nullable|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            
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
    }

    public function mount(Project $project)
    {
        $this->project = $project;
        
        // Fill the form with existing project data
        $this->title = $project->title;
        $this->slug = $project->slug;
        $this->description = $project->description;
        $this->developer_id = $project->developer_id;
        $this->property_type_id = $project->property_type_id;
        $this->is_active = $project->is_active;
        $this->is_featured = $project->is_featured;
        $this->purpose = $project->purpose;
        $this->video = $project->video;
        $this->city_id = $project->city_id;
        $this->state_id = $project->state_id;
        $this->area_range_from = $project->area_range_from;
        $this->area_range_to = $project->area_range_to;
        $this->building_style = $project->building_style;
        $this->address = $project->address;
        $this->existingImages = $project->images ?? [];
        $this->threedurl = $project->threedurl;
        $this->mediaPDF = $project->mediaPDF;
        $this->AdLicense = $project->AdLicense;
        
        // Make sure coordinates are properly cast to float/string
        $this->latitude = $project->latitude ? (string) $project->latitude : '';
        $this->longitude = $project->longitude ? (string) $project->longitude : '';
        
        // Payment plan fields
        $this->enables_payment_plan = $project->enables_payment_plan;
        $this->completion_percentage = $project->completion_percentage;
        $this->architect_office_name = $project->architect_office_name;
        $this->construction_supervisor_office = $project->construction_supervisor_office;
        $this->main_contractor = $project->main_contractor;
        $this->istisna_contract_details = $project->istisna_contract_details;
        
        // Payment milestones
        if ($project->paymentMilestones->count() > 0) {
            foreach ($project->paymentMilestones as $milestone) {
                $this->paymentMilestones[] = [
                    'id' => $milestone->id,
                    'name' => $milestone->name,
                    'percentage' => $milestone->percentage,
                    'completion_milestone' => $milestone->completion_milestone
                ];
            }
        } else {
            $this->paymentMilestones = [
                ['name' => '', 'percentage' => '', 'completion_milestone' => '']
            ];
        }
        
        // Load state options based on selected city
        if ($this->city_id) {
            $this->stateOptions = State::where('city_id', $this->city_id)->pluck('name', 'id')->toArray();
        }
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
        
        // Debug coordinate updates
        if ($propertyName === 'latitude' || $propertyName === 'longitude') {
        }
    }

    // Method to handle coordinate updates from JavaScript
    public function updateMapCoordinates($lat, $lng)
    {
        $this->latitude = (string) $lat;
        $this->longitude = (string) $lng;        
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
        // If milestone has an ID, we need to delete it from database
        if (isset($this->paymentMilestones[$index]['id'])) {
            $this->project->paymentMilestones()->where('id', $this->paymentMilestones[$index]['id'])->delete();
        }
        
        unset($this->paymentMilestones[$index]);
        $this->paymentMilestones = array_values($this->paymentMilestones);
    }

    public function removeImage($index)
    {
        // If it's an existing image, mark it for deletion
        if (isset($this->existingImages[$index])) {
            Storage::disk('public')->delete($this->existingImages[$index]);
            unset($this->existingImages[$index]);
            $this->existingImages = array_values($this->existingImages);
        } 
        // If it's a newly uploaded image
        else {
            unset($this->images[$index - count($this->existingImages)]);
            $this->images = array_values($this->images);
        }
    }

    public function submit()
    {        
        $this->validate();

        // Handle file uploads
        $imagePaths = $this->existingImages;
        foreach ($this->images as $image) {
            $imagePaths[] = $image->store('projects', 'public');
        }

        $pdfPath = $this->mediaPDF;
        if ($this->newMediaPDF) {
            // Delete old PDF if exists
            if ($this->mediaPDF) {
                Storage::disk('public')->delete($this->mediaPDF);
            }
            $pdfPath = $this->newMediaPDF->store('projects/pdf', 'public');
        }

        // Convert coordinates to float for database storage
        $latitude = is_numeric($this->latitude) ? (float) $this->latitude : null;
        $longitude = is_numeric($this->longitude) ? (float) $this->longitude : null;

        // Update the project
        $this->project->update([
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'developer_id' => $this->developer_id,
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
            'latitude' => $latitude,
            'longitude' => $longitude,
            
            // Payment plan fields
            'enables_payment_plan' => $this->enables_payment_plan,
            'completion_percentage' => $this->completion_percentage,
            'architect_office_name' => $this->architect_office_name,
            'construction_supervisor_office' => $this->construction_supervisor_office,
            'main_contractor' => $this->main_contractor,
            'istisna_contract_details' => $this->istisna_contract_details,
        ]);

        // Handle payment milestones
        $this->project->paymentMilestones()->delete(); // Delete all existing milestones
        
        if ($this->enables_payment_plan && !empty($this->paymentMilestones)) {
                    // تحقق من الحقول الخاصة بخطة الدفع
            if (
                $this->completion_percentage === null ||
                empty($this->architect_office_name) ||
                empty($this->construction_supervisor_office) ||
                empty($this->main_contractor)
            ) {
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
                    $this->addError('paymentMilestones.' . $index . '.name', 'جميع تفاصيل المرحلة مطلوبة.');
                    return;
                }
            }

            foreach ($this->paymentMilestones as $milestone) {
                $this->project->paymentMilestones()->create([
                    'name' => $milestone['name'],
                    'percentage' => $milestone['percentage'],
                    'completion_milestone' => $milestone['completion_milestone'],
                    'order' => count($this->paymentMilestones),
                ]);
            }
        }

        $this->alert('success', 'تم تحديث المشروع بنجاح.');
        session()->flash('message', 'تم تحديث المشروع بنجاح');
        return redirect()->back();
    }

    public function render()
    {
        return view('livewire.developer.dashboard.projects.edit-project', [
            'developers' => Developer::all(),
            'propertyTypes' => ProjectType::all(),
            'cities' => City::all(),
        ])->layout('components.layouts.developer', ['direction' => 'rtl']);
    }
}