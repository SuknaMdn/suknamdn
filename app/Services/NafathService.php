<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class NafathService
{
    protected $client;

    public function __construct()
    {
        $config = config('nafath');

        $this->client = new Client([
            'base_uri' => $config['api_url'],
            'headers' => [
                'APP-ID' => $config['app_id'],
                'APP-KEY' => $config['app_key'],
                'Content-Type' => 'application/json;charset=utf-8',
            ],
            'timeout' => $config['timeout'],
        ]);
    }

    public function createMfaRequest($nationalId, $service, $requestId, $local = 'ar')
    {
        try {
            // Add more debugging options to Guzzle
            $options = [
                'query' => [
                    'local' => $local,
                    'requestId' => $requestId,
                ],
                'json' => [
                    'nationalId' => $nationalId,
                    'service' => $service,
                    'callbackUrl' => config('nafath.callback_url'),
                ],
                'connect_timeout' => 10,    // Separate connect timeout
                'timeout' => 30,            // Overall timeout
                'debug' => false,            // Enable Guzzle debugging
                'verify' => true,           // SSL certificate verification
            ];

            Log::info("Attempting Nafath API request to: " . config('nafath.api_url') . '/api/v1/mfa/request');
            $response = $this->client->post('/api/v1/mfa/request', $options);

            return [
                'success' => true,
                'data' => json_decode($response->getBody(), true),
            ];
        } catch (RequestException $e) {
            // Enhance exception handling to capture connection issues
            if (!$e->hasResponse()) {
                Log::error("Nafath API connection error: " . $e->getMessage());
                return [
                    'success' => false,
                    'error' => [
                        'code' => 0,
                        'message' => 'Cannot connect to the Nafath API. Please check API URL and network connectivity.',
                        'details' => $e->getMessage(),
                    ],
                ];
            }
            return $this->handleException($e, 'createMfaRequest');
        }
    }

    public function getMfaRequestStatus($nationalId, $transId, $random)
    {
        try {
            $response = $this->client->post('/api/v1/mfa/request/status', [
                'json' => [
                    'nationalId' => $nationalId,
                    'transId' => $transId,
                    'random' => $random,
                ],
            ]);

            return [
                'success' => true,
                'data' => json_decode($response->getBody(), true),
            ];
        } catch (RequestException $e) {
            return $this->handleException($e, 'getMfaRequestStatus');
        }
    }

    protected function handleException(RequestException $e, $method)
    {
        $errorDetails = [
            'success' => false,
            'error' => [
                'code' => $e->getCode(),
                'message' => 'An error occurred while processing your request.',
                'details' => null,
            ],
        ];

        if ($e->hasResponse()) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            // Log the error for debugging
            Log::error("Nafath API Error in $method: Status Code $statusCode, Response: $body");

            // Add more details to the error response
            $errorDetails['error']['code'] = $statusCode;
            $errorDetails['error']['details'] = json_decode($body, true);
        } else {
            // Log the error for debugging
            Log::error("Nafath API Error in $method: " . $e->getMessage());
        }

        return $errorDetails;
    }
}
