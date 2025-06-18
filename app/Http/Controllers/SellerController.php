<?php

namespace App\Http\Controllers;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use App\Models\Seller;

class SellerController extends Controller
{
    public function uploadQRCode(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = auth()->user();
        $seller = Seller::where('user_id', $user->id)->first();

        if (!$seller) {
            return response()->json(['message' => 'Seller not found'], 404);
        }

        try {
            // Upload to Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('qr_code')->getRealPath(), [
                'folder' => 'utmthrift/qr_codes',
                'public_id' => 'seller_' . $seller->id . '_qr',
                'overwrite' => true,
            ])->getSecurePath();

            // Update DB
            $seller->qr_code_image = $uploadedFileUrl;
            $seller->save();

            return response()->json([
                'message' => 'QR Code uploaded successfully',
                'qr_code_image' => $uploadedFileUrl,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Upload failed', 'error' => $e->getMessage()], 500);
        }
    }

    // fetches seller’s QR code image.
    public function getQRCode($userId)
    {
        $seller = Seller::where('user_id', $userId)->first();

        if (!$seller) {
            \Log::info("Seller not found for user_id: $userId");
            return response()->json(['qr_code_image' => null], 404);
        }

        \Log::info("Fetched seller for user_id: $userId, QR Code: " . $seller->qr_code_image);

        return response()->json([
            'qr_code_image' => $seller->qr_code_image,
        ]);
    }



}
