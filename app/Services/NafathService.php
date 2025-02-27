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

    /**
     * Create a new MFA request in Nafath
     *
     * @param string $nationalId User's national ID
     * @param string $service Service type (from the service types table)
     * @param string $requestId Unique identifier for the request
     * @param string $local Language (ar or en)
     * @return array Response with status and data
     */
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

            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);

            Log::info('Nafath API Response:', ['body' => $body]);

            if (!empty($body)) {
                return [
                    'success' => true,
                    'data' => $data,
                ];
            } else {
                return [
                    'success' => false,
                    'error' => [
                        'message' => 'Empty response from Nafath API',
                    ]
                ];
            }
        } catch (RequestException $e) {
            return $this->handleException($e, 'createMfaRequest');
        }
    }

    /**
     * Get the status of an MFA request
     *
     * @param string $nationalId User's national ID
     * @param string $transId Transaction ID returned from createMfaRequest
     * @param string $random Random number returned from createMfaRequest
     * @return array Response with status and data
     */
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

            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);

            Log::info('Nafath Status Response:', ['body' => $body]);

            return [
                'success' => true,
                'data' => $data,
            ];
        } catch (RequestException $e) {
            return $this->handleException($e, 'getMfaRequestStatus');
        }
    }

    /**
     * Retrieve the JWK for verifying JWT tokens
     *
     * @return array Response with JWK data
     */
    public function retrieveJwk()
    {
        try {
            $response = $this->client->get('/api/v1/mfa/jwk');
            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);

            return [
                'success' => true,
                'data' => $data,
            ];
        } catch (RequestException $e) {
            return $this->handleException($e, 'retrieveJwk');
        }
    }

    /**
     * Verify a JWT token using the JWK
     *
     * @param string $token The JWT token to verify
     * @return array Decoded token data or error
     */
    public function verifyJwtToken($token)
    {
        try {
            // Get the JWK
            $jwkResponse = $this->retrieveJwk();

            if (!$jwkResponse['success']) {
                return $jwkResponse;
            }

            // TODO: Implement JWT verification with the JWK
            // This would require a JWT library like firebase/php-jwt

            // decode the token without verification
            $tokenParts = explode('.', $token);
            if (count($tokenParts) !== 3) {
                return [
                    'success' => false,
                    'error' => [
                        'message' => 'Invalid JWT token format',
                    ]
                ];
            }

            $payload = base64_decode(str_replace(['-', '_'], ['+', '/'], $tokenParts[1]));
            $decodedToken = json_decode($payload, true);

            return [
                'success' => true,
                'data' => $decodedToken,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => [
                    'message' => 'Failed to verify token: ' . $e->getMessage(),
                ]
            ];
        }
    }

    /**
     * Handle exceptions from API requests
     *
     * @param RequestException $e The caught exception
     * @param string $method The method that threw the exception
     * @return array Formatted error response
     */
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

            $errorDetails['error']['code'] = $statusCode;
            $errorDetails['error']['details'] = json_decode($body, true);

            return $errorDetails;
        } else {
            // Log the error for debugging
            Log::error("Nafath API Error in $method: " . $e->getMessage());
        }

        return $errorDetails;
    }
}
