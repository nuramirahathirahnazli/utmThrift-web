<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'start_time',
        'end_time',
        'location',
        'poster',
    ];

    public function getPosterUrlAttribute()
    {
        return $this->poster ? url('event-image/' . $this->poster) : null;
    }


}
