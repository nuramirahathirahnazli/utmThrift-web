<?php

namespace App\Http\Controllers;

use Carbon\Carbon; 
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use App\Models\Seller;
use App\Models\Order;
use App\Models\Item;

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

    public function getSellerSales(Request $request, $sellerId)
    {
        $month = $request->query('month'); // e.g. 6 for June
        $year = $request->query('year');   // e.g. 2025

        $query = DB::table('orders')
            ->join('items', 'orders.item_id', '=', 'items.id')
            ->select(
                'orders.id as order_id',
                'items.name as item_name',
                'items.price',
                'orders.quantity',
                'orders.payment_method',
                'orders.receipt_image',
                'orders.created_at'
            )
            ->where('orders.seller_id', $sellerId)
            ->where('orders.order_status', 'completed');

        if ($month && $year) {
            $query->whereMonth('orders.created_at', $month)
                ->whereYear('orders.created_at', $year);
        }

        $sales = $query->orderBy('orders.created_at', 'desc')->get();
        
        $totalQuantity = 0;
        $totalRevenue = 0;

        foreach ($sales as $sale) {
            $totalQuantity += $sale->quantity;
            $totalRevenue += $sale->price * $sale->quantity;
        }

        $summary = [
            'month' => $month && $year
                ? Carbon::create()->month($month)->format('F') . ' ' . $year
                : 'All Time', // or use Carbon::now()->format('F Y') for current month
            'items_sold' => $totalQuantity,
            'total_revenue' => $totalRevenue,
        ];


        return response()->json([
            'summary' => $summary,
            'sales' => $sales,
        ]);
    }




}
