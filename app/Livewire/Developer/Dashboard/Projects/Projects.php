<?php

namespace App\Livewire\Developer\Dashboard\Projects;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\URL;
use App\Models\Project;
use App\Models\ProjectType;

#[Title('المشاريع')]
#[Layout('components.layouts.developer')]
class Projects extends Component
{
    use LivewireAlert, WithFileUploads, WithPagination;

    #[URL(as: 'featured')]
    public ?bool $is_featured = false;

    #[URL(as: 'is_active')]
    public ?int $selected_is_active = null;

    #[URL(as: 'project_type')]
    public ?int $selected_project_type = null;

    public $developer;
    public $projectTypes;

    public function render()
    {
        $this->developer = auth()->user()->developer;

        $projects = Project::where('developer_id', $this->developer->id);

        // Apply filters
        if ($this->selected_is_active !== null) {
            $projects->where('is_active', $this->selected_is_active);
        }

        if ($this->is_featured) {
            $projects->where('is_featured', $this->is_featured);
        }

        if ($this->selected_project_type !== null) {
            $projects->where('property_type_id', $this->selected_project_type);
        }

        // Get the projects
        $projectsCollection = Project::where('developer_id', $this->developer->id)->get();

        $activeProjects = $projectsCollection->where('is_active', 1)->count();
        $inactiveProjects = $projectsCollection->where('is_active', 0)->count();

        $unitsSold = $projectsCollection->sum(function ($project) {
            return $project->units->where('case', 1)->count();
        });

        $unitsNotSold = $projectsCollection->sum(function ($project) {
            return $project->units->where('case', 0)->count();
        });

        $allUnits = $unitsSold + $unitsNotSold;

        $projectsWithoutCaseZero = $projectsCollection->filter(function ($project) {
            return $project->units->where('case', 0)->count() == 0;
        })->count();

        $projects = $projects->paginate(9);
        $this->projectTypes = ProjectType::where('status', 1)->get();

        return view('livewire.developer.dashboard.projects.projects', compact(
            'projects',
            'activeProjects',
            'inactiveProjects',
            'unitsSold',
            'unitsNotSold',
            'allUnits',
            'projectsWithoutCaseZero'
        ));
    }

    public function calculateProgressPercentage($project)
    {
        $totalUnits = 0;
        $caseOneUnits = 0;

        foreach ($project->units as $unit) {
            $totalUnits++;
            if ($unit->case == 1) {
                $caseOneUnits++;
            }
        }

        return $totalUnits > 0 ? ($caseOneUnits / $totalUnits) * 100 : 0;
    }
}
