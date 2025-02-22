<?php

namespace App\Http\Controllers\Api;

use App\Events\NafathStatusUpdated;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NafathService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Exception;

class NafathController extends Controller
{

    protected $nafathService;

    public function __construct(NafathService $nafathService)
    {
        $this->nafathService = $nafathService;
    }


    public function createMfaRequest(Request $request)
    {
        $nationalId = $request->input('nationalId');
        $service = $request->input('service');
        $requestId = $request->input('requestId');
        $local = $request->input('local', 'ar');

        $response = $this->nafathService->createMfaRequest($nationalId, $service, $requestId, $local);
        // dd($response);
        if ($response['success']) {
            return response()->json($response['data'], 200);
        } else {
            return response()->json($response['error'], $response['error']['code'] ?? 500);
        }
    }

    public function getMfaRequestStatus(Request $request)
    {
        $nationalId = $request->input('nationalId');
        $transId = $request->input('transId');
        $random = $request->input('random');

        $response = $this->nafathService->getMfaRequestStatus($nationalId, $transId, $random);

        if ($response['success']) {
            return response()->json($response['data'], 200);
        } else {
            return response()->json($response['error'], $response['error']['code'] ?? 500);
        }
    }

    public function handleCallback(Request $request)
    {
        // Log the incoming request for debugging
        Log::info('Nafath Callback Received:', $request->all());

        // Validate the request
        $request->validate([
            'transId' => 'required|string',
            'status' => 'required|string|in:ACCEPTED,REJECTED',
            'userInfo' => 'nullable|array', // Optional user information
        ]);

        // Extract data from the request
        $transId = $request->input('transId');
        $status = $request->input('status');
        $userInfo = $request->input('userInfo', []);

        // Handle the callback based on the status
        if ($status === 'ACCEPTED') {
            // User accepted the request
            Log::info("User accepted the MFA request. Transaction ID: $transId");
            Log::info('User Information:', $userInfo);

            // Process the user information (e.g., save to database, log in the user, etc.)
            $this->processUserInfo($userInfo);
        } else {
            // User rejected the request
            Log::info("User rejected the MFA request. Transaction ID: $transId");
        }

        // Return a success response to Nafath
        return response()->json(['success' => true]);
    }

    protected function processUserInfo($userInfo)
    {
        // Process the user information
        // User::updateOrCreate(['national_id' => $userInfo['nationalId']], $userInfo);

        // Log the user information for debugging
        Log::info('User information processed successfully.');
    }

}
