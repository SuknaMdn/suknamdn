<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Payment;
use App\Models\UnitOrder;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\PaymentRequest;

class UnitPaymentController extends Controller
{
        /**
     * Handle payment due to unit reservation with comprehensive error handling
     */
    public function handlePaymentDueToUnitReservation(Request $request): JsonResponse
    {
        try {
            // Validate request data
            $validatedData = $this->validatePaymentRequest($request);

            // Get authenticated user
            $user = $this->getAuthenticatedUser();

            // Start database transaction
            return DB::transaction(function () use ($validatedData, $user) {

                // Find and validate unit
                // $unit = $this->findAndValidateUnit($validatedData['unit_id']);

                // Create payment record
                $payment = $this->createPayment($validatedData, $user, $unit);

                // Create unit order
                $unitOrder = $this->createUnitOrder($validatedData, $user, $payment);

                // Log successful transaction
                Log::info('Payment processed successfully', [
                    'user_id' => $user->id,
                    'unit_id' => $validatedData['unit_id'],
                    'payment_id' => $payment->id,
                    'amount' => $validatedData['amount']
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'تم معالجة الدفع بنجاح',
                    'data' => [
                        'payment_id' => $payment->id,
                        'unit_order_id' => $unitOrder->id,
                        'transaction_id' => $payment->transaction_id,
                        'amount' => $payment->amount,
                        'currency' => $payment->currency,
                        'status' => $payment->status
                    ]
                ], 200);
            });

        } catch (ValidationException $e) {
            return $this->handleValidationError($e);
        } catch (\Exception $e) {
            return $this->handleGenericError($e, $request->all());
        }
    }

