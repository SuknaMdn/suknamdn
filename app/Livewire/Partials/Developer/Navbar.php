<?php

namespace App\Livewire\Partials\Developer;

use App\Models\Developer;
use Livewire\Component;
use App\Settings\GeneralSettings;
use App\Models\UnitOrder;

class Navbar extends Component
{
    public $siteLogo;
    public $user;
    public $ordersCount;
    public $projects;
    public $units;
    public function mount()
    {
        $settings = app(GeneralSettings::class);
        $siteLogo = $settings->brand_logo;
        $this->siteLogo = $siteLogo;
        $this->user = auth()->user();

        $this->projects = $this->user->developer->projects()->with('units')->get();

        foreach ($this->projects as $project) {
            foreach ($project->units as $unit) {
                $this->ordersCount += $unit->orders->where('status', 'pending')->count();
            }
        }
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->to('/developer/login');
    }

    public function render()
    {
        return view('livewire.partials.developer.navbar', [
            'siteLogo' => $this->siteLogo,
            'user' => $this->user,
            'ordersCount' => $this->ordersCount,
        ]);
    }
}
