<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}
