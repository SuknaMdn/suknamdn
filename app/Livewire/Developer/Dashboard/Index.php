<?php

namespace App\Livewire\Developer\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\UnitOrder;
use Livewire\Attributes\Title;

class Index extends Component
{
    #[Layout('components.layouts.developer')]
    public $developer;
    public $projects;

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

    public function mount()
    {
        $this->developer = auth()->user()->developer;
        $this->projects = $this->developer->projects;
    }
    public function prepareUnitsData()
    {
        $unitsSoldByMonth = [];
        $unitsNotSoldByMonth = [];
        $monthLabels = [];

        $projects = $this->developer->projects;
        $unitIds = $projects->pluck('units')->flatten()->pluck('id');
        $unitOrders = UnitOrder::whereIn('unit_id', $unitIds)->where('payment_status', 'paid')->orderBy('created_at', 'desc')->get();


        // Group units by month and case
        $unitsByMonth = $unitOrders->groupBy(function ($unitOrder) {
            return $unitOrder->created_at->format('M Y');
        });

        foreach ($unitsByMonth as $month => $units) {
            $monthLabels[] = $month;
            $unitsSoldByMonth[] = $units->where('payment_status', 'paid')->count();
            $unitsNotSoldByMonth[] = $units->where('payment_status', 'unpaid')->count();
        }

        return [
            'unitsSoldByMonth' => $unitsSoldByMonth,
            'unitsNotSoldByMonth' => $unitsNotSoldByMonth,
            'monthLabels' => $monthLabels
        ];
    }

    #[Title('لوحة التحكم')]
    public function render()
    {
        $unitsData = $this->prepareUnitsData();
        return view('livewire.developer.dashboard.index', $unitsData);
    }
}
