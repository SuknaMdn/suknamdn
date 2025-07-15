<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;
use App\Services\FirebaseService;
use App\Models\DeviceToken;
use Illuminate\Support\Facades\Log;

class FirebaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $title;
    protected $body;
    protected $data;
    protected $sendPush;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $title, string $body, array $data = [], bool $sendPush = true)
    {
        $this->title = $title;
        $this->body = $body;
        $this->data = $data;
        $this->sendPush = $sendPush;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        $channels = ['database'];
        
        if ($this->sendPush) {
            $channels[] = 'firebase';
        }
        
        return $channels;
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase($notifiable): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'data' => $this->data,
            'read_at' => null,
            'created_at' => now(),
        ];
    }

    /**
     * Send Firebase push notification
     */
    public function toFirebase($notifiable)
    {
        try {
            $firebaseService = app(FirebaseService::class);
            
            // Get user's device tokens
            $deviceTokens = DeviceToken::where('user_id', $notifiable->id)
                ->pluck('token')
                ->toArray();

            if (empty($deviceTokens)) {
                Log::info('No device tokens found for user', ['user_id' => $notifiable->id]);
                return;
            }

            // Send to multiple devices
            $result = $firebaseService->sendToMultipleDevices(
                $deviceTokens,
                $this->title,
                $this->body,
                $this->data
            );

            Log::info('Firebase notification sent', [
                'user_id' => $notifiable->id,
                'title' => $this->title,
                'devices_count' => count($deviceTokens)
            ]);

            return $result;

        } catch (\Exception $e) {
            Log::error('Firebase notification failed', [
                'user_id' => $notifiable->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'data' => $this->data,
        ];
    }
}