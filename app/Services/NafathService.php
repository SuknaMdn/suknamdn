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
            ]
        ]);
    }

    public function createMfaRequest($nationalId, $service, $requestId, $local = 'ar')
    {
        try {
            // Make the request
            $response = $this->client->post('/api/v1/mfa/request', [
                'query' => [
                    'local' => $local,
                    'requestId' => $requestId,
                ],
                'json' => [
                    'nationalId' => $nationalId,
                    'service' => $service,
                    'callbackUrl' => config('nafath.callback_url'),
                ],
            ]);

            // Get the response body
            $body = $response->getBody()->getContents();

            // Log the response
            Log::info('Nafath API Response:', ['body' => $body]);

            return json_decode($body, true);

        } catch (RequestException $e) {
            Log::error('Nafath API Request Exception:', [
                'message' => $e->getMessage(),
                'response' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);

            // If there's an HTTP response, return it as the error message
            if ($e->hasResponse()) {
                return [
                    'success' => false,
                    'error' => [
                        'message' => $e->getResponse()->getBody()->getContents(),
                        'code' => $e->getResponse()->getStatusCode(),
                    ]
                ];
            }

            // Return the general error
            return [
                'success' => false,
                'error' => [
                    'message' => 'An error occurred while processing the request.',
                    'code' => 500,
                ]
            ];
        } catch (\Exception $e) {
            Log::error('General Exception in createMfaRequest:', ['message' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => 500,
                ]
            ];
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
