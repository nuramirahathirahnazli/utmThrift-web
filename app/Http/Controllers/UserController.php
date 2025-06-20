<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class UserController extends Controller
{
    public function getProfile()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'contact' => $user->contact,
                'matric' => $user->matric,
                'user_type' => $user->user_type,
                'gender' => $user->gender,
                'location' => $user->location,
                'user_role' => $user->user_role,
                'profile_picture' => $user->profile_picture,
                'created_at' => $user->created_at->format('Y-m-d H:i:s'), 
            ]
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validatedData = $request->validate([
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:15',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'gender' => 'nullable|in:Male,Female',
            'location' => 'nullable|string|max:255',
            'user_role' => 'nullable|in:Student,Lecturer',
            'user_type' => 'required|string|in:Buyer,Seller',
        ]);

        // ✅ Upload to Cloudinary if image exists
        if ($request->hasFile('profile_picture')) {
            try {
                $uploadedFileUrl = Cloudinary::upload(
                    $request->file('profile_picture')->getRealPath(),
                    ['folder' => 'utmthrift/profile_pictures']
                )->getSecurePath();

                \Log::info('Profile image uploaded to Cloudinary: ' . $uploadedFileUrl);

                // ✅ Add Cloudinary URL to data being updated
                $validatedData['profile_picture'] = $uploadedFileUrl;
            } catch (\Exception $e) {
                \Log::error('Failed to upload profile picture: ' . $e->getMessage());
            }
        }

        // ✅ Update user with all validated data (including profile_picture if set)
        $user->update($validatedData);

        \Log::info('Updated User Data:', [
            'name' => $user->name,
            'contact' => $user->contact,
            'gender' => $user->gender,
            'location' => $user->location,
            'user_role' => $user->user_role,
            'profile_picture' => $user->profile_picture,
        ]);

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => $user,
        ])
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
    }


}
