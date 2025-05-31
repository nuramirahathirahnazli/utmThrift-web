<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemFavourite;

class ItemFavouriteController extends Controller
{
    // POST: /api/items/{id}/toggle-favourite
    public function toggleFavourite(Request $request, $id)
    {
        $user = $request->user();
        $item = Item::findOrFail($id); // Will return 404 if item does not exist

        $favourite = ItemFavourite::where('user_id', $user->id)
            ->where('item_id', $item->id)
            ->first();

        if ($favourite) {
            $favourite->delete();

            return response()->json([
                'message' => 'Item removed from favourites',
                'status' => 'removed'
            ]);
        } else {
            ItemFavourite::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);

            return response()->json([
                'message' => 'Item added to favourites',
                'status' => 'added'
            ]);
        }
    }

    // GET: /api/items/favourites
    public function getUserFavourites(Request $request)
    {
        $user = $request->user();

        $favourites = ItemFavourite::with('item')
            ->where('user_id', $user->id)
            ->get()
            ->map(function ($favourite) {
                return [
                    'id' => $favourite->item->id,
                    // include other item fields if needed
                ];
            });

        return response()->json($favourites);
    }
}
