<?php

namespace App\Livewire\Partials\Developer;

use App\Models\Developer;
use Livewire\Component;
use App\Settings\GeneralSettings;
use App\Models\UnitOrder;

class Navbar extends Component
{
    public $developer;

    // nafath auth component
    protected $listeners = [
        'nafath-verified' => 'handleNafathVerified'
    ];

    public function handleNafathVerified($data)
    {
        // Handle what happens after successful Nafath verification
        $this->developer = $this->developer->fresh(); // Refresh the model
        session()->flash('success', 'تم التحقق من الهوية بنجاح! يمكنك الآن الوصول لجميع الخدمات');
    }


    public $siteLogo;
    public $user;
    public $ordersCount;
    public $projects;
    public $units;
    public function mount()
    {
        $settings = app(GeneralSettings::class);
        $this->developer = auth()->user()->developer;
        $siteLogo = $settings->brand_logo;
        $this->siteLogo = $siteLogo;
        $this->user = auth()->user();

        $this->projects = $this->user->developer->projects()->with('units')->get();

        foreach ($this->projects as $project) {
            foreach ($project->units as $unit) {
                $this->ordersCount += $unit->orders->where('status', 'pending')->where('payment_status', 'paid')->count();
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
