<?php

namespace App\ViewModels\Items;

use App\Models\Item;

class ItemViewModel {
    public function getAllItems() {
        return Item::all();
    }
}
