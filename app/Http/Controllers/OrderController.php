<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Item;
use App\Models\Order;
use App\Models\Itemscart;


class OrderController extends Controller
{
    public function checkoutMeetUp(Request $request)
    {
        $user = auth()->user();

        // Validate required inputs
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'nullable|integer|min:1'
        ]);

        $item = Item::findOrFail($request->item_id);
        $quantity = $request->quantity ?? 1;

        // Check if the item is available and quantity is enough
        if (strtolower($item->status) !== 'available') {
            return response()->json(['error' => 'Item is not available.'], 422);
        }

        if ($quantity > $item->quantity) {
            return response()->json(['error' => 'Requested quantity exceeds available stock.'], 422);
        }

        // Use transaction to ensure data integrity
        DB::beginTransaction();

        try {
            // Create order
            $order = Order::create([
                'buyer_id' => $user->id,
                'item_id' => $item->id,
                'seller_id' => $item->user_id,
                'quantity' => $quantity,
                'payment_method' => 'Meet Up',
                'order_status' => 'pending', // optional but recommended
            ]);

            // Update item quantity and status
            $item->quantity = $item->quantity - $quantity;
            if ($item->quantity <= 0) {
                $item->status = 'sold';  // mark as sold if no more stock
            }
            $item->save();

            // Remove item(s) from cart
            Itemscart::where('user_id', $user->id)
                ->where('item_id', $item->id)
                ->delete();

            DB::commit();

            // Return order info for next step (e.g., chat)
            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'seller_id' => $order->seller_id,
                'message' => 'Order created successfully. Proceed to chat with seller.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => 'Failed to create order. ' . $e->getMessage()
            ], 500);
        }
    }
 
    public function create(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'buyer_id' => 'required|integer|exists:users,id',
            'item_id' => 'required|integer|exists:items,id',
            'seller_id' => 'required|integer|exists:users,id',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string|in:Meet Up,COD,Online',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create order
        $order = new Order();
        $order->buyer_id = $request->buyer_id;
        $order->item_id = $request->item_id;
        $order->seller_id = $request->seller_id;
        $order->quantity = $request->quantity;
        $order->payment_method = $request->payment_method;
        $order->order_status = 'Pending'; // or your default status
        $order->save();

        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order,
        ], 201);
    }

    public function confirmOrder(Request $request, $orderId)
    {
        $user = auth()->user();

        // Find the order
        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found.'], 404);
        }

        // Optional: Check if current user is authorized to confirm this order
        if ($user->id !== $order->buyer_id && $user->id !== $order->seller_id) {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }

        // Update order status to 'completed'
        $order->order_status = 'completed';
        $order->save();

        // Update item status to 'sold'
        $item = Item::find($order->item_id);
        if ($item) {
            $item->status = 'sold';
            $item->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Order confirmed and item marked as sold.',
            'order' => $order,
            'item' => $item,
        ]);
    }

    public function getBuyerOrders()
    {
        $user = auth()->user();

        $orders = Order::with('item') // eager load item details
            ->where('buyer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders, 200);
    }
}