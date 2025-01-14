<?php

namespace App\Livewire\Developer\Dashboard\Orders;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
#[Title('الطلبات')]
#[Layout('components.layouts.developer')]
class AllOrders extends Component
{
    public function render()
    {
        return view('livewire.developer.dashboard.orders.all-orders');
    }
}
