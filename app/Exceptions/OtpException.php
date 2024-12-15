<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class OtpException extends Exception
{
    /**
     * Error codes for specific OTP-related issues
     */
    const ERROR_INVALID_PHONE = 'INVALID_PHONE';
    const ERROR_OTP_EXPIRED = 'OTP_EXPIRED';
    const ERROR_TOO_MANY_ATTEMPTS = 'TOO_MANY_ATTEMPTS';
    const ERROR_GENERATION_FAILED = 'OTP_GENERATION_FAILED';

    /**
     * Validate Saudi Arabian phone number
     */
    public static function validateSaudiPhoneNumber(string $phoneNumber): bool
    {
        // Remove any non-digit characters
        $sanitizedPhone = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Check if number starts with 966
        if (strpos($sanitizedPhone, '966') !== 0) {
            return false;
        }

        // Remove 966 prefix
        $phoneWithoutPrefix = substr($sanitizedPhone, 3);

        // Validate total length (9 digits after 966)
        if (strlen($phoneWithoutPrefix) !== 9) {
            return false;
        }

        // Validate first digit based on Saudi mobile prefixes
        $validPrefixes = ['5', '6', '7', '8', '9'];
        return in_array($phoneWithoutPrefix[0], $validPrefixes);
    }

    /**
     * Log and create OTP-related exceptions
     */
    public static function log(
        string $message,
        string $errorCode,
        ?string $phoneNumber = null,
        ?\Throwable $previous = null
    ): self {
        // Mask phone number for privacy
        $maskedPhone = $phoneNumber ? substr($phoneNumber, -4) : 'N/A';

        Log::warning('OTP Error', [
            'error_code' => $errorCode,
            'phone_last_4_digits' => $maskedPhone,
            'message' => $message
        ]);

        $exception = new self($message, 0, $previous);
        $exception->setErrorCode($errorCode);
        return $exception;
    }

    /**
     * Set custom error code
     */
    private string $errorCode;

    public function setErrorCode(string $errorCode): void
    {
        $this->errorCode = $errorCode;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * Prepare full Saudi phone number
     */
    public static function prepareSaudiPhoneNumber(string $phoneNumber): string
    {
        // Remove any non-digit characters
        $sanitizedPhone = preg_replace('/[^0-9]/', '', $phoneNumber);

        // If starts with 0, replace with 966
        if (strpos($sanitizedPhone, '0') === 0) {
            $sanitizedPhone = '966' . substr($sanitizedPhone, 1);
        }

        // If doesn't start with 966, add it
        if (strpos($sanitizedPhone, '966') !== 0) {
            $sanitizedPhone = '966' . $sanitizedPhone;
        }

        return '+' . $sanitizedPhone;
    }
}
