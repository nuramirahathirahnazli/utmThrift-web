<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class SellerApplicationController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'matric_card_image' => 'required|image|mimes:jpeg,png,jpg|max:5048', // max 2MB
        ]);

        $user = Auth::user();

        // Check if the user has already applied
        if (Seller::where('user_id', $user->id)->where('verification_status', 'pending')->exists()) {
            return response()->json([
                'message' => 'You have already applied to become a seller. Please wait for approval.',
            ], 400);
        }

        // Upload to Cloudinary 
        $uploadedFileUrl = Cloudinary::upload($request->file('matric_card_image')->getRealPath(), [
            'folder' => 'matric_cards'
        ])->getSecurePath();

        // Save seller application
        Seller::create([
            'user_id' => $user->id,
            'store_name' => $request->store_name,
            'matric_card_image' => $uploadedFileUrl, 
            'verification_status' => 'pending',
        ]);

        // Update user type to PendingSeller
        $user->update(['user_type' => 'PendingSeller']);

        return response()->json([
            'message' => 'Seller application submitted successfully.',
        ], 200);
    }
}
