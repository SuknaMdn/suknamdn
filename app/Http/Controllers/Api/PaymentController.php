<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\MoyasarPaymentService;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Http\Requests\CreatePaymentRequest;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(MoyasarPaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Create a new payment using Moyasar.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createPayment(CreatePaymentRequest $request)
    {
        $paymentData = $request->all();

        try {

            // Find the unit first
            $unit = Unit::findOrFail($request->unit_id);

            $payment = new Payment([
                'amount' => $request->amount,
                'currency' => $request->currency,
                'payment_method' => $request->payment_method,
                'description' => $request->description,
                'payment_type' => 'property_deposit',
                'user_id' => auth()->user()->id,
            ]);

            // Associate the payment with the unit using polymorphism
            $payment->payable_type = get_class($unit);
            $payment->payable_id = $unit->id;

            // Save the payment
            $unit->payments()->save($payment);

            // Process the payment using Moyasar
            $moyasarPayment = $payment->processPayment($paymentData, $payment);

            return response()->json([
                'message' => 'Payment processed successfully',
                'payment' => $moyasarPayment
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Payment failed',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function createSTCPayment(Request $request) // TODO: remove this
    {
        $paymentResponse = $this->paymentService->createSTCPayment([
            'amount' => $request->amount,
            'currency' => "SAR",
            'description' => "stc test",
            'mobile' => $request->mobile,
            'callback_url' => config('app.url') . '/api/payments/webhook',
        ]);

        // Assuming $paymentResponse is the array you provided
        $transactionUrl = $paymentResponse['source']['transaction_url'];

        // Parse the URL to extract the query parameters
        parse_str(parse_url($transactionUrl, PHP_URL_QUERY), $queryParams);

        // Extract the otp_token
        $otpToken = $queryParams['otp_token'] ?? null;

        // Complete the payment with OTP
        // $paymentResult = $this->paymentService->completePaymentWithOtp($paymentResponse['id'], '12345', $otpToken);
        // Redirect the user to the STC Pay payment page
        return $paymentResponse;
    }

    /**
     * List all payments in the system.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listPayments()
    {
        $payments = Payment::all();

        return response()->json([
            'message' => 'Payments retrieved successfully',
            'payments' => $payments
        ], 200);
    }

    /**
     * Handle webhook updates from Moyasar.
     * Update payment status based on webhook event.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleWebhook(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'id' => 'required|string|exists:payments,transaction_id',
                'status' => 'required|string',
            ], [
                'id.required' => 'The transaction ID is required.',
                'id.string' => 'The transaction ID must be a string.',
                'id.exists' => 'The transaction ID does not exist.',
                'status.required' => 'The status is required.',
                'status.string' => 'The status must be a string.',
            ]);

            $transactionId = $validatedData['id'];
            $status = $validatedData['status'];

            // Find the payment by transaction ID
            $payment = Payment::where('transaction_id', $transactionId)->first();

            if ($payment) {

                $payment->status = $status;
                $payment->save();

                return response()->json(['message' => 'Payment status updated successfully'], 200);
            }

            return response()->json(['message' => 'Payment not found'], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update payment status',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
