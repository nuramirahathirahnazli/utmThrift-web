<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Order;
use App\Models\Seller;

class ReviewRatingController extends Controller
{   
    // Submit a review
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'item_id' => 'required|exists:items,id',
            'buyer_id' => 'required|exists:users,id',
            'seller_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $order = Order::find($validated['order_id']);

        if (strtolower($order->order_status) !== 'completed') {
            return response()->json(['message' => 'You can only review completed orders.'], 403);
        }


        // Confirm the item exists in that order (optional, depending on your DB design)
        if ($order->item_id != $validated['item_id']) {
            return response()->json(['message' => 'Item does not belong to the given order.'], 403);
        }

        // Optional: prevent duplicate review for the same item
        $alreadyReviewed = Review::where('order_id', $validated['order_id'])
            ->where('item_id', $validated['item_id'])
            ->where('buyer_id', $validated['buyer_id'])
            ->exists();

        if ($alreadyReviewed) {
            return response()->json(['message' => 'You have already reviewed this item.'], 409);
        }

        $review = Review::create($validated);

        return response()->json(['message' => 'Review submitted!', 'review' => $review], 201);
    }

    // Get all reviews for a seller
    public function getSellerReviews($sellerId)
    {
        $reviews = Review::with('buyer') // buyer = user who gives review
            ->where('seller_id', $sellerId)
            ->latest()
            ->get();

        $averageRating = Review::where('seller_id', $sellerId)->avg('rating');

         // Load seller and related user
        $seller = Seller::with('user')->where('user_id', $sellerId)->first();
            
        return response()->json([
            'average_rating' => $averageRating,
            'seller' => [
                'id' => $seller->id,
                'store_name' => $seller->store_name,
                'name' => $seller->user->name ?? null,
                'contact' => $seller->user->contact ?? null,
                'user_role' => $seller->user->user_role ?? null,
                'faculty' => $seller->user->faculty ?? null,
                'location' => $seller->user->location ?? null,
            ],
            'reviews' => $reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'buyer_id' => $review->buyer_id,
                    'buyer_name' => $review->buyer->name, 
                    'seller_id' => $review->seller_id,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'created_at' => $review->created_at->toDateTimeString(),
                ];
            }),
        ]);
    }


}
