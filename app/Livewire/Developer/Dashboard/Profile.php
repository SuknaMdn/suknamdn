<?php

namespace App\Livewire\Developer\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
class Profile extends Component
{
    #[Layout('components.layouts.developer')]
    #[Title('الملف الشخصي')]
    public function render()
    {
        return view('livewire.developer.dashboard.profile');
    }
}
