<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Project;

class ShowProject extends Component
{
    public $slug;
    public $project;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->project = Project::where('slug', $slug)->first();
    }

    public function render()
    {
        // Return the view with project data
        return view('livewire.frontend.show-project', [
            'project' => $this->project,
        ]);
    }
}
