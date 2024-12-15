<?php

namespace App\Livewire\Developer\Dashboard\Projects;

use Livewire\Component;
use Livewire\Attributes\Layout;
class CreateProject extends Component
{
    #[Layout('components.layouts.developer')]

    public function render()
    {
        return view('livewire.developer.dashboard.projects.create-project');
    }
}
