<?php

namespace App\Livewire\Developer\Components;

use Livewire\Component;
use App\Models\UnitOrder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Notifications\OrderConfirmed;
class OrderDetailsModal extends Component
{
    use LivewireAlert;

    public $orderId;
    public $order;
    public $status;
    protected $listeners = ['showOrderDetails'];

    public function showOrderDetails($orderId)
    {
        $this->orderId = $orderId;
        $this->order = UnitOrder::find($orderId);
        $this->status = $this->order->status;
    }

    // Method to load/reload the order
    public function loadOrder()
    {
        $this->order = UnitOrder::find($this->orderId);
        $this->status = $this->order->status;
    }

    // Method to update status
    public function updateStatus()
    {
        $order = UnitOrder::find($this->orderId);

        if ($order) {
            try {
                $order->status = $this->status;
                $order->paid_at = now();
                if($this->status == 'confirmed'){
                    // update units as paid
                    $order->unit->case = '1';
                    $order->unit->save();

                    // Send email to client
                    // $client = $order->user;
                    // $client->notify(new OrderConfirmed($order));
                }
                $order->save();
                $this->loadOrder();
                $this->dispatch('order-status-updated', orderId: $this->orderId);
                $this->alert('success', 'Order status updated successfully');
            } catch (\Exception $e) {
                $this->alert('error', 'Failed to update order status');
            }
        }
    }

    public function closeModal()
    {
        $this->orderId = null;
        $this->order = null;
    }

    public function render()
    {
        return view('livewire.developer.components.order-details-modal');
    }
}
