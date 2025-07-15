<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\FirebaseService;
use App\Models\DeviceToken;
use Illuminate\Support\Facades\Log;

class FirebaseChannel
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Send the given notification.
     */
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toFirebase')) {
            return;
        }

        try {
            // Now it's safe to call toFirebase() because we know it exists.
            $message = $notification->toFirebase($notifiable);
            
            if (is_null($message)) {
                return; // Allow the notification to cancel the send.
            }

            // Get user's device tokens
            $deviceTokens = DeviceToken::where('user_id', $notifiable->id)
                ->pluck('token')
                ->toArray();

            if (empty($deviceTokens)) {
                Log::info('No device tokens found for user in FirebaseChannel.', ['user_id' => $notifiable->id]);
                return;
            }

            // Send to multiple devices using the message data
            $this->firebaseService->sendToMultipleDevices(
                $deviceTokens,
                $message['title'],
                $message['body'],
                $message['data'] ?? [] // Use null coalescing for safety
            );

        } catch (\Exception $e) {
            Log::error('Firebase channel send failed', [
                'user_id' => $notifiable->id ?? null,
                'notification' => get_class($notification),
                'error' => $e->getMessage()
            ]);
        }
    }
}