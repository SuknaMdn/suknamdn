<?php

namespace App\Http\Controllers\Api;

use App\Events\NafathStatusUpdated;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NafathService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class NafathController extends Controller
{

    protected $nafathService;

    public function __construct(NafathService $nafathService)
    {
        $this->nafathService = $nafathService;
    }

    // This is the endpoint that your frontend will call to initiate the authentication process
    public function initiateAuth()
    {
        // try {
            $authUrl = $this->nafathService->initiateAuthentication();
            return response()->json([
                'status' => 'success',
                'auth_url' => $authUrl
            ]);
        // } catch (\Exception $e) {
        //     Log::error('Nafath initiation failed: ' . $e->getMessage());
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Failed to initiate authentication'
        //     ], 500);
        // }
    }

    // This is the callback URL that Nafath will redirect to after authentication
    public function callback(Request $request)
    {
        try {
            $idToken = $request->input('id_token');
            $state = $request->input('state');

            $userInfo = $this->nafathService->verifyCallback($idToken, $state);

            // Store user information or update your database as needed
            // You might want to associate this with your existing user model

            return response()->json([
                'status' => 'success',
                'user_info' => $userInfo
            ]);
        } catch (\Exception $e) {
            Log::error('Nafath callback failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Authentication failed'
            ], 401);
        }
    }

}
