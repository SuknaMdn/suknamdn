<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeviceToken;
use App\Services\FirebaseService;

class NotificationController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function storeToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'device_type' => 'nullable|string'
        ]);

        DeviceToken::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'token' => $request->token
            ],
            [
                'device_type' => $request->device_type
            ]
        );

        return response()->json(['message' => 'Token stored successfully']);
    }

    public function removeToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        DeviceToken::where('user_id', auth()->id())
            ->where('token', $request->token)
            ->delete();

        return response()->json(['message' => 'Token removed successfully']);
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'body' => 'required|string',
            'data' => 'nullable|array'
        ]);

        $deviceTokens = DeviceToken::where('user_id', $request->user_id)
            ->pluck('token')
            ->toArray();

        if (empty($deviceTokens)) {
            return response()->json(['message' => 'No device tokens found'], 404);
        }

        try {
            $this->firebaseService->sendToMultipleDevices(
                $deviceTokens,
                $request->title,
                $request->body,
                $request->data ?? []
            );

            return response()->json(['message' => 'Notification sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to send notification', 'error' => $e->getMessage()], 500);
        }
    }
}