    /**
     * Validate payment request data
     */
    private function validatePaymentRequest(Request $request): array
    {
        try {
            return $request->validate([
                'unit_id' => [
                    'required',
                    'integer',
                    'min:1',
                    'exists:units,id'
                ],
                'amount' => [
                    'required',
                    'numeric',
                    'min:1',
                    'max:999999999.99'
                ],
                'currency' => [
                    'required',
                    'string',
                    'size:3',
                ],
                'payment_method' => [
                    'required',
                    'string',
                    'max:50',
                    'in:credit_card,debit_card,bank_transfer,wallet,cash,mada,stc_pay,apple_pay'
                ],
                'description' => [
                    'nullable',
                    'string',
                    'max:500'
                ],
                'payment_plan' => [
                    'nullable',
                    'string',
                ],
                'tax_exemption_status' => [
                    'nullable',
                    'boolean'
                ],
                'note' => [
                    'nullable',
                    'string',
                    'max:1000'
                ],
                'transaction_id' => [
                    'nullable',
                    'string',
                    'max:100',
                    'unique:payments,transaction_id'
                ],
                'status' => [
                    'nullable',
                    'string',
                    'in:initiated,paid,failed,pending,canceled,refunded'
                ]
            ], [
                // Custom error messages
                'unit_id.required' => 'معرف الوحدة مطلوب',
                'unit_id.exists' => 'الوحدة المحددة غير موجودة',
                'amount.required' => 'مبلغ الدفع مطلوب',
                'amount.min' => 'مبلغ الدفع يجب أن يكون أكبر من صفر',
                'amount.max' => 'مبلغ الدفع كبير جداً',
                'currency.required' => 'العملة مطلوبة',
                'currency.in' => 'العملة المحددة غير مدعومة',
                'payment_method.required' => 'طريقة الدفع مطلوبة',
                'payment_method.in' => 'طريقة الدفع المحددة غير مدعومة',
                'transaction_id.unique' => 'معرف المعاملة مستخدم مسبقاً',
                'status.in' => 'حالة الدفع غير صحيحة'
            ]);
        } catch (ValidationException $e) {
            Log::warning('Payment validation failed', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            throw $e;
        }
    }

    /**
     * Get authenticated user with error handling
     */
    private function getAuthenticatedUser(): User
    {
        $user = auth()->user();

        if (!$user) {
            throw new \Exception('المستخدم غير مصرح له بالوصول', 401);
        }

        // Check if user is active
        if (isset($user->status) && $user->status !== 'active') {
            throw new \Exception('حساب المستخدم غير نشط', 403);
        }

        return $user;
    }

    /**
     * Find and validate unit
     */
    public function findAndValidateUnit(int $unitId): array
    {
        $unit = Unit::find($unitId);

        if (!$unit) {
            return [
                'success' => false,
                'message' => 'الوحدة المحددة غير موجودة',
            ];
        }

        if ($unit->case == 1) {
            return [
                'success' => false,
                'message' => 'هذه الوحدة محجوزة بالفعل',
            ];
        }

        if ($unit->case == 2) {
            return [
                'success' => false,
                'message' => 'الوحدة مباعة من قبل مستخدم آخر',
            ];
        }

        return [
            'success' => true,
            'message' => 'هذة الوحدة ماحة',
            // 'unit' => $unit,
        ];
    }


    /**
     * Create payment record
     */
    private function createPayment(array $data, User $user, Unit $unit): Payment
    {
        try {
            $payment = new Payment([
                'amount' => $data['amount'],
                'currency' => $data['currency'],
                'payment_method' => $data['payment_method'],
                'description' => $data['description'] ?? 'دفع حجز وحدة عقارية',
                'payment_type' => 'property_deposit',
                'user_id' => $user->id,
                'transaction_id' => $data['transaction_id'] ?? $this->generateTransactionId(),
                'status' => $data['status'] ?? 'pending',
                'payable_type' => get_class($unit),
                'payable_id' => $unit->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $payment->save();

            return $payment;

        } catch (\Exception $e) {
            Log::error('Failed to create payment', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'unit_id' => $unit->id
            ]);
            throw new \Exception('فشل في إنشاء سجل الدفع', 500);
        }
    }

    /**
     * Create unit order record
     */
    private function createUnitOrder(array $data, User $user, Payment $payment): UnitOrder
    {
        try {
            $unitOrder = UnitOrder::create([
                'unit_id' => $data['unit_id'],
                'user_id' => $user->id,
                'payment_id' => $payment->id,
                'payment_plan' => $data['payment_plan'] ?? 'full_payment',
                'payment_method' => $data['payment_method'],
                'tax_exemption_status' => $data['tax_exemption_status'] ?? false,
                'payment_status' => $data['status'] ?? 'pending',
                'note' => $data['note'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return $unitOrder;

        } catch (\Exception $e) {
            Log::error('Failed to create unit order', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id,
                'user_id' => $user->id
            ]);
            throw new \Exception('فشل في إنشاء طلب الوحدة', 500);
        }
    }

    /**
     * Generate unique transaction ID
     */
    private function generateTransactionId(): string
    {
        return 'TXN_' . time() . '_' . random_int(1000, 9999);
    }

    /**
     * Handle validation errors
     */
    private function handleValidationError(ValidationException $e): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'بيانات غير صحيحة',
            'errors' => $e->errors(),
            'error_code' => 'VALIDATION_ERROR'
        ], 422);
    }

    /**
     * Handle generic errors
     */
    private function handleGenericError(\Exception $e, array $requestData = []): JsonResponse
    {
        // Log the error
        Log::error('Payment processing failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request_data' => $requestData,
            'user_id' => auth()->id()
        ]);

        // Determine response based on exception
        $statusCode = method_exists($e, 'getCode') && $e->getCode() ? $e->getCode() : 500;

        // Handle specific HTTP status codes
        switch ($statusCode) {
            case 400:
                $message = $e->getMessage();
                break;
            case 401:
                $message = 'غير مصرح بالوصول';
                break;
            case 403:
                $message = 'ممنوع الوصول';
                break;
            case 404:
                $message = 'المورد غير موجود';
                break;
            case 409:
                $message = $e->getMessage();
                break;
            default:
                $message = 'حدث خطأ في معالجة الدفع';
                $statusCode = 500;
        }

        return response()->json([
            'success' => false,
            'message' => $message,
            'error_code' => $this->getErrorCode($statusCode),
            'timestamp' => now()->toISOString()
        ], $statusCode);
    }

    /**
     * Get error code based on status
     */
    private function getErrorCode(int $statusCode): string
    {
        $errorCodes = [
            400 => 'BAD_REQUEST',
            401 => 'UNAUTHORIZED',
            403 => 'FORBIDDEN',
            404 => 'NOT_FOUND',
            409 => 'CONFLICT',
            422 => 'VALIDATION_ERROR',
            500 => 'INTERNAL_SERVER_ERROR'
        ];

        return $errorCodes[$statusCode] ?? 'UNKNOWN_ERROR';
    }

    /**
     * Get payment status for specific unit and user
     */
    public function getPaymentStatus(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'unit_id' => 'required|integer|exists:units,id'
            ]);

            $user = auth()->user();
            $payments = Payment::where('user_id', $user->id)
                ->where('payable_id', $request->unit_id)
                ->where('payable_type', Unit::class)
                ->with('payable')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $payments,
                'message' => 'تم جلب حالة الدفع بنجاح'
            ]);

        } catch (\Exception $e) {
            return $this->handleGenericError($e, $request->all());
        }
    }
}
