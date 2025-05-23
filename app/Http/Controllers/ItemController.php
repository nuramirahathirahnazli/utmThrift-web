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

        $items = Item::latest()->take($limit)->get();

        return response()->json([
            'success' => true,
            'data' => $items,  // <-- use 'data' key for items
        ]);
    }


}
