<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\MoyasarPaymentService;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Http\Requests\CreatePaymentRequest;
use App\Models\UnitOrder;
use App\Notifications\Developer\UnitOrderNotification;
use App\Settings\GeneralSettings;

use Carbon\Carbon;

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
    public function createPayment(CreatePaymentRequest $request, GeneralSettings $settings)
    {
        $paymentData = $request->all();
        // Retrieve the timeout days from the settings class
        $timeoutDays = $settings->payment_timeout_days;

        $user = auth()->user();

        try {

            // Find the unit first
            $unit = Unit::findOrFail($request->unit_id);
            // التحقق من ان الوحدة غير مباعة
            if ($unit->case == 1) {
                return response()->json([
                    'message' => 'هذة الوحدة نباعة بالفعل',
                ], 400);
            }
            // التحقق من وجود عملية دفع معلقة لنفس الوحدة
            $pendingPayment = Payment::where([
                'user_id' => $user->id,
                'payable_type' => get_class($unit),
                'payable_id' => $request->unit_id,
                'status' => 'initiated',
            ])->first();

            // if ($pendingPayment) {
            //     return response()->json([
            //         'message' => 'يوجد عملية دفع معلقة لهذه الوحدة',
            //         'payment_data' => $pendingPayment
            //     ], 400);
            // }

            // Check if there's an order in 'processing' status or created more than timeout days ago
            $unitOrder = UnitOrder::where('unit_id', $request->unit_id)
            ->where(function ($query) use ($timeoutDays) {
                $query->where('payment_status', 'processing')
                    ->orWhere('created_at', '<', Carbon::now()->subDays($timeoutDays));
            })
            ->first();

            // if ($unitOrder) {
            //     return response()->json([
            //         'message' => 'يوجد طلب حجز لهذه الوحدة',
            //         'order_data' => $unitOrder
            //     ], 400);
            // }

            // Create a new payment
            $payment = new Payment([
                'amount' => $request->amount,
                'currency' => $request->currency,
                'payment_method' => $request->payment_method,
                'description' => $request->description,
                'payment_type' => 'property_deposit',
                'user_id' => $user->id,
            ]);

            // Associate the payment with the unit using polymorphism
            $payment->payable_type = get_class($unit);
            $payment->payable_id = $unit->id;

            // Save the payment
            $unit->payments()->save($payment);

            if ($payment) {
                // create a new order
                UnitOrder::create([
                    'unit_id' => $request->unit_id,
                    'user_id' => $user->id,
                    'payment_id' => $payment->id,
                    'payment_plan' => $request->payment_plan,
                    'payment_method' => $request->payment_method,
                    "tax_exemption_status" => $request->tax_exemption_status,
                    "payment_status" => "pending",
                    "note" => $request->note,
                ]);
            }

            // Process the payment using Moyasar
            $moyasarPayment = $payment->processPayment($paymentData, $payment);

            return response()->json([
                'message' => 'Payment processed successfully',
                'payment' => $moyasarPayment,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Payment failed',
                'error' => $e->getMessage()
            ], 400);
        }
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

                // Update the order status if the payment is successful
                if ($payment->status === "paid") {
                    $unitOrder = UnitOrder::where('payment_id', $payment->id)->first();
                    $unitOrder->payment_status = 'paid';
                    $unitOrder->save();

                    $unit = $unitOrder->unit;
                    if ($unit) {
                        $project = $unit->project;
                        // send Notification to the Developer
                        $orderDetails = [
                            'unit_name'    => $unit->title,
                            'unit_id'      => $unitOrder->unit_id,
                            'developer_id' => $project->developer_id,
                        ];
                        $project->developer->user->notify(new UnitOrderNotification($orderDetails));
                    } else {
                        return response()->json([
                            'message' => 'Unit not found',
                        ], 404);
                    }

                }
                return response()->json([
                    'message' => 'Payment status updated successfully',
                    'payment' => $payment
                ], 200);
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
        $payments = Payment::where('user_id', auth()->id())->get();

        $payments->map(function ($payment) {
            $payment->unit = [
                'id' => $payment->payable_id,
                'title' => optional($payment->payable)->title,
            ];
            unset($payment->payable);
        });

        return response()->json([
            'message' => 'Payments retrieved successfully',
            'payments' => $payments
        ], 200);
    }

    /**
     * Get a payment by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPayment($id)
    {
        $payment = Payment::find($id);
        if ($payment) {
            $payment->unit = [
                'id' => $payment->payable_id,
                'title' => optional($payment->payable)->title,
            ];
            unset($payment->payable);
        }
        // check if payment exists and the user is owner of the payment
        if (!$payment || $payment->user_id !== auth()->id()) {
            return response()->json(['message' => 'Payment not found'], 404);
        }
        if ($payment) {
            return response()->json([
                'message' => 'Payment retrieved successfully',
                'payment' => $payment
            ], 200);
        }

        return response()->json(['message' => 'Payment not found'], 404);
    }

    public function handelReturnData(Request $request)
    {
        $data = $request->all();
        return response()->json([
            'data' => $data
        ], 200);
    }
}
