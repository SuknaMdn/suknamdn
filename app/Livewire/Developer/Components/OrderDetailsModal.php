<?php

namespace App\Livewire\Developer\Components;

use Livewire\Component;
use App\Models\UnitOrder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Notifications\OrderConfirmed;
use App\Notifications\User\OrderStatusNotification;

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
            // التحقق من أن الأوردر ليس مكتملاً قبل السماح بالتعديل
            if ($order->status === 'confirmed') {
                $this->alert('error', 'لا يمكن تعديل طلب مكتمل');
                $this->status = 'confirmed';
                return;
            }

            try {
                $order->status = $this->status;
                // $order->payment->paid_at = now();
                if($this->status == 'confirmed'){
                    // update units as paid
                    $order->unit->case = '1';
                    $order->unit->save();
                }
                $order->save();
                $this->loadOrder();
                $this->dispatch('order-status-updated', orderId: $this->orderId);
                $this->alert('success', 'تم تحديث حالة الطلب بنجاح');

                $order->user->notify(new OrderStatusNotification($order));
            } catch (\Exception $e) {
                $this->alert('error', 'فشل في تحديث حالة الطلب');
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
