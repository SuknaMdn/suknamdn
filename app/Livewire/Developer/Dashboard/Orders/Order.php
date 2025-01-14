<?php

namespace App\Livewire\Developer\Dashboard\Orders;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('بيانات الطلب')]
#[Layout('components.layouts.developer')]
class Order extends Component
{
    public function render()
    {
        return view('livewire.developer.dashboard.orders.order');
    }
}
