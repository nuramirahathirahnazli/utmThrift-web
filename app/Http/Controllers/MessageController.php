<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $otherUserId = $request->query('with_user_id');
        $itemId = $request->query('item_id'); // New

        $query = Message::where(function ($q) use ($userId, $otherUserId) {
                $q->where('sender_id', $userId)->where('receiver_id', $otherUserId);
            })
            ->orWhere(function ($q) use ($userId, $otherUserId) {
                $q->where('sender_id', $otherUserId)->where('receiver_id', $userId);
            });

        if ($itemId) {
            $query->where('item_id', $itemId);
        }

        $messages = $query->orderBy('created_at')->get();

        return response()->json($messages);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'item_id' => 'nullable|exists:items,id',
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' => $request->user()->id,
            'receiver_id' => $validated['receiver_id'],
            'item_id' => $validated['item_id'] ?? null,
            'message' => $validated['message'],
        ]);

        return response()->json($message);
    }

    public function getSellerMessages($sellerId)
    {
        $messages = Message::where('receiver_id', $sellerId)
                    ->with('sender:id,name') // optional: include sender name
                    ->orderBy('created_at', 'desc')
                    ->get();

        return response()->json($messages);
    }

    public function getUnreadCount(Request $request)
    {
        $userId = $request->user()->id;

        $unreadCount = Message::where('receiver_id', $userId)
                        ->where('is_read', false)
                        ->distinct('sender_id')
                        ->count('sender_id');

        return response()->json(['unreadCount' => $unreadCount]);
    }


    public function getChatList(Request $request)
    {
        $userId = $request->user()->id;

        // Include item relationship too
        $messages = Message::where('sender_id', $userId)
                    ->orWhere('receiver_id', $userId)
                    ->with(['sender:id,name', 'receiver:id,name', 'item:id,name']) // <--- moved here
                    ->orderBy('created_at', 'desc')
                    ->get();

        $chatPartners = [];

        foreach ($messages as $msg) {
            $partner = $msg->sender_id === $userId ? $msg->receiver : $msg->sender;
            $itemId = $msg->item?->id;

            // Count unread messages for this partner & item (where current user is receiver)
            $unreadCount = Message::where('sender_id', $partner->id)
                ->where('receiver_id', $userId)
                ->where('item_id', $itemId)
                ->where('is_read', false)
                ->count();

            $chatPartners[$partner->id . '_' . $itemId] = [
                'sellerId' => $partner->id,
                'name' => $partner->name,
                'itemId' => $itemId,
                'itemName' => $msg->item?->name ?? 'Unknown Item',
                'unreadCount' => $unreadCount,
            ];
        }

        return response()->json(array_values($chatPartners));
    }

    public function markAsRead(Request $request)
    {
        $authenticatedUserId = $request->user()->id;

        $validated = $request->validate([
            'userId' => 'required|integer|exists:users,id',  // the other chat user (partner)
            'userType' => 'required|string|in:buyer,seller',
            'itemId' => 'nullable|integer|exists:items,id',
        ]);

        $chatPartnerId = $validated['userId'];
        $itemId = $validated['itemId'] ?? null;

        // Mark messages received by authenticated user from the chat partner
        $query = Message::where('receiver_id', $authenticatedUserId)
                        ->where('sender_id', $chatPartnerId)
                        ->where('is_read', false);

        if ($itemId !== null) {
            $query->where('item_id', $itemId);
        }

        $updatedCount = $query->update(['is_read' => true]);

        return response()->json([
            'message' => 'Messages marked as read',
            'updated_count' => $updatedCount,
        ]);

    }

}

