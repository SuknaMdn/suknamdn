<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\OrderInstallment;

class PaymentConfirmed extends Notification
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
            'title'       => 'تم تأكيد دفعة',
            'description' => 'تم تأكيد دفعتك رقم ' . $this->installment->id . ' بقيمة ' . $this->installment->amount . ' ر.س.',
            'unit_id'     => $this->installment->unitOrder->unit_id,
            'icon'        => 'ki-wallet',
            'type'        => 'payment',
        ];
    }
}
