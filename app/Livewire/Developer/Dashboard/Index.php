<?php

namespace App\Livewire\Developer\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\UnitOrder;
class Index extends Component
{
    #[Layout('components.layouts.developer')]
    public $developer;
    public $projects;

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
    public function render()
    {
        $unitsData = $this->prepareUnitsData();
        return view('livewire.developer.dashboard.index', $unitsData);
    }
}
