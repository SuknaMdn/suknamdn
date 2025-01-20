<?php
namespace App\Livewire\Developer\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\UnitOrder;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\Attributes\Title;

class OrdersByMonth extends Component
{
    use WithPagination;

    public $ordersByMonth = [];
    public $developer;
    public $projects;

    public $dateRange;
    // Filter properties
    #[Url]
    public $filterStatus = [];
    #[Url]
    public $filterPaymentPlan = [];
    #[Url]
    public $searchTerm = '';
    #[Url]
    public $filterProject = [];

    public $currentRoute;

    protected $queryString = [
        'filterStatus' => ['except' => []],
        'filterPaymentPlan' => ['except' => []],
        'searchTerm' => ['except' => ''],
        'filterProject' => ['except' => []],
    ];

    public function openOrderDetails($orderId)
    {
        $this->dispatch('showOrderDetails', $orderId);
    }

    protected $listeners = ['order-status-updated'];

    public function order_status_updated($orderId)
    {
        $this->ordersByMonth = UnitOrder::find($orderId);
    }

    public function mount()
    {
        $this->currentRoute = request()->route()->getName();
        $developer = auth()->user()->developer;
        if ($developer) {
            $this->projects = $developer->projects;
            $this->generateMonthlyOrderStats();
        }
    }

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function generateMonthlyOrderStats()
    {
        $unitIds = $this->projects->pluck('units')->flatten()->pluck('id');

        $this->ordersByMonth = UnitOrder::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            // ->where('payment_status', 'paid')
            ->whereIn('unit_id', $unitIds)
            ->when(!empty($this->filterStatus), fn($query) => $query->whereIn('status', $this->filterStatus))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(fn($item) => [Carbon::create()->month($item->month)->format('F') => $item->count]);
    }

    public function resetFilters()
    {
        $this->reset(['filterStatus', 'filterPaymentPlan', 'filterProject', 'searchTerm']);
        $this->resetPage();
        $this->generateMonthlyOrderStats();
    }
    #[Title('الطلبات')]
    public function render()
    {
        $unitIds = $this->projects->pluck('units')->flatten()->pluck('id');

        $query = UnitOrder::whereIn('unit_id', $unitIds)->orderBy('created_at', 'desc'); // ->where('payment_status', 'paid')

        if ($this->filterStatus != null) {
            $query->whereIn('status', $this->filterStatus);
        }

        if ($this->filterPaymentPlan != null) {
            $query->whereIn('payment_plan', $this->filterPaymentPlan);
        }

        if ($this->filterProject != null) {
            $query->whereHas('unit', fn($unitQuery) => $unitQuery->whereIn('project_id', $this->filterProject));
        }

        if (!empty($this->dateRange)) {
            $dates = explode(' to ', $this->dateRange);
            $startDate = isset($dates[0]) ? Carbon::createFromFormat('Y-m-d', $dates[0])->startOfDay() : null;
            $endDate = isset($dates[1]) ? Carbon::createFromFormat('Y-m-d', $dates[1])->endOfDay() : null;

            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }

        if ($this->searchTerm != '') {
            $query->where(function($subQuery) {
                $subQuery->whereHas('user', fn($userQuery) => $userQuery->where('username', 'like', "%{$this->searchTerm}%")
                ->orWhere('phone', 'like', "%{$this->searchTerm}%")
                ->orWhere('email', 'like', "%{$this->searchTerm}%"))
                ->orWhereHas('unit', fn($unitQuery) => $unitQuery->where('title', 'like', "%{$this->searchTerm}%")
                ->orWhereHas('project', fn($projectQuery) => $projectQuery->where('title', 'like', "%{$this->searchTerm}%"))
                );
            });
        }

        $orders = $query->paginate(15);
        // dd($orders);

        return view('livewire.developer.dashboard.orders-by-month', [
            'orders' => $orders,
            'statusOptions' => ['pending', 'processing', 'confirmed', 'cancelled'],
            'paymentPlanOptions' => UnitOrder::distinct('payment_plan')->pluck('payment_plan'),
            'projects' => $this->projects,
            'currentRoute' => $this->currentRoute
        ]);
    }
}
