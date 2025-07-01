<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\OrderInstallment;

class ReceiptUploadedForReview extends Notification
{
    use Queueable;

    public function __construct(public OrderInstallment $installment) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'       => 'إيصال جديد بانتظار المراجعة',
            'description' => 'قام المستخدم ' . $this->installment->unitOrder->user->name .
                             ' برفع إيصال للدفعة رقم ' . $this->installment->id .
                             ' في مشروع ' . $this->installment->unitOrder->unit->project->title .
                             ' بقيمة ' . $this->installment->amount . ' ر.س.',
            'unit_id'     => $this->installment->unitOrder->unit_id,
            'installment_id' => $this->installment->id,
            'icon'        => 'ki-add-folder',
            'type'        => 'receipt_review',
            'link'        => $this->installment->unit_order_id,
        ];
    }
}
