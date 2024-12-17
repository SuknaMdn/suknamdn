<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\State;
use App\Models\User;
use App\Models\Address;
class AddressController extends Controller
{
    public function getUserAddress(Request $request)
    {
        try {

            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'message' => 'Unauthorized',
                    'error' => 'User not authenticated'
                ], 401);
            }
            $address = Address::where('user_id', $user->id)
                ->with(['city:id,name', 'state:id,name'])
                ->select('id', 'user_id', 'city_id', 'state_id', 'is_default')
                ->get();

            return response()->json([
                'address' => $address
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Authentication error',
                'error' => $e->getMessage()
            ], 401);
        }
    }

    public function getCities()
    {
        try {
            $cities = City::where('status', 1)->select('id', 'name', 'country','status')->get();
            $data = [
                'cities' => $cities
            ];
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAreas(Request $request)
    {
        try {
            $areas = State::where('city_id', $request->city_id)->select('id', 'name', 'status')->get();
            $data = [
                'areas' => $areas
            ];
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
