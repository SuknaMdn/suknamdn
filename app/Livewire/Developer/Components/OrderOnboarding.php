<?php

namespace App\Livewire\Developer\Components;

use App\Models\Project;
use Livewire\Component;
use App\Models\UnitOrder;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Notifications\DeveloperActionRequired;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class OrderOnboarding extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public UnitOrder $order;
    public $currentStep = 1;
    public $istisnaContractFile;
    public $priceQuoteFile;
    public $paymentReceipt;
    public $completionPercentage;
    public $newStatus = 'pending';
    public $showStatusModal = false;
    public $enables_payment_plan = false;
    protected $listeners = ['refreshOnboarding' => '$refresh'];

    public function mount(UnitOrder $order)
    {
        $this->order = $order;
        $this->enables_payment_plan = $order->unit->project->enables_payment_plan;
        $this->completionPercentage = $this->order->unit->project->fresh()->completion_percentage;
        // إذا كانت النسبة 100، ابدأ من الخطوة الثالثة مباشرةً
        if ($this->completionPercentage == 100) {
            $this->currentStep = 3;
        }

        
    }

    public function nextStep()
    {
        // منع الانتقال إلى الخطوة 3 إلا إذا كانت النسبة 100%
        if ($this->currentStep == 2 && $this->completionPercentage < 100) {
            $this->alert('warning', 'لا يمكنك إتمام الطلب قبل إكمال نسبة الإنجاز إلى 100٪');
            return;
        }

        if ($this->currentStep < 3) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        // منع الرجوع إذا كانت النسبة 100%
        if ($this->completionPercentage == 100) {
            return;
        }

        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function updatedCompletionPercentage()
    {
        if ($this->completionPercentage == 100) {
            $this->currentStep = 3;
        }
    }

    public function uploadIstisnaContract()
    {
        $this->validate([
            'istisnaContractFile' => 'required|file|mimes:pdf|max:10240',
        ]);

        $path = $this->istisnaContractFile->store('contracts', 'public');
        $this->order->update(['istisna_contract_url' => $path]);

        $this->alert('success', 'تم رفع عقد الاستصناع بنجاح');
        $this->reset('istisnaContractFile');
    }

    public function uploadPriceQuote()
    {
        $this->validate([
            'priceQuoteFile' => 'required|file|mimes:pdf|max:10240',
        ]);

        $path = $this->priceQuoteFile->store('quotes', 'public');
        $this->order->update(['price_quote_url' => $path]);

        $this->alert('success', 'تم رفع عرض السعر بنجاح');
        $this->reset('priceQuoteFile');
    }

    public function confirmPayment($installmentId)
    {
        $installment = $this->order->installments()->findOrFail($installmentId);
        $installment->update([
            'status' => 'paid',
            'paid_at' => now()
        ]);

        $this->alert('success', 'تم تأكيد الدفعة بنجاح');
    }

    public function updateProjectCompletion()
    {
        $this->validate([
            'completionPercentage' => 'required|numeric|min:0|max:100',
        ]);

        $this->order->unit->project->update([
            'completion_percentage' => $this->completionPercentage
        ]);

        $this->alert('success', 'تم تحديث نسبة إنجاز المشروع');

        // إذا وصلت إلى 100%، انتقل مباشرةً للخطوة 3
        if ($this->completionPercentage == 100) {
            $this->currentStep = 3;
        }
    }

    public function openStatusModal()
    {
        $this->showStatusModal = true;
    }

    public function closeStatusModal()
    {
        $this->showStatusModal = false;
    }

    public function updateOrderStatus()
    {
        $this->validate([
            'newStatus' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $this->order->update(['status' => $this->newStatus]);
        $this->showStatusModal = false;

        $this->alert('success', 'تم تحديث حالة الطلب بنجاح');
    }

    public function render()
    {
        return view('livewire.developer.components.order-onboarding');
    }
}
