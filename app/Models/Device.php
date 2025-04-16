<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Device extends Authenticatable
{
    use HasApiTokens;


    protected $fillable = ['uuid', 'name', 'chat_credit'];


    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'device_id', 'uuid');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'device_id', 'uuid');
    }

    public function getSubscriptionStatusAttribute()
    {
        return $this->subscription->product_id ?? 'free';
    }
}
