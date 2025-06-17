<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Order;

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
    public function sellerReviews($sellerId)
    {
         $reviews = Review::with('buyer')
            ->where('seller_id', $sellerId)
            ->latest()
            ->get();

        return response()->json($reviews);
    }

    // Get average rating for a seller
    public function averageRating($sellerId)
    {
        $avg = Review::where('seller_id', $sellerId)->avg('rating');
        return response()->json(['seller_id' => $sellerId, 'average_rating' => round($avg, 2)]);
    }
}
