<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class NafathService
{
    protected $client;

    public function __construct()
    {
        $config = config('nafath');
        $this->client = new Client([
            'base_uri' => 'https://nafath.api.elm.sa/',
            'headers' => [
                'APP-ID' => 'a99667c2',
                'APP-KEY' => '0ac6e0d1a3185a1afdfd1954ba9846af',
                'Content-Type' => 'application/json;charset=utf-8',
            ]
        ]);
    }
    // 0d47c960
    // f77ee9a3121448e244e24c08275f9081
    // live
    // 'APP-ID' => 'a99667c2',
    // 'APP-KEY' => '0ac6e0d1a3185a1afdfd1954ba9846af',


    /**
     * Create a new MFA request in Nafath
     *
     * @param string $nationalId User's national ID
     * @param string $service Service type (from the service types table)
     * @param string|null $requestId Optional unique identifier for the request
     * @param string $local Language (ar or en)
     * @return array Response with status and data
     */
    public function createMfaRequest($nationalId, $service, $local = 'en')
    {
        try {
            // Generate a UUID if none is provided
            $requestId = $this->generateUuid();
            // dd($requestId);
            $response = $this->client->post('api/v1/mfa/request', [
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

            Log::info('Nafath API Response:', [
                'requestId' => $requestId,
                'body' => $body
            ]);

            if (!empty($body)) {
                return [
                    'success' => true,
                    'data' => $data,
                    'requestId' => $requestId // Return the generated requestId
                ];
            } else {
                return [
                    'success' => false,
                    'error' => [
                        'message' => 'Empty response from Nafath API',
                    ],
                    'requestId' => $requestId
                ];
            }
        } catch (RequestException $e) {
            return $this->handleException($e, 'createMfaRequest', [
                'requestId' => $requestId
            ]);
        }
    }

    /**
     * Generate a UUID v4
     *
     * @return string
     */
    protected function generateUuid()
    {
        // Simple implementation of UUID v4
        if (function_exists('random_bytes')) {
            $data = random_bytes(16);
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $data = openssl_random_pseudo_bytes(16);
        } else {
            // Fallback if secure random functions are not available
            $data = '';
            for ($i = 0; $i < 16; $i++) {
                $data .= chr(mt_rand(0, 255));
            }
        }

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Output the 36 character UUID
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
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
            $response = $this->client->post('api/v1/mfa/request/status', [
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
        if (Cache::has('nafath_jwk')) {
            return Cache::get('nafath_jwk');
        }

        try {
            $response = $this->client->get('/api/v1/mfa/jwk');
            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);
            Cache::put('nafath_jwk', [
                'success' => true,
                'data' => $data,
            ], now()->addMinutes(10)); // Cache for 10 minutes

            return ['success' => true, 'data' => $data];
        } catch (RequestException $e) {
            return $this->handleException($e, 'retrieveJwk');
        }
    }

    public function verifyJwtToken($token)
    {
        try {
            // Get the JWK
            $jwkResponse = $this->retrieveJwk();

            if (!$jwkResponse['success']) {
                return $jwkResponse;
            }

            // Extract the JWK set from the response
            $jwks = $jwkResponse['data']['keys'] ?? null;

            if (!$jwks) {
                return [
                    'success' => false,
                    'error' => [
                        'message' => 'Invalid JWK set received from Nafath API',
                    ]
                ];
            }

            // Get the token header to identify which key to use
            $tokenParts = explode('.', $token);
            if (count($tokenParts) !== 3) {
                return [
                    'success' => false,
                    'error' => [
                        'message' => 'Invalid JWT token format',
                    ]
                ];
            }

            $headerJson = base64_decode(str_replace(['-', '_'], ['+', '/'], $tokenParts[0]));
            $header = json_decode($headerJson, true);

            // Get the key ID from the token header
            $kid = $header['kid'] ?? null;

            // Find the corresponding key in the JWK set
            $key = null;
            foreach ($jwks as $jwk) {
                if (isset($jwk['kid']) && $jwk['kid'] === $kid) {
                    $key = $jwk;
                    break;
                }
            }

            if (!$key) {
                return [
                    'success' => false,
                    'error' => [
                        'message' => 'Unable to find a matching JWK for the provided token',
                    ]
                ];
            }

            // Convert JWK to PEM format for verification
            $pem = $this->jwkToPem($key);

            if (!$pem) {
                return [
                    'success' => false,
                    'error' => [
                        'message' => 'Failed to convert JWK to PEM format',
                    ]
                ];
            }

            // Verify and decode the token
            $decodedToken = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key($pem, $header['alg']));

            // Convert object to array for consistent response
            $tokenData = json_decode(json_encode($decodedToken), true);

            return [
                'success' => true,
                'data' => $tokenData,
            ];
        } catch (\Firebase\JWT\ExpiredException $e) {
            Log::error('JWT token expired: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => [
                    'message' => 'Token has expired',
                    'details' => $e->getMessage(),
                ]
            ];
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            Log::error('JWT signature invalid: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => [
                    'message' => 'Invalid token signature',
                    'details' => $e->getMessage(),
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Failed to verify token: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => [
                    'message' => 'Failed to verify token: ' . $e->getMessage(),
                ]
            ];
        }
    }

    /**
     * Convert a JWK to PEM format for use with JWT verification
     *
     * @param array $jwk The JWK to convert
     * @return string|false The PEM string or false on failure
     */
    protected function jwkToPem($jwk)
    {
        // Only handling RSA keys for now
        if ($jwk['kty'] !== 'RSA') {
            Log::error('Unsupported key type: ' . $jwk['kty']);
            return false;
        }

        // Required parameters for RSA
        if (!isset($jwk['n']) || !isset($jwk['e'])) {
            Log::error('Missing required JWK parameters');
            return false;
        }

        // Decode the base64url encoded modulus and exponent
        $modulus = $this->base64UrlDecode($jwk['n']);
        $exponent = $this->base64UrlDecode($jwk['e']);

        // Convert modulus and exponent to binary
        $modulusHex = bin2hex($modulus);
        $exponentHex = bin2hex($exponent);

        // Create a resource from the modulus and exponent
        $rsa = openssl_pkey_new([
            'n' => hex2bin($modulusHex),
            'e' => hex2bin($exponentHex),
        ]);

        if ($rsa === false) {
            // Try alternative approach if openssl_pkey_new fails
            // Create PEM using details directly
            $modulus = $this->chopPKCS1Padding(base64_encode($modulus));
            $exponent = base64_encode($exponent);

            $pemKey = "-----BEGIN PUBLIC KEY-----\n" .
                     "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA" . $modulus . "\n" .
                     "IDAQAB\n" .
                     "-----END PUBLIC KEY-----";

            return $pemKey;
        }

        // Get public key details
        $details = openssl_pkey_get_details($rsa);
        if ($details === false) {
            Log::error('Failed to get public key details');
            return false;
        }

        // Return the PEM format
        return $details['key'];
    }

    /**
     * Decode a base64url encoded string
     *
     * @param string $input Base64url encoded input
     * @return string Decoded string
     */
    protected function base64UrlDecode($input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }

    /**
     * Remove PKCS1 padding from base64 encoded string
     *
     * @param string $str Base64 encoded string
     * @return string String with padding removed
     */
    protected function chopPKCS1Padding($str)
    {
        $str = base64_decode($str);
        $start = strpos($str, "\x00");
        if ($start !== false) {
            $str = substr($str, $start + 1);
        }
        return base64_encode($str);
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

            // Log the error
            Log::error("Nafath API Error in $method: Status Code $statusCode, Response: $body");

            // Parse body if it's valid JSON
            $decodedBody = json_decode($body, true);

            // Create a more readable error message
            $errorMessage = "Error {$statusCode} {$response->getReasonPhrase()}";

            if ($decodedBody && isset($decodedBody['message'])) {
                $errorMessage .= ": {$decodedBody['message']}";
            }

            // Update the error message in the response
            $errorDetails['error']['message'] = $errorMessage;
            $errorDetails['error']['details'] = $decodedBody;

            return $errorDetails;
        } else {
            Log::error("Nafath API Error in $method: " . $e->getMessage());
        }

        return $errorDetails;
    }
}
