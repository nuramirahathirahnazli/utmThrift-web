<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model {
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'condition',
        'image',
        'status',
        'user_id',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'category_id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'user_id', 'user_id');
    }

}
