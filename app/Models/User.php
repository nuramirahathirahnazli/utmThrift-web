<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'profile_picture',
        'name',
        'email',
        'password',
        'contact',
        'matric',
        'user_type',
        'gender',
        'location',
        'user_role',
        'verification_status',
        
];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'verification_status' => 'string',
    ];
    

     /**
     * Get the items for the user.
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    /**
     * Get the sellers for the user.
     */
    public function seller()
    {
        return $this->hasOne(Seller::class);
    }

    /**
     * Method to update the seller's verification status.
     *
     * @param string $status The new verification status ('approved' or 'rejected')
     * @return void
     */
    public function updateVerificationStatus($status)
    {
        $this->verification_status = $status;
        $this->save();
    }

    public function favouriteItems()
    {
        return $this->belongsToMany(Item::class, 'itemfavourites')->withTimestamps();
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }


}
