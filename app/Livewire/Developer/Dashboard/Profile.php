<?php

namespace App\Livewire\Developer\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;
class Profile extends Component
{
    #[Layout('components.layouts.developer')]
    public function render()
    {
        return view('livewire.developer.dashboard.profile');
    }
}
