<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Seller extends Model
{
    protected $fillable = [
        'user_id',
        'store_name',
        'matric_card_file',
        'verification_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
