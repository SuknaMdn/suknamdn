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


    public function createMfaRequest(Request $request)
    {
        $nationalId = $request->input('nationalId');
        $service = $request->input('service');
        $requestId = $request->input('requestId');
        $local = $request->input('local', 'ar');

        $response = $this->nafathService->createMfaRequest($nationalId, $service, $requestId, $local);

        if ($response['success']) {
            return response()->json($response['data'], 200);
        } else {
            return response()->json($response['error'], $response['error']['code'] ?? 500);
        }
    }

    public function getMfaRequestStatus(Request $request)
    {
        $nationalId = $request->input('nationalId');
        $transId = $request->input('transId');
        $random = $request->input('random');

        $response = $this->nafathService->getMfaRequestStatus($nationalId, $transId, $random);

        if ($response['success']) {
            return response()->json($response['data'], 200);
        } else {
            return response()->json($response['error'], $response['error']['code'] ?? 500);
        }
    }
}
