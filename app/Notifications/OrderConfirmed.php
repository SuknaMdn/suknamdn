<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\UnitOrder;

class OrderConfirmed extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public UnitOrder $order) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'       => 'تم تأكيد الطلب',
            'description' => 'طلبك رقم ' . $this->order->id . ' تم تأكيده بنجاح.',
            'unit_id'     => $this->order->unit_id,
            'icon'        => 'ki-badge',
            'type'        => 'order_confirmed',
        ];
    }
}
