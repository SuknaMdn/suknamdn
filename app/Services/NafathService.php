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
            $response = $this->client->post('/api/v1/mfa/request', [
                'query' => [
                    'local' => $local,
                    'requestId' => $requestId,
                ],
                'json' => [
                    'nationalId' => $nationalId,
                    'service' => $service,
                ],
            ]);

            return [
                'success' => true,
                'data' => json_decode($response->getBody(), true),
            ];
        } catch (RequestException $e) {
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
