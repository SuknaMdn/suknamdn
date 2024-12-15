<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        try {
            $user->save();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update profile',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }
}
