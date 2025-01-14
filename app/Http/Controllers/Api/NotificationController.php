<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $notifications = $user->notifications()
            ->latest()
            ->paginate(20);

        return response()->json([
            'data' => [
                'notifications' => $notifications->items(),
                'unread_count' => $user->unreadNotifications()->count(),
                'meta' => [
                    'current_page' => $notifications->currentPage(),
                    'last_page' => $notifications->lastPage(),
                    'total' => $notifications->total(),
                ],
            ],
        ]);
    }

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
