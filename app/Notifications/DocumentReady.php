<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentReady extends Notification
{
    use Queueable;

    public function __construct(
        public string $unitTitle,
        public string $type, // 'istisna' أو 'quote'
        public int $unitId
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'       => $this->type === 'istisna' ? 'عقد الاستصناع جاهز' : 'عرض السعر جاهز',
            'description' => 'الملف الخاص بالوحدة "' . $this->unitTitle . '" أصبح متاحًا الآن.',
            'unit_id'     => $this->unitId,
            'icon'        => 'ki-file-added',
            'type'        => 'document_ready',
        ];
    }
}
