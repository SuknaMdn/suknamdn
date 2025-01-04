<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NafathService
{
    private $baseUrl;
    private $clientId;
    private $redirectUri;
    private $certificate;
    private $privateKey;

    public function __construct()
    {
        $this->baseUrl = config('services.nafath.base_url', 'https://iam.elm.sa/authservice');
        $this->clientId = config('services.nafath.client_id');
        $this->redirectUri = config('services.nafath.redirect_uri');
        $this->certificate = config('services.nafath.certificate_path');
        $this->privateKey = config('services.nafath.private_key_path');
    }

    public function initiateAuthentication()
    {
        $nonce = Str::uuid()->toString();
        $maxAge = time(); // Current time in seconds

        // Build the base authentication URL
        $baseAuthUrl = "{$this->baseUrl}/authorize?" . http_build_query([
            'scope' => 'openid',
            'response_type' => 'id_token',
            'response_mode' => 'form_post',
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'nonce' => $nonce,
            'ui_locales' => 'ar',
            'prompt' => 'login',
            'max_age' => $maxAge,
        ]);

        // Sign the request
        $signature = $this->signRequest($baseAuthUrl);

        // Add the state parameter with the signature
        $finalUrl = $baseAuthUrl . '&state=' . urlencode($signature);

        // Store nonce in session for validation
        session(['nafath_nonce' => $nonce]);
        session(['nafath_state' => hash('sha256', $signature)]);

        return $finalUrl;
    }

    public function verifyCallback($idToken, $state)
    {
        try {
            // Verify state matches stored state
            if (hash('sha256', $state) !== session('nafath_state')) {
                throw new \Exception('Invalid state parameter');
            }

            // Verify and decode the JWT token
            $tokenParts = explode('.', $idToken);
            if (count($tokenParts) !== 3) {
                throw new \Exception('Invalid token format');
            }

            // Verify signature using Elm's public certificate
            $this->verifyTokenSignature($idToken);

            // Decode the payload
            $payload = json_decode(base64_decode($tokenParts[1]), true);

            // Validate token
            $this->validateToken($payload);

            return $payload;
        } catch (\Exception $e) {
            Log::error('Nafath verification failed: ' . $e->getMessage());
            throw $e;
        }
    }

    private function signRequest($data)
    {
        // Load your private key
        $privateKey = openssl_pkey_get_private(file_get_contents($this->privateKey));

        // Create signature
        openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        return base64_encode($signature);
    }

    private function verifyTokenSignature($token)
    {
        // Implement signature verification using Elm's public certificate
        // This would use openssl_verify() with the appropriate public key
    }

    private function validateToken($payload)
    {
        // Validate expiration
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            throw new \Exception('Token has expired');
        }

        // Validate audience
        if (!isset($payload['aud']) || $payload['aud'] !== $this->redirectUri) {
            throw new \Exception('Invalid audience');
        }

        // Validate issuer
        if (!isset($payload['iss']) || $payload['iss'] !== 'https://www.iam.gov.sa/authservice') {
            throw new \Exception('Invalid issuer');
        }
    }
}
