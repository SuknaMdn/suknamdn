<?php

namespace App\Services;

use Moyasar\Payment as MoyasarPayment;
use GuzzleHttp\Client;
use Exception;

class MoyasarPaymentService
{

    protected $client;
    protected $apiUrl = 'https://api.moyasar.com/v1/payments';
    protected $callbackUrl;

    public function __construct()
    {
        $this->callbackUrl = config('app.url') . '/api/payments/webhook';
        // Initialize Guzzle HTTP client
        $this->client = new Client([
            'base_uri' => $this->apiUrl,
            'auth' => [config('services.moyasar.api_key'), ''],
        ]);
    }


    /**
     * Create a Moyasar payment.
     *
     * @param array $data
     * @return MoyasarPayment
     * @throws \Exception
     */
    public function createPayment(array $data, $payment)
    {
        try {

            // Prepare the payload for the payment request
            $response = $this->client->post('', [
                'json' => [
                    'amount' => $data['amount'] * 100,
                    'currency' => $data['currency'],
                    'description' => $data['description'],
                    'source' => [
                        'type' => $data['payment_method'], // e.g., 'creditcard'
                        'number' => $data['number'],       // Card number or equivalent
                        'name' => $data['name'],           // Name on the card
                        'month' => $data['month'],         // Expiry month
                        'year' => $data['year'],           // Expiry year
                        'cvc' => $data['cvc'],             // CVC security code
                    ],
                    'callback_url' => $this->callbackUrl,  // Add the callback_url here
                ],
            ]);

            // Parse the JSON response
            $responseBody = json_decode($response->getBody(), true);

            // update the payment model with the redirect url
            $payment->redirect_url = $responseBody['source']['transaction_url'];
            $payment->save();

            return $responseBody;

        } catch (Exception $e) {
            throw new Exception('Payment request failed: ' . $e->getMessage());
        }
    }


    /**
     * Create a payment with STC Pay.
     *
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function createSTCPayment(array $data) // TODO: remove this
    {
        try {
            // Prepare the payload for the STC Pay payment request
            $response = $this->client->post('', [
                'json' => [
                    'amount' => $data['amount'] * 100,
                    'currency' => $data['currency'],
                    'description' => $data['description'],
                    'source' => [
                        "type" => "stcpay",
                        "mobile" => $data['mobile'],
                    ],
                    'callback_url' => $this->callbackUrl,
                ],
            ]);

            // Parse the JSON response
            $responseBody = json_decode($response->getBody(), true);

            // Handle the response (redirect the user to the payment page or save payment details)
            return $responseBody;

        } catch (\Exception $e) {
            throw new \Exception('Payment request failed: ' . $e->getMessage());
        }
    }


    public function completePaymentWithOtp($stcPayId, $otpValue, $otpToken) // TODO: remove this
    {

        try {
            // Create a Guzzle Client
            $stcPayClient = new Client([
                'base_uri' => 'https://api.moyasar.com/v1/stc_pays/' . $stcPayId . '/proceed',
                'auth' => [config('services.moyasar.api_key'), ''],
            ]);

            // Send GET request to Moyasar API with otp_value and otp_token
            $response = $stcPayClient->get('', [
                'query' => [
                    'otp_token' => $otpToken,
                    'otp_value' => $otpValue,
                ]
            ]);

            // Get the API response
            $responseBody = json_decode($response->getBody(), true);

            // Check the response (example)
            if ($responseBody['status'] == 'completed') {
                return response()->json(['message' => 'Payment successful']);
            } else {
                return response()->json(['message' => 'Payment failed']);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error completing payment: ' . $e->getMessage()], 500);
        }
    }

}
