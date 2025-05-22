<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    use HasFactory;

    protected $table = 'itemcategories';

    protected $fillable = ['name']; 

    // Optional: define reverse relationship to items
    public function items()
    {
        return $this->hasMany(Item::class, 'category_id');
    }
}
