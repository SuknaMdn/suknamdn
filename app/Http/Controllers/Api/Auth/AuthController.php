<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use SadiqSalau\LaravelOtp\Facades\Otp;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Otp\UserRegistrationOtp;
use HossamMonir\Msegat\Facades\Msegat;
use App\Services\OtpAuthService;
use App\Exceptions\OtpException;
use App\Models\DeviceToken;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Spatie\Permission\Models\Role;


class AuthController extends Controller
{

    protected $otpService;
    public $is_new_user = false;
    public function __construct(OtpAuthService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Request OTP with robust error handling
     */
    public function requestOtp(Request $request)
    {

        try {
            return $this->otpService->sendOtp($request->input('phone'));

        } catch (OtpException $e) {
            return match($e->getErrorCode()) {
                OtpException::ERROR_INVALID_PHONE =>
                    response()->json(['error' => 'Invalid Saudi phone number'], 400),
                OtpException::ERROR_TOO_MANY_ATTEMPTS =>
                    response()->json(['error' => 'Too many OTP attempts'], 429),
                default =>
                    response()->json(['error' => 'OTP request failed'], 500)
            };
        }
    }

        /**
     * Verify OTP with comprehensive error handling
     */
    public function verifyOtp(Request $request)
    {
        try {
            $validated = $request->validate([
                'phone' => 'required|string|min:9|max:15',
                'otp' => 'required|string|size:4',
                'device_token' => 'nullable|string',
                'device_type' => 'nullable|string|in:android,ios,web'
            ]);

            if ($this->otpService->verifyOtp($validated['phone'], $validated['otp'])) {
                $user = $this->otpService->authenticateUser($validated['phone']);
                $token = $user->createToken('auth_token', ['*'])->plainTextToken;

                // Check if the user already has the "user" role
                $role = Role::where('name', 'user')->first();
                if (!$user->roles->contains($role)) {
                    // Assign the "user" role to the authenticated user
                    $user->roles()->attach($role);
                    $user->is_new_user = true;
                }else {
                    $user->is_new_user = false;
                }

                // Store device token if provided
                if (!empty($validated['device_token'])) {
                    $this->storeDeviceToken($user, $validated['device_token'], $validated['device_type'] ?? 'unknown');
                }

                return response()->json([
                    'status' => true,
                    'is_new_user' => $user->is_new_user,
                    'message' => 'Successfully signed in',
                    'data' => [
                        'user' => $user->only(['id', 'username', 'phone', 'email', 'created_at']),
                        'token' => $token,
                        'token_type' => 'Bearer'
                    ]
                ], 200);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP'
            ], 422);

        } catch (OtpException $e) {
            Log::warning('OTP Verification Error', [
                'phone' => substr($request->input('phone'), -4),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Unexpected OTP Verification Error', [
                'phone' => substr($request->input('phone'), -4),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred',
                'error' => $e->getMessage()

            ], 500);
        }
    }

    /**
     * Store or update device token for push notifications
     */
    private function storeDeviceToken($user, $deviceToken, $deviceType)
    {
        try {
            DeviceToken::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'token' => $deviceToken
                ],
                [
                    'device_type' => $deviceType,
                    'updated_at' => now()
                ]
            );

            Log::info('Device token stored successfully', [
                'user_id' => $user->id,
                'device_type' => $deviceType
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to store device token', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

      /**
     * Update device token (separate endpoint)
     */
    public function updateDeviceToken(Request $request)
    {
        try {
            $validated = $request->validate([
                'device_token' => 'required|string',
                'device_type' => 'nullable|string|in:android,ios,web'
            ]);

            $user = auth()->user();
            $this->storeDeviceToken($user, $validated['device_token'], $validated['device_type'] ?? 'unknown');

            return response()->json([
                'status' => true,
                'message' => 'Device token updated successfully'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Failed to update device token', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update device token'
            ], 500);
        }
    }

        /**
     * Logout and remove device token
     */
    public function logout(Request $request)
    {
        try {
            $user = auth()->user();
            
            // Remove device token if provided
            if ($request->has('device_token')) {
                DeviceToken::where('user_id', $user->id)
                    ->where('token', $request->input('device_token'))
                    ->delete();
            }

            // Revoke current token
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Successfully logged out'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Logout error', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Logout failed'
            ], 500);
        }
    }
}
