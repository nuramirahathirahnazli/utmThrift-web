<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemCart; 

class ItemCartController extends Controller
{
    public function getCartItems($id)
    {
        $cartItems = ItemCart::with('item')->where('user_id', $id)->get();

        $formatted = $cartItems->map(function ($cartItem) {
            $item = $cartItem->item;

            return [
                'cart_id' => $cartItem->id,
                'item_id' => $item->id,
                'item_name' => $item->name,
                'price' => $item->price,
                'images' => json_decode($item->image) ?: [], 
                'quantity' => $cartItem->quantity,
                'created_at' => $cartItem->created_at->toDateTimeString(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formatted,
        ]);
    }

    public function addToCart(Request $request)
    {
        \Log::info('Add to cart request:', $request->all());
        
        $user = $request->user(); 
        \Log::info('Authenticated user:', ['id' => $user?->id]);

        $request->validate([
            'item_id' => 'required|exists:items,id',
            // remove quantity validation or ignore it entirely
        ]);

        $itemId = $request->input('item_id');
        $quantity = 1;  // Force quantity to 1 always

        // Check if item already in cart
        $cartItem = ItemCart::where('user_id', $user->id)->where('item_id', $itemId)->first();

        if ($cartItem) {
            // Item already in cart, do not increase quantity
            \Log::info('Item already in cart. Quantity stays 1.');
            return response()->json(['message' => 'Item is already in your cart']);
        } else {
            try {
                \Log::info('Creating new cart item');
                $cart = ItemCart::create([
                    'user_id' => $user->id,
                    'item_id' => $itemId,
                    'quantity' => $quantity,
                ]);
                \Log::info('Cart created: ', $cart->toArray());
            } catch (\Exception $e) {
                \Log::error('Cart creation failed: ' . $e->getMessage());
                return response()->json(['error' => 'Failed to add to cart'], 500);
            }
        }

        return response()->json(['message' => 'Item added to cart']);
    }


   
}
