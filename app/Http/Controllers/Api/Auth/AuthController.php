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
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Spatie\Permission\Models\Role;


class AuthController extends Controller
{

    protected $otpService;

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
                'otp' => 'required|string|size:4'
            ]);

            if ($this->otpService->verifyOtp($validated['phone'], $validated['otp'])) {
                $user = $this->otpService->authenticateUser($validated['phone']);
                $token = $user->createToken('auth_token', ['*'])->plainTextToken;

                // Check if the user already has the "user" role
                $role = Role::where('name', 'user')->first();
                if (!$user->roles->contains($role)) {
                    // Assign the "user" role to the authenticated user
                    $user->roles()->attach($role);
                }

                return response()->json([
                    'status' => true,
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

    // إرسال OTP
    // public function sendOTP(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             // 'username' => 'required|string',
    //             'phone' => 'required|string',
    //             // 'email' => 'required|email',
    //             // 'firstname' => 'required|string',
    //             // 'country_code' => 'nullable|string',
    //             // 'lastname' => 'required|string'
    //         ]);

    //         // Send OTP via Msegat
    //         $response = Msegat::numbers([$request->phone])
    //         ->sendOTP('OTP');

    //         return response()->json([
    //             'status' => $response
    //         ]);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Failed to send OTP',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    // public function verifyOtp(Request $request)
    // {
    //     try {

    //         $request->validate([
    //             'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
    //             'otp' => 'required'
    //         ]);

    //         $otp = Otp::identifier($request->phone)->attempt($request->otp);

    //         if($otp['status'] != Otp::OTP_PROCESSED)
    //         {
    //             abort(403, __($otp['status']));
    //         }

    //         // Find the user by phone number
    //         $user = User::where('phone', $request->phone)->first();

    //         if (!$user) {
    //             return response()->json([
    //                 'message' => 'User not found'
    //             ], 404);
    //         }

    //         // Log the user in
    //         Auth::login($user);

    //         // Generate a token for the user
    //         $token = $user->createToken('authToken')->plainTextToken;

    //         return response()->json([
    //             'message' => 'Phone verified successfully',
    //             'status' => $otp['result'],
    //             'token' => $token,
    //             'user' => $user
    //         ]);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Verification failed',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    // public function verify(Request $request)
    // {
    //     return $request->all();
    // }
}
