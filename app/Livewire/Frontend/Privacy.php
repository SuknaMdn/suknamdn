<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Settings\GeneralSettings;

class Privacy extends Component
{
    public $privacy;
    public function mount(GeneralSettings $settings)
    {
        $this->privacy = $settings->privacy_policy;
    }
    public function render()
    {
        $privacy = $this->privacy;
        return view('livewire.frontend.privacy', compact('privacy'));
    }
}
