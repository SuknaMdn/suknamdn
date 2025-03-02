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

    /**
     * Create a new MFA request in Nafath
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createMfaRequest(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nationalId' => 'required|string',
                'service' => 'required|string',
                'requestId' => 'required|string',
                'local' => 'nullable|string|in:ar,en',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

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
        } catch (Exception $e) {
            Log::error('Nafath createMfaRequest error: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get the status of an MFA request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMfaRequestStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nationalId' => 'required|string',
                'transId' => 'required|string',
                'random' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $nationalId = $request->input('nationalId');
            $transId = $request->input('transId');
            $random = $request->input('random');

            $response = $this->nafathService->getMfaRequestStatus($nationalId, $transId, $random);

            if ($response['success']) {
                return response()->json($response['data'], 200);
            } else {
                return response()->json($response['error'], $response['error']['code'] ?? 500);
            }
        } catch (Exception $e) {
            Log::error('Nafath getMfaRequestStatus error: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle Nafath callback
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleCallback(Request $request)
    {
        // Log the incoming request for debugging
        Log::info('Nafath Callback Received:', $request->all());

        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'token' => 'required|string',
                'transId' => 'required|string',
                'requestId' => 'required|string',
            ]);

            if ($validator->fails()) {
                Log::error('Nafath Callback Validation Error: ', $validator->errors()->toArray());
                return response()->json($validator->errors(), 400);
            }

            $token = $request->input('token');
            $transId = $request->input('transId');
            $requestId = $request->input('requestId');

            // Verify and decode the JWT token
            $tokenData = $this->nafathService->verifyJwtToken($token);

            if (!$tokenData['success']) {
                Log::error('Nafath Token Verification Error: ', $tokenData['error']);
                return response()->json($tokenData['error'], 400);
            }

            $tokenInfo = $tokenData['data'];
            $status = $tokenInfo['status'] ?? null;

            // Handle the callback based on the status
            if ($status === 'COMPLETED') {
                // User accepted the request
                Log::info("User accepted the MFA request. Transaction ID: $transId");

                // Process user information if available
                if (isset($tokenInfo['userInfo'])) {
                    $userInfo = $tokenInfo['userInfo'];
                    Log::info('User Information:', $userInfo);
                    $this->processUserInfo($userInfo);
                }

                // Optionally broadcast an event
                // event(new NafathStatusUpdated($requestId, $transId, $status, $userInfo ?? null));
            } elseif ($status === 'REJECTED') {
                // User rejected the request
                Log::info("User rejected the MFA request. Transaction ID: $transId");

                // Optionally broadcast an event
                // event(new NafathStatusUpdated($requestId, $transId, $status, null));
            } else {
                Log::warning("Unexpected status in Nafath callback: $status");
            }

            // Return a success response to Nafath
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            Log::error('Nafath Callback Error: ' . $e->getMessage());
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    /**
     * Process user information received from Nafath
     *
     * @param array $userInfo
     * @return void
     */
    protected function processUserInfo($userInfo)
    {
        try {
            // User::updateOrCreate(['national_id' => $userInfo['nin'] ?? $userInfo['iqamaNumber']], [
            //     'name' => $userInfo['firstName'] . ' ' . $userInfo['familyName'],
            //     'email' => $userInfo['email'] ?? null,
            //     // Add other fields as needed
            // ]);

            // Log the user information for debugging
            Log::info('User information processed successfully.', $userInfo);
        } catch (Exception $e) {
            Log::error('Error processing user information: ' . $e->getMessage());
        }
    }
}
