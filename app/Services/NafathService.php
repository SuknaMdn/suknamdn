<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\Client\ConnectionException;

class NafathService
{
    private $baseUrl;
    private $apiKey;
    private $clientSecret;
    private $maxRetries;
    private $timeout;
    private $retryDelay;

    public function __construct()
    {
        $this->baseUrl = config('services.nafath.base_url', 'https://iam.elm.sa');
        $this->apiKey = config('services.nafath.api_key');
        $this->clientSecret = config('services.nafath.client_secret');
        $this->maxRetries = config('services.nafath.max_retries', 3);
        $this->timeout = config('services.nafath.timeout', 30);
        $this->retryDelay = config('services.nafath.retry_delay', 1000); // milliseconds
    }

    /**
     * Initiate Nafath verification with retry mechanism
     *
     * @param string $nationalId
     * @param string $dateOfBirth
     * @return array
     * @throws Exception
     */
    public function initiateVerification(string $nationalId, string $dateOfBirth): array
    {
        $attempt = 1;
        $lastException = null;

        while ($attempt <= $this->maxRetries) {
            try {
                Log::info("Attempting Nafath verification initiation - Attempt {$attempt}/{$this->maxRetries}");

                $response = Http::timeout($this->timeout)
                    ->withHeaders([
                        'x-api-key' => $this->apiKey,
                        'Content-Type' => 'application/json'
                    ])
                    ->retry($this->maxRetries, $this->retryDelay, function ($exception) {
                        return $exception instanceof ConnectionException;
                    })
                    ->post("{$this->baseUrl}/verify/init", [
                        'national_id' => $nationalId,
                        'date_of_birth' => $dateOfBirth,
                        'callback_url' => route('nafath.callback'),
                        'client_secret' => $this->clientSecret
                    ]);

                if ($response->successful()) {
                    Log::info('Nafath verification initiated successfully', [
                        'attempt' => $attempt,
                        'national_id' => substr($nationalId, 0, 4) . '****' // Log partial ID for security
                    ]);
                    return $response->json();
                }

                Log::warning('Nafath verification failed with response', [
                    'attempt' => $attempt,
                    'status' => $response->status(),
                    'body' => $response->json()
                ]);

                throw new Exception('Failed to initiate verification: ' . $response->status());

            } catch (ConnectionException $e) {
                $lastException = $e;
                Log::warning("Connection timeout on attempt {$attempt}", [
                    'error' => $e->getMessage(),
                    'attempt' => $attempt
                ]);

                if ($attempt === $this->maxRetries) {
                    Log::error('All retry attempts exhausted', [
                        'error' => $e->getMessage(),
                        'total_attempts' => $this->maxRetries
                    ]);
                    throw new Exception('Service unavailable after ' . $this->maxRetries . ' attempts. Please try again later.', 503);
                }

                // Wait before next retry
                usleep($this->retryDelay * 1000);
                $attempt++;
                continue;
            }
        }

        throw $lastException ?? new Exception('Unknown error occurred during verification');
    }

    /**
     * Check verification status with retry mechanism
     *
     * @param string $transactionId
     * @return array
     * @throws Exception
     */
    public function checkVerificationStatus(string $transactionId): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'x-api-key' => $this->apiKey,
                    'Content-Type' => 'application/json'
                ])
                ->retry($this->maxRetries, $this->retryDelay, function ($exception) {
                    return $exception instanceof ConnectionException;
                })
                ->get("{$this->baseUrl}/verify/status/{$transactionId}");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Nafath status check failed', [
                'transaction_id' => $transactionId,
                'status' => $response->status(),
                'response' => $response->json()
            ]);

            throw new Exception('Failed to check verification status: ' . $response->status());

        } catch (ConnectionException $e) {
            Log::error('Connection error during status check', [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage()
            ]);
            throw new Exception('Service unavailable. Please try again later.', 503);
        }
    }
}
