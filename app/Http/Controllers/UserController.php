<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Cloudinary\Cloudinary;
use Cloudinary\Api\Upload\UploadApi;

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
                'status' => $user->status,
                'created_at' => $user->created_at->format('Y-m-d H:i:s'), // Convert to readable string
            ]
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validatedData = $request->validate([
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:15',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'gender' => 'nullable|in:Male,Female',
            'location' => 'nullable|string|max:255',
            'status' => 'nullable|in:Student,Lecturer',
            'user_type' => 'required|string|in:Buyer,Seller',
        ]);

        // Handle Profile Picture Upload
        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            try {
                // Cloudinary upload logic
                $cloudinary = new Cloudinary();
                $upload = $cloudinary->uploadApi()->upload($image->getRealPath(), [
                    'folder' => 'utmthrift_pics', // Specify the folder in Cloudinary
                ]);

                // Log the Cloudinary response for debugging
                \Log::info('Cloudinary Upload Response:', ['response' => $upload]);

                // Get the Cloudinary URL from the response
                $profileImageUrl = $upload['secure_url'];

                // Save the URL to the user’s profile_picture column
                $user->profile_picture = $profileImageUrl;
            } catch (\Exception $e) {
                \Log::error('Error uploading image to Cloudinary: ' . $e->getMessage());
            }
        }

        $user->update($validatedData);

        // 🔹 Debug: Print new user data after update
        \Log::info('Updated User Data:', [
            'name' => $user->name,
            'contact' => $user->contact,
            'gender' => $user->gender,
            'location' => $user->location,
            'status' => $user->status,
            'profile_picture' => $user->profile_picture,
        ]);

        // Add CORS headers to the response
        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => $user,
        ])
        ->header('Access-Control-Allow-Origin', '*')  // Allow all origins, you can specify a domain if needed
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
    }



}
