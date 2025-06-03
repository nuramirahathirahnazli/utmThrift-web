<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'item_id',
        'message',
        'is_read', 
    ];

    protected $casts = [
        'is_read' => 'boolean',  // Optional, helps Laravel treat it as bool
    ];

    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver() {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function item() {
        return $this->belongsTo(Item::class);
    }
}

