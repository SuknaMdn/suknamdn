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
use GuzzleHttp\Client;
use Firebase\JWT\JWT;
use App\Models\Nafath;
use Firebase\JWT\Key;

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
                'local' => 'nullable|string|in:ar,en',
                'user_id' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $nationalId = $request->input('nationalId');
            $service = $request->input('service');
            $local = $request->input('local', 'ar');

            $response = $this->nafathService->createMfaRequest($nationalId, $service, $local);
            if ($response['success']) {
                // save national id to this user
                if ($request->has('user_id')) {
                    $user = User::find($request->input('user_id'));
                    if ($user) {
                        $user->national_id = $nationalId;
                        $user->save();
                        // save the transaction id
                        Nafath::create([
                            'transaction_id' => $response['data']['transId'],
                            'national_id' => $nationalId,
                            'user_id' => $user->id,
                            'request_id' => $response['requestId'],
                            'status' => 'PENDING',
                            'random_number' => $response['data']['random'],
                        ]);
                    }
                }
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
     * @return Response
     */
    public function handleCallback(Request $request)
    {
        // Log the incoming request for debugging
        Log::info('Nafath callback received', $request->all());

        // Extract data from the request
        $token = $request->input('token');
        $transId = $request->input('transId');
        $requestId = $request->input('requestId');

        // Verify the token
        $userData = $this->verifyToken($token);

        if (!$userData) {
            Log::error('Invalid JWT token received');
            // update the request status
            $this->updateRequestStatus($requestId, 'REJECTED');
            return response()->json(['status' => 'error', 'message' => 'Invalid token'], 400);
        }

        // Check the status from the JWT
        if ($userData['status'] === 'COMPLETED') {
            // User accepted the request, save the user data
            $this->saveUserData($userData, $transId, $requestId);

            // Return a success response
            return response()->json(['status' => 'success'], 200);
        } else if ($userData['status'] === 'REJECTED') {
            // User rejected the request
            Log::info('User rejected the authentication request', ['transId' => $transId]);

            // Update your database to reflect the rejection
            $this->updateRequestStatus($requestId, 'REJECTED');

            return response()->json(['status' => 'success'], 200);
        }

        // For other statuses, just acknowledge
        return response()->json(['status' => 'success'], 200);
    }



    /**
     * Verify the JWT token from Nafath
     *
     * @param string $token
     * @return array|false Decoded token payload or false on failure
     */
    protected function verifyToken($token)
    {
        try {
            // Fetch the JWK from Nafath
            $jwk = $this->fetchJwk();

            if (!$jwk) {
                Log::error('Failed to fetch JWK');
                return false;
            }

            // Convert JWK to PEM
            $publicKey = $this->jwkToPem($jwk);

            if (!$publicKey) {
                Log::error('Failed to convert JWK to PEM');
                return false;
            }

            // Parse the token to get the header
            $tokenParts = explode('.', $token);
            if (count($tokenParts) !== 3) {
                Log::error('Invalid JWT format');
                return false;
            }

            // Verify the token
            $payload = JWT::decode($token, new Key($publicKey, 'RS256'));

            // Convert the payload to an array
            return (array) $payload;

        } catch (\Exception $e) {
            Log::error('JWT verification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Fetch JWK from Nafath API
     *
     * @return array|false The first JWK or false on failure
     */
    protected function fetchJwk()
    {
        try {
            $client = new Client();
            $response = $client->get(config('services.nafath.base_url') . '/api/v1/mfa/jwk', [
                'headers' => [
                    'APP-ID' => config('services.nafath.api_id'),
                    'APP-KEY' => config('services.nafath.api_key'),
                    'Content-Type' => 'application/json'
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['keys']) && !empty($data['keys'])) {
                return $data['keys'][0]; // Return the first key
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Failed to fetch JWK: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Save user data to database
     *
     * @param array $userData Decoded JWT data
     * @param string $transId Transaction ID
     * @param string $requestId Request ID
     */
    protected function saveUserData($userData, $transId, $requestId)
    {
        // Find if we have a pending request with this ID
        $pendingRequest = Nafath::where('request_id', $requestId)
            ->where('trans_id', $transId)
            ->first();

        if (!$pendingRequest) {
            Log::warning('No pending request found for requestId: ' . $requestId);
            // You might want to create one here or handle this case

        }

        // Create or update the user record
        // Note: The exact fields will depend on what's in the JWT and your database structure
        $user = User::updateOrCreate(
            ['national_id' => $userData['nin'] ?? null],
            [
                'first_name' => $userData['firstName'] ?? null,
                'lastname'    => $userData['lastName'],
                'gender' => $userData['gender'] ?? null,
                'nationality' => $userData['nationality'] ?? null,
                'birth_date'  => $userData['birthgDate'],
                'birth_place' => $userData['placeOfBirth'],
                'social_status' => $userData['socialStatus'],
                'national_address' => $userData['nationalAddress'],
                'iqama_number' => $userInfo['iqamaNumber'] ?? $userData['nin'],
                'city'        => $userData['city'],
                'region_id'   => $userData['regionId'],
                'district_id' => $userData['districID'],
                'street_name' => $userData['streetName'],

            ]
        );

        // Update the request status
        $this->updateRequestStatus($requestId, 'COMPLETED', $user->id);

        // Log the successful authentication
        Log::info('User authenticated successfully', [
            'user_id' => $user->id,
            'national_id' => $userData['nin'] ?? null,
            'request_id' => $requestId
        ]);

        return $user;
    }

    /**
     * Update the request status in the database
     *
     * @param string $requestId
     * @param string $status
     * @param int|null $userId
     */
    protected function updateRequestStatus($requestId, $status, $userId = null)
    {
        $request = Nafath::where('request_id', $requestId)->first();

        if ($request) {
            $request->status = $status;
            if ($userId) {
                $request->user_id = $userId;
            }
            $request->completed_at = now();
            $request->save();
        }
    }
}
