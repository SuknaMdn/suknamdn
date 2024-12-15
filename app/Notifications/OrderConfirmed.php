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

    protected $order;

    /**
     * Create a new notification instance.
     *
     * @param UnitOrder $order
     * @return void
     */
    public function __construct(UnitOrder $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Order Confirmed')
                    ->greeting('Hello!')
                    ->line('Your order has been confirmed - الطلب الخاص بك قد تم التأكيد عليه.')
                    ->line('Order ID: ' . $this->order->id . ' - رقم الطلب: ' . $this->order->id)
                    ->line('Unit - الوحدة: ' . $this->order->unit->title)
                    ->line('Project - المشروع: ' . $this->order->project->title)
                    ->line('Thank you for using our application! - شكرا لاستخدامكم لتطبيقنا.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'status' => $this->order->status,
            'message' => 'Your order has been confirmed. - الطلب الخاص بك قد تم التأكيد عليه.',
            'url_parameter' => $this->order->id,
        ];
    }
}
