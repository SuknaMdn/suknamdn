<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        $validatedData = $request->validated();
        // Update basic fields
        $user->firstname = $validatedData['firstname'] ?? $user->firstname;
        $user->lastname = $validatedData['lastname'] ?? $user->lastname;
        $user->email = $validatedData['email'] ?? $user->email;
        $user->phone = $validatedData['phone'] ?? $user->phone;

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Validate avatar file
            $request->validate([
                'avatar' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Remove old avatar if exists
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        // Handle addresses
        if (isset($validatedData['addresses'])) {
            foreach ($validatedData['addresses'] as $addressData) {
                if (isset($addressData['id'])) {
                    // Update existing address
                    $address = Address::where('id', $addressData['id'])
                        ->where('user_id', $user->id)
                        ->first();

                    if ($address) {
                        $address->update($addressData);
                    }
                } else {
                    // Create new address
                    $addressData['user_id'] = $user->id;
                    Address::create($addressData);
                }

                // If this address is set as default, unset others
                if (isset($addressData['is_default']) && $addressData['is_default']) {
                    Address::where('user_id', $user->id)
                        ->where('id', '!=', $addressData['id'] ?? null)
                        ->update(['is_default' => false]);
                }
            }
        }

        try {

            $user->save();

            // Load the updated user with addresses
            $user->load(['address' => function ($query) {
                $query->select('id', 'user_id', 'city_id', 'state_id', 'is_default')
                      ->with(['city:id,name', 'state:id,name']);
            }]);

            return response()->json([
                'message' => 'Profile updated successfully',
                'user' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update profile',
                'error' => $e->getMessage()
            ], 500);
        }

    }


    /**
     * Delete the authenticated user's account
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            // Get authenticated user
            $user = Auth::user();

            // Check if user exists (safety check)
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            DB::beginTransaction();

            try {

                // Revoke all tokens
                if ($user->tokens()) {
                    $user->tokens()->delete();
                }

                // Remove user roles
                if ($user->roles()) {
                    $user->roles()->detach();
                }

                // Delete the user account
                $user->delete();

                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Account deleted successfully'
                ], 200);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Account deletion failed during transaction', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Account deletion failed', [
                'user_id' => $user->id ?? 'unknown',
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to delete account',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
