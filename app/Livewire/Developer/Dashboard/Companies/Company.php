<?php

namespace App\Livewire\Developer\Dashboard\Companies;

use Livewire\Component;
use Livewire\Attributes\Layout;

class Company extends Component
{
    #[Layout('components.layouts.developer')]
    public function render()
    {
        return view('livewire.developer.dashboard.companies.company');
    }
}
