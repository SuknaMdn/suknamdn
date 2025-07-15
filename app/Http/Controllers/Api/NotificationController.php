<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\FirebaseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Http\Resources\NotificationResource;

class NotificationController extends Controller
{
    /**
     * Send notification to a specific user
     */
    public function sendToUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'data' => 'nullable|array',
            'send_push' => 'boolean'
        ]);

        try {
            $user = User::findOrFail($request->user_id);
            
            $notification = new FirebaseNotification(
                $request->title,
                $request->body,
                $request->data ?? [],
                $request->send_push ?? true
            );

            $user->notify($notification);

            return response()->json([
                'status' => true,
                'message' => 'Notification sent successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to send notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send notification to multiple users
     */
    public function sendToMultipleUsers(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'data' => 'nullable|array',
            'send_push' => 'boolean'
        ]);

        try {
            $users = User::whereIn('id', $request->user_ids)->get();
            
            $notification = new FirebaseNotification(
                $request->title,
                $request->body,
                $request->data ?? [],
                $request->send_push ?? true
            );

            Notification::send($users, $notification);

            return response()->json([
                'status' => true,
                'message' => 'Notifications sent successfully',
                'sent_to' => $users->count() . ' users'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to send notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's notifications
     */
    public function index(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $notifications = $user->notifications()
            ->latest()
            ->paginate(20);

        return response()->json([
            'data' => [
                'notifications' => NotificationResource::collection($notifications->items()),
                'unread_count' => $user->unreadNotifications()->count(),
                'meta' => [
                    'current_page' => $notifications->currentPage(),
                    'last_page' => $notifications->lastPage(),
                    'total' => $notifications->total(),
                    'per_page' => $notifications->perPage(),
                ],
            ],
        ]);
    }

    /**
     * Mark notification as read
     */
    public function makeAsRead(Request $request, $id)
    {
        $notification = $request->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read successfully',
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function makeAllAsRead(Request $request)
    {
        $request->user()
            ->unreadNotifications()
            ->update(['read_at' => now()]);

        return response()->json([
            'message' => 'All notifications marked as read successfully',
        ]);
    }
}