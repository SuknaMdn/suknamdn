<?php

namespace App\Livewire\Developer\Dashboard\Projects;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Project as ProjectModel;
use App\Models\Unit;
use App\Models\UnitOrder;
use Jantinnerezo\LivewireAlert\LivewireAlert;

#[Layout('components.layouts.developer')]
#[Title('Project')]

class ProjectPage extends Component
{
    use LivewireAlert;
    public $project;
    protected $slug;
    public $orders;
    public $units;
    public $unitsSold;
    public $unitsNotSold;
    public $clients;
    public $buildingNumbers = [];

    public function __construct($slug = null)
    {
        $this->slug = $slug;
    }

    public function mount($slug)
    {
        $this->project = ProjectModel::where('slug', $slug)->first();
        $this->units = Unit::where('project_id', $this->project->id)->with('orders')->get();
        $this->orders = UnitOrder::whereIn('unit_id', $this->units->pluck('id'))->get();
        $this->unitsSold = $this->units->where('case', 1)->count();
        $this->unitsNotSold = $this->units->where('case', 0)->count();
        $this->clients = $this->units->pluck('client')->unique();
        $this->buildingNumbers = $this->units->pluck('building_number')->unique();
    }

    public function openOrderDetails($orderId)
    {
        $this->dispatch('showOrderDetails', $orderId);
    }

    public function inactiveProject()
    {
        $this->project->update(['is_active' => false]);
        $this->alert('success', 'Project Inactive');
    }
    public function activeProject()
    {
        $this->project->update(['is_active' => true]);
        $this->alert('success', 'Project Active');
    }

    public function render()
    {
        return view('livewire.developer.dashboard.projects.project');
    }
}
