<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\ViewModels\Items\ItemViewModel;

class ItemController extends Controller {
    protected $itemVM;

    public function __construct(ItemViewModel $itemVM) {
        $this->itemVM = $itemVM;
    }

    public function index() {
        $items = $this->itemVM->getAllItems(); // Fetch items
        return view('items.index', compact('items')); // Load Blade view
    }

    public function listItems(Request $request)
    {
        $limit = $request->query('limit', 10);

        $items = Item::with(['category', 'seller.user'])
            ->latest()
            ->take($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'price' => $item->price,
                    'condition' => $item->condition,
                    'image' => json_decode($item->image) ?: [], // decode JSON images or empty array
                    'category' => [
                        'name' => optional($item->category)->name ?? 'Unknown',
                    ],
                    'seller_name' => optional(optional($item->seller)->user)->name ?? 'Unknown',
                    'seller_id' => optional($item->seller)->user_id,
                    'quantity' => $item->quantity,
                    'status' => $item->status,
                    'created_at' => $item->created_at->toDateTimeString(),

                ];
            });

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }

    public function show($id)
    {
        $item = Item::with(['category', 'seller.user'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'item' => [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'price' => $item->price,
                'condition' => $item->condition,
                'images' => json_decode($item->image) ?: [],
                'category' => [
                    'id' => $item->category->id,
                    'name' => $item->category->name,
                ],
                'seller' => [
                    'id' => $item->seller->user_id,
                    'name' => $item->seller->user->name,
                    'store_name' => $item->seller->store_name,
                ],
                'created_at' => $item->created_at,
            ]
        ]);
    }



}
