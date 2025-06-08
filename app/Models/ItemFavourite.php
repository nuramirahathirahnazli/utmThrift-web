<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemFavourite extends Model
{
    protected $table = 'itemfavourites';
    
    protected $fillable = ['user_id', 'item_id'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
