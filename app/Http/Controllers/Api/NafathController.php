<?php

namespace App\Http\Controllers\Api;

use App\Events\NafathStatusUpdated;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NafathService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Exception;

class NafathController extends Controller
{

    protected $nafathService;

    public function __construct(NafathService $nafathService)
    {
        $this->nafathService = $nafathService;
    }

    public function initiateVerification(Request $request)
    {
        $validated = $request->validate([
            'national_id' => 'required|string',
            'date_of_birth' => 'required|date_format:Y-m-d',
        ]);

        try {
            $result = $this->nafathService->initiateVerification(
                $validated['national_id'],
                $validated['date_of_birth']
            );

            // Store transaction ID in session for later verification
            session(['nafath_transaction_id' => $result['transaction_id']]);

            return response()->json([
                'success' => true,
                'transaction_id' => $result['transaction_id'],
                'message' => 'Verification initiated successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to initiate verification'
            ], 500);
        }
    }

    public function handleCallback(Request $request)
    {
        try {
            $transactionId = $request->input('transaction_id');
            $status = $this->nafathService->checkVerificationStatus($transactionId);

            if ($status['status'] === 'VERIFIED') {
                // Update user verification status
                $user = User::where('national_id', $status['national_id'])->first();
                $user->update(['verified' => true]);

                return response()->json([
                    'success' => true,
                    'message' => 'Verification successful'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Verification failed or pending'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing verification callback'
            ], 500);
        }
    }

}
