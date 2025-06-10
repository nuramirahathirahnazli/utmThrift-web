<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'item_id',
        'seller_id',
        'quantity',
        'payment_method',
        'order_status',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
