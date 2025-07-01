<?php

namespace App\Livewire\Developer\Dashboard\Orders;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\WithFileUploads;
use App\Models\UnitOrder;
use App\Models\OrderInstallment;
use App\Notifications\PaymentConfirmed;
use App\Notifications\DocumentReady;
use App\Notifications\OrderConfirmed;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Auth;

#[Title('تفاصيل الطلب')]
#[Layout('components.layouts.developer')]

class Order extends Component
{
 use WithFileUploads, LivewireAlert;

    // The main property holding all order data.
    public UnitOrder $order;

    // Properties for file uploads.
    public $istisnaContractFile;
    public $priceQuoteFile;

    // Properties for the "Update Status" modal.
    public string $newStatus = '';
    public string $statusNotes = '';

    // ---== Lifecycle Hooks ==---
    public function mount(UnitOrder $order): void
    {
        // 1. Authorization Check: Does this order belong to the current developer?
        abort_if(Auth::user()->developer->id !== $order->unit->project->developer_id, 403, 'غير مصرح لك بعرض هذا الطلب');

        // 2. Eager Loading: Load all required relationships at once for better performance.
        $this->order = $order->load([
            'user:id,username,email,phone,firstName,lastName',
            'unit:id,project_id,title,total_area,unit_price,total_amount',
            'unit.project:id,developer_id,title,enables_payment_plan',
            'installments.milestone:id,name,completion_milestone'
        ]);

        // Initialize the status for the modal
        $this->newStatus = $this->order->status;
    }

    // ---== Computed Properties for Financial Management ==---

    #[Computed(persist: true)]
    public function financialSummary(): array
    {
        // Return empty if the project doesn't use payment plans.
        if (!$this->order->unit->project->enables_payment_plan) {
            return [];
        }

        $installments = $this->order->installments;
        $totalAmount = $installments->sum('amount');
        $paidAmount = $installments->where('status', 'paid')->sum('amount');
        
        return [
            'total'      => $totalAmount,
            'paid'       => $paidAmount,
            'remaining'  => $totalAmount - $paidAmount,
            'percentage' => ($totalAmount > 0) ? round(($paidAmount / $totalAmount) * 100) : 0,
        ];
    }

    #[Computed(persist: true)]
    public function nextDueInstallment(): ?OrderInstallment
    {
        if (!$this->order->unit->project->enables_payment_plan) {
            return null;
        }

        return $this->order->installments
            ->whereIn('status', ['pending', 'due', 'overdue'])
            ->sortBy('id')
            ->first();
    }

    // ---== Action Methods ==---

    /**
     * Confirm that an installment has been paid.
     */
    public function confirmPayment(int $installmentId): void
    {
        $installment = OrderInstallment::find($installmentId);

        if ($installment && $installment->unit_order_id === $this->order->id) {
            $installment->update(['status' => 'paid', 'paid_at' => now()]);
            
            // Notify the user
            $this->order->user->notify(new PaymentConfirmed($installment));
            
            $this->alert('success', 'تم تأكيد الدفعة بنجاح!');
            
            // Refresh the order data to update the UI
            $this->order->refresh();
            unset($this->financialSummary); // Unset computed property to force recalculation
        }
    }
    
    /**
     * Upload a contractual document (Istisna Contract or Price Quote).
     */
    public function uploadFile(string $type): void
    {
        $field = ($type === 'istisna') ? 'istisnaContractFile' : 'priceQuoteFile';
        $column = ($type === 'istisna') ? 'istisna_contract_url' : 'price_quote_url';
        $folder = ($type === 'istisna') ? 'contracts' : 'quotes';
        $message = ($type === 'istisna') ? 'تم رفع عقد الاستصناع بنجاح.' : 'تم رفع عرض السعر بنجاح.';

        $this->validate([$field => 'required|file|mimes:pdf|max:10240']);

        $path = $this->$field->store($folder, 'public');
        $this->order->update([$column => $path]);

        $this->reset($field);
        $this->alert('success', $message);
        
        // Dispatch an event to let the frontend know the upload is complete.
        $this->dispatch('fileUploaded');
        $this->order->refresh();
    }

    /**
     * Send a notification to the user that a document is ready.
     */
    public function sendNotification(string $type): void
    {
        $user = $this->order->user;
        $user->notify(new DocumentReady(
            unitTitle: $this->order->unit->title,
            type: $type,
            unitId: $this->order->unit_id
        ));
        
        $this->alert('success', 'تم إرسال الإشعار بنجاح!');
    }

    /**
     * Update the order's main status from the modal.
     */
    public function updateOrderStatus(): void
    {
        $this->validate([
            'newStatus' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $this->order->update([
            'status' => $this->newStatus
        ]);
        
        // Here you could also log the change or add a note to a history table.
        // For now, we just update the status.
        $this->order->user->notify(new OrderConfirmed($this->order));
        $this->alert('success', 'تم تحديث حالة الطلب بنجاح.');
        
        // Dispatch an event to close the modal from the frontend.
        $this->dispatch('orderStatusUpdated');
    }

    public function render()
    {
        return view('livewire.developer.dashboard.orders.order');
    }
}
