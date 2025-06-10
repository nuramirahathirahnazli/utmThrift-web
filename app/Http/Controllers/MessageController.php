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

        $messages = Message::where(function ($q) use ($userId, $otherUserId) {
            $q->where('sender_id', $userId)->where('receiver_id', $otherUserId);
        })->orWhere(function ($q) use ($userId, $otherUserId) {
            $q->where('sender_id', $otherUserId)->where('receiver_id', $userId);
        })
        ->orderBy('created_at')
        ->get();

        return response()->json($messages);
    }

    public function store(Request $request)
    {
        logger('store request data:', $request->all());

        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'item_id' => 'nullable|exists:items,id',
            'message' => 'required|string',
            'sender_id' => 'sometimes|exists:users,id',
        ]);

        $senderId = $validated['sender_id'] ?? $request->user()->id;

        // Create original message
        $message = Message::create([
            'sender_id' => $senderId,
            'receiver_id' => $validated['receiver_id'],
            'item_id' => $validated['item_id'] ?? null,
            'message' => $validated['message'],
        ]);

        // Auto-reply condition: buyer sends message with "Meet Up"
        // if (
        //     str_contains(strtolower($validated['message']), 'meet') &&
        //     str_contains(strtolower($validated['message']), 'payment')
        // ) {
        //     // Auto-reply from seller
        //    // $autoReply = "Hi! Thank you for choosing to meet. Please let me know your availability and preferred location.";

        //     Message::create([
        //         'sender_id' => $validated['receiver_id'],      // seller replies
        //         'receiver_id' => $senderId,                    // buyer receives
        //         'item_id' => $validated['item_id'] ?? null,
        //         'message' => '',
        //     ]);

        //     logger('Auto-reply sent to buyer from seller.');
        // }

        return response()->json($message);
    }


    public function getUserMessages($buyerId)
    {
        $messages = Message::where('receiver_id', $buyerId)
                    ->with('sender:id,name') // optional: include sender name
                    ->orderBy('created_at', 'desc')
                    ->get();

        return response()->json($messages);
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

        $messages = Message::where('sender_id', $userId)
                    ->orWhere('receiver_id', $userId)
                    ->with(['sender:id,name', 'receiver:id,name'])
                    ->orderBy('created_at', 'desc')
                    ->get();

        $chatPartners = [];

        foreach ($messages as $msg) {
            $partner = $msg->sender_id === $userId ? $msg->receiver : $msg->sender;

            $key = $partner->id;

            if (!isset($chatPartners[$key])) {
                // Count unread messages from this partner
                $unreadCount = Message::where('sender_id', $partner->id)
                    ->where('receiver_id', $userId)
                    ->where('is_read', false)
                    ->count();

                $chatPartners[$key] = [
                    'sellerId' => $partner->id,
                    'name' => $partner->name,
                    'lastMessage' => $msg->message,
                    'lastUpdated' => $msg->updated_at,
                    'unreadCount' => $unreadCount,
                ];
            }
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

