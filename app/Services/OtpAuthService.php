<?php

namespace App\Services;

use App\Exceptions\OtpException;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use HossamMonir\Msegat\Facades\Msegat;

class OtpAuthService
{
    const OTP_EXPIRY = 5; // minutes
    const OTP_LENGTH = 4;
    const MAX_OTP_ATTEMPTS = 3;

    /**
     * Generate secure OTP
     */
    public function generateOtp(string $phoneNumber): string
    {
        // Validate and prepare phone number
        $formattedPhone = OtpException::prepareSaudiPhoneNumber($phoneNumber);

        // Validate phone number format
        if (!OtpException::validateSaudiPhoneNumber($formattedPhone)) {
            throw OtpException::log(
                'Invalid phone number',
                OtpException::ERROR_INVALID_PHONE,
                $phoneNumber
            );
        }

        // Limit OTP generation attempts
        // $attempts = Cache::get("otp_attempts_{$formattedPhone}", 0);
        // if ($attempts >= self::MAX_OTP_ATTEMPTS) {
        //     throw OtpException::log(
        //         'Too many OTP requests',
        //         OtpException::ERROR_TOO_MANY_ATTEMPTS,
        //         $phoneNumber
        //     );
        // }

        // Generate cryptographically secure OTP
        $otp = str_pad(random_int(0, pow(10, self::OTP_LENGTH) - 1), self::OTP_LENGTH, '0', STR_PAD_LEFT);

        // Cache OTP with attempt tracking
        Cache::put("otp_{$formattedPhone}", $otp, now()->addMinutes(self::OTP_EXPIRY));
        // Cache::put("otp_attempts_{$formattedPhone}", $attempts + 1, now()->addMinutes(30));

        return $otp;
    }

    /**
     * Send OTP securely
     */
    public function sendOtp(string $phoneNumber)
    {
        try {
            // Validate and prepare phone number
            $formattedPhone = OtpException::prepareSaudiPhoneNumber($phoneNumber);

            if (!OtpException::validateSaudiPhoneNumber($formattedPhone)) {
                throw OtpException::log(
                    'Invalid phone number',
                    OtpException::ERROR_INVALID_PHONE,
                    $phoneNumber
                );
            }

            $otp = $this->generateOtp($formattedPhone);

            // Send OTP via Msegat
            $response = Msegat::numbers([$formattedPhone])
            ->message('رمز الدخول : ' . $otp)
            ->sendWithDefaultSender();

            // $response = Msegat::getSenders();

            return response()->json([
                'status' => $response,
                'otp' => $otp
            ]);

        } catch (OtpException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'error_code' => $e->getCode()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred',
                'error_code' => 500
            ]);
        }
    }

    /**
     * Verify OTP with enhanced security and virtual account support
     */
    public function verifyOtp(string $phoneNumber, string $userOtp): bool
    {
        // --- BEGIN VIRTUAL ACCOUNT CHECK ---
        // Get virtual account credentials from environment variables
        $virtualPhone = env('VIRTUAL_OTP_PHONE');
        $virtualOtp = env('VIRTUAL_OTP_CODE');

        // Only proceed with virtual account check if both environment variables are set
        if ($virtualPhone && $virtualOtp) {
            // Format both phone numbers using the same prepareSaudiPhoneNumber method
            // to ensure consistent comparison with the '+' prefix
            $formattedInputPhone = OtpException::prepareSaudiPhoneNumber($phoneNumber);
            $formattedVirtualPhone = OtpException::prepareSaudiPhoneNumber($virtualPhone);

            // Check if the formatted input matches the formatted virtual phone and OTP
            if ($formattedInputPhone === $formattedVirtualPhone && $userOtp === $virtualOtp) {
                // Virtual account credentials match - bypass normal OTP verification
                return true;
            }
        }
        // --- END VIRTUAL ACCOUNT CHECK ---

        try {
            // Validate and prepare phone number
            $formattedPhone = OtpException::prepareSaudiPhoneNumber($phoneNumber);

            // Validate phone number format
            if (!OtpException::validateSaudiPhoneNumber($formattedPhone)) {
                throw OtpException::log(
                    'Invalid phone number',
                    OtpException::ERROR_INVALID_PHONE,
                    $phoneNumber
                );
            }

            // Validate OTP format
            if (strlen($userOtp) !== self::OTP_LENGTH || !is_numeric($userOtp)) {
                throw OtpException::log(
                    'Invalid OTP format',
                    OtpException::ERROR_GENERATION_FAILED,
                    $phoneNumber
                );
            }

            $cachedOtp = Cache::get("otp_{$formattedPhone}");

            if (!$cachedOtp) {
                throw OtpException::log(
                    'OTP expired',
                    OtpException::ERROR_OTP_EXPIRED,
                    $phoneNumber
                );
            }

            if ($cachedOtp !== $userOtp) {
                // Track invalid attempts
                $invalidAttempts = Cache::increment("otp_invalid_attempts_{$formattedPhone}");

                if ($invalidAttempts > 3) {
                    // Cache::forget("otp_{$formattedPhone}");
                    throw OtpException::log(
                        'Too many invalid attempts',
                        OtpException::ERROR_TOO_MANY_ATTEMPTS,
                        $phoneNumber
                    );
                }

                return false;
            }

            // Successfully verified, clear OTP and attempts
            // Cache::forget("otp_{$formattedPhone}");
            // Cache::forget("otp_attempts_{$formattedPhone}");
            // Cache::forget("otp_invalid_attempts_{$formattedPhone}");

            return true;
        } catch (OtpException $e) {
            // Handle known OTP exceptions
            throw $e;
        } catch (\Exception $e) {
            // Log unexpected exceptions
            Log::error('Unexpected error in verifyOtp', [
                'phoneNumber' => $phoneNumber,
                'userOtp' => $userOtp,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Throw a generic OtpException instead of returning a response
            // This maintains the expected return type contract (bool or exception)
            throw new OtpException('An unexpected error occurred during OTP verification.', 500, $e);
        }
    }

    /**
     * Create or find user securely
     */
    public function authenticateUser(string $phoneNumber): User
    {
        // Validate and prepare phone number
        $formattedPhone = OtpException::prepareSaudiPhoneNumber($phoneNumber);

        // Ensure phone number is valid
        if (!OtpException::validateSaudiPhoneNumber($formattedPhone)) {
            throw OtpException::log(
                'Invalid phone number',
                OtpException::ERROR_INVALID_PHONE,
                $phoneNumber
            );
        }

        return User::firstOrCreate(
            ['phone' => $formattedPhone],
            [
                'username' => 'User_' . Str::random(5),
                'password' => bcrypt(Str::random(40)),
            ]
        );
    }
}
