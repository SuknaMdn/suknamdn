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
use Illuminate\Support\Facades\DB;
use Firebase\JWT\JWK;

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
            $nafathTable = Nafath::where('transaction_id', $transId)->first();
            if ($response['success']) {
                if ($nafathTable) {
                    $nafathTable->update([
                        'status' => $response['data']['status'],
                        'verified_at' => now(),
                    ]);
                }
                return response()->json($response['data'], 200);
                
            } else {
                if ($nafathTable) {
                    $nafathTable->update([
                        'status' => $response['data']['status'],
                    ]);
                }
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
        try {
            // Log the incoming request for debugging
            Log::info('Nafath callback received', $request->all());

            // Extract data from the request
            $token = $request->input('token');
            $transId = $request->input('transId');
            $requestId = $request->input('requestId');

            if (!$token || !$transId || !$requestId) {
                Log::error('Missing required fields in callback', $request->all());
                return response()->json(['status' => 'error', 'message' => 'Missing required fields'], 400);
            }

            // Verify the token
            // $userData = $this->verifyToken($token);

            // Verify and decode the JWT token
            $userData = $this->verifyAndDecodeToken($token);

            if (!$userData) {
                Log::error('Invalid JWT token received');
                // update the request status
                $this->updateRequestStatus($requestId, 'REJECTED');
                return response()->json(['status' => 'error', 'message' => 'Invalid token'], 400);
            }

            // Process based on status
            switch ($userData['status']) {
                case 'COMPLETED':
                    $this->handleCompletedRequest($userData, $transId, $requestId);
                    break;
                
                case 'REJECTED':
                    $this->handleRejectedRequest($transId, $requestId);
                    break;
                
                case 'EXPIRED':
                    $this->handleExpiredRequest($transId, $requestId);
                    break;
                
                default:
                    Log::info('Callback received with status: ' . $userData['status'], ['transId' => $transId]);
                    break;
            }

            // For other statuses, just acknowledge
            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            Log::error('Error processing Nafath callback', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            // Still return success to avoid retries from Nafath
            return response()->json(['status' => 'success'], 200);
        }
    }


    /**
     * Verify and decode JWT token
     *
     * @param string $token
     * @return array|null
     */
    private function verifyAndDecodeToken($token)
    {
        try {
            // Get JWK from Nafath
            $jwkData = $this->getJWK();
            if (!$jwkData) {
                Log::error('Failed to retrieve JWK');
                return null;
            }

            // Decode JWT header to get kid
            $headerData = json_decode(base64_decode(explode('.', $token)[0]), true);
            $kid = $headerData['kid'] ?? null;

            if (!$kid) {
                Log::error('JWT token missing kid in header');
                return null;
            }

            // Find the matching key
            $matchingKey = null;
            foreach ($jwkData['keys'] as $key) {
                if ($key['kid'] === $kid) {
                    $matchingKey = $key;
                    break;
                }
            }

            if (!$matchingKey) {
                Log::error('No matching JWK found for kid: ' . $kid);
                return null;
            }

            // Convert JWK to PEM format
            $publicKey = JWK::parseKey($matchingKey);

            // Decode and verify JWT
            $decoded = JWT::decode($token, new Key($publicKey, 'RS256'));

            return (array) $decoded;

        } catch (\Exception $e) {
            Log::error('JWT verification failed', [
                'error' => $e->getMessage(),
                'token' => substr($token, 0, 50) . '...'
            ]);
            return null;
        }
    }

    /**
     * Get JWK from Nafath API
     *
     * @return array|null
     */
    private function getJWK()
    {
        try {
            $client = new Client();
            $response = $client->get(config('services.nafath.base_url') . 'api/v1/mfa/jwk', [
                'headers' => [
                    'APP-ID' => config('services.nafath.api_id'),
                    'APP-KEY' => config('services.nafath.api_key'),
                    'Content-Type' => 'application/json'
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody(), true);
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Failed to get JWK', ['error' => $e->getMessage()]);
            return null;
        }
    }

        /**
     * Handle completed authentication request
     *
     * @param array $userData
     * @param string $transId
     * @param string $requestId
     */
    private function handleCompletedRequest($userData, $transId, $requestId)
    {
        Log::info('User completed authentication', ['transId' => $transId]);

        // Save user data
        $this->saveUserData($userData, $transId, $requestId);

        // Update request status
        $this->updateRequestStatus($requestId, 'COMPLETED', 'Authentication successful');
    }

        /**
     * Handle rejected authentication request
     *
     * @param string $transId
     * @param string $requestId
     */
    private function handleRejectedRequest($transId, $requestId)
    {
        Log::info('User rejected authentication', ['transId' => $transId]);
        $this->updateRequestStatus($requestId, 'REJECTED', 'User rejected authentication');
    }

    /**
     * Handle expired authentication request
     *
     * @param string $transId
     * @param string $requestId
     */
    private function handleExpiredRequest($transId, $requestId)
    {
        Log::info('Authentication request expired', ['transId' => $transId]);
        $this->updateRequestStatus($requestId, 'EXPIRED', 'Authentication request expired');
    }

    /**
     * Save user data from Nafath response
     *
     * @param array $userData
     * @param string $transId
     * @param string $requestId
     */
    private function saveUserData($userData, $transId, $requestId)
    {
        try {
            DB::beginTransaction();

            // Determine user type based on available data
            $userType = $this->determineUserType($userData);

            // my database structure expects the following fields:
            // user_id transaction_id national_id id_type full_name date_of_birth gender nationality status response_data request_id random_number verified_at expires_at

            // Prepare user data for saving
            $userDataToSave = [
                'nafath_request_id' => $requestId,
                'nafath_trans_id' => $transId,
                'user_type' => $userType,
                'authentication_status' => 'COMPLETED',
                'authenticated_at' => now(),
                'raw_data' => json_encode($userData),
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Add user-specific data based on type
            switch ($userType) {
                case 'national_id':
                    $userDataToSave = array_merge($userDataToSave, [
                        'national_id' => $userData['nin'] ?? null,
                        'first_name' => $userData['firstName'] ?? null,
                        'father_name' => $userData['fatherName'] ?? null,
                        'grandfather_name' => $userData['grandFatherName'] ?? null,
                        'family_name' => $userData['familyName'] ?? null,
                        'english_first_name' => $userData['englishFirstName'] ?? null,
                        'english_second_name' => $userData['englishSecondName'] ?? null,
                        'english_third_name' => $userData['englishThirdName'] ?? null,
                        'english_last_name' => $userData['englishLastName'] ?? null,
                        'gender' => $userData['gender'] ?? null,
                        'nationality' => $userData['nationality'] ?? null,
                        'nationality_code' => $userData['nationalityCode'] ?? null,
                        'date_of_birth_g' => $userData['dateOfBirthG'] ?? null,
                        'date_of_birth_h' => $userData['dateOfBirthH'] ?? null,
                        'id_issue_date_g' => $userData['idIssueDateG'] ?? null,
                        'id_issue_date_h' => $userData['idIssueDate'] ?? null,
                        'id_expiry_date_g' => $userData['idExpiryDateG'] ?? null,
                        'id_expiry_date_h' => $userData['idExpiryDate'] ?? null,
                        'id_version_number' => $userData['idVersionNumber'] ?? null,
                        'id_issue_place' => $userData['idIssuePlace'] ?? null,
                        'social_status_code' => $userData['socialStatusCode'] ?? null,
                        'social_status_desc' => $userData['socialStatusDesc'] ?? null,
                        'occupation_code' => $userData['occupationCode'] ?? null,
                        'place_of_birth' => $userData['placeOfBirth'] ?? null,
                        'passport_number' => $userData['passportNumber'] ?? null,
                        'is_minor' => $userData['isMinor'] ?? false,
                    ]);
                    break;

                case 'iqama':
                    $userDataToSave = array_merge($userDataToSave, [
                        'iqama_number' => $userData['iqamaNumber'] ?? null,
                        'first_name' => $userData['firstName'] ?? null,
                        'second_name' => $userData['secondName'] ?? null,
                        'third_name' => $userData['thirdName'] ?? null,
                        'last_name' => $userData['lastName'] ?? null,
                        'english_first_name' => $userData['englishFirstName'] ?? null,
                        'english_second_name' => $userData['englishSecondName'] ?? null,
                        'english_third_name' => $userData['englishThirdName'] ?? null,
                        'english_last_name' => $userData['englishLastName'] ?? null,
                        'gender' => $userData['gender'] ?? null,
                        'nationality_code' => $userData['nationalityCode'] ?? null,
                        'nationality_desc' => $userData['nationalityDesc'] ?? null,
                        'date_of_birth_g' => $userData['dateOfBirthG'] ?? null,
                        'date_of_birth_h' => $userData['dateOfBirthH'] ?? null,
                        'iqama_version_number' => $userData['iqamaVersionNumber'] ?? null,
                        'iqama_expiry_date_g' => $userData['iqamaExpiryDateG'] ?? null,
                        'iqama_expiry_date_h' => $userData['iqamaExpiryDateH'] ?? null,
                        'iqama_issue_date_g' => $userData['iqamaIssueDateG'] ?? null,
                        'iqama_issue_date_h' => $userData['iqamaIssueDateH'] ?? null,
                        'iqama_issue_place_code' => $userData['iqamaIssuePlaceCode'] ?? null,
                        'iqama_issue_place_desc' => $userData['iqamaIssuePlaceDesc'] ?? null,
                        'social_status_code' => $userData['socialStatusCode'] ?? null,
                        'occupation_code' => $userData['occupationCode'] ?? null,
                        'sponsor_name' => $userData['sponsorName'] ?? null,
                        'legal_status' => $userData['legalStatus'] ?? null,
                    ]);
                    break;

                case 'visa':
                    $userDataToSave = array_merge($userDataToSave, [
                        'first_name' => $userData['firstName'] ?? null,
                        'second_name' => $userData['secondName'] ?? null,
                        'third_name' => $userData['thirdName'] ?? null,
                        'last_name' => $userData['lastName'] ?? null,
                        'gender' => $userData['gender'] ?? null,
                        'nationality_code' => $userData['nationalityCode'] ?? null,
                        'nationality_desc' => $userData['nationalityDesc'] ?? null,
                        'date_of_birth_g' => $userData['birthgDate'] ?? null,
                        'date_of_birth_h' => $userData['birthhDate'] ?? null,
                    ]);
                    break;
            }

            // Save to database
            DB::table('nafath_authentications')->insert($userDataToSave);

            // Save national address if available
            if (isset($userData['nationalAddress']) && is_array($userData['nationalAddress'])) {
                $this->saveNationalAddress($userData['nationalAddress'], $requestId);
            }

            DB::commit();

            Log::info('User data saved successfully', [
                'requestId' => $requestId,
                'userType' => $userType
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to save user data', [
                'error' => $e->getMessage(),
                'requestId' => $requestId,
                'userData' => $userData
            ]);
        }
    }

    /**
     * Determine user type based on available data
     *
     * @param array $userData
     * @return string
     */
    private function determineUserType($userData)
    {
        if (isset($userData['nin'])) {
            return 'national_id';
        } elseif (isset($userData['iqamaNumber'])) {
            return 'iqama';
        } else {
            return 'visa';
        }
    }

    /**
     * Save national address data
     *
     * @param array $addresses
     * @param string $requestId
     */
    private function saveNationalAddress($addresses, $requestId)
    {
        try {
            foreach ($addresses as $address) {
                DB::table('nafath_national_addresses')->insert([
                    'nafath_request_id' => $requestId,
                    'street_name' => $address['streetName'] ?? null,
                    'city' => $address['city'] ?? null,
                    'additional_number' => $address['additionalNumber'] ?? null,
                    'district' => $address['district'] ?? null,
                    'unit_number' => $address['unitNumber'] ?? null,
                    'building_number' => $address['buildingNumber'] ?? null,
                    'post_code' => $address['postCode'] ?? null,
                    'location_coordinates' => $address['locationCoordinates'] ?? null,
                    'is_primary_address' => $address['isPrimaryAddress'] ?? false,
                    'city_id' => $address['cityId'] ?? null,
                    'region_id' => $address['regionId'] ?? null,
                    'district_id' => $address['districID'] ?? null,
                    'region_name_l2' => $address['regionNameL2'] ?? null,
                    'city_l2' => $address['cityL2'] ?? null,
                    'street_l2' => $address['streetL2'] ?? null,
                    'district_l2' => $address['districtL2'] ?? null,
                    'region_name' => $address['regionName'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to save national address', [
                'error' => $e->getMessage(),
                'requestId' => $requestId
            ]);
        }
    }

      /**
     * Update request status in database
     *
     * @param string $requestId
     * @param string $status
     * @param string $message
     */
    private function updateRequestStatus($requestId, $status, $message = null)
    {
        try {
            DB::table('nafaths')
                ->where('request_id', $requestId)
                ->update([
                    'status' => $status,
                    'updated_at' => now()
                ]);

            Log::info('Request status updated', [
                'requestId' => $requestId,
                'status' => $status,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update request status', [
                'error' => $e->getMessage(),
                'requestId' => $requestId,
                'status' => $status
            ]);
        }
    }
}
