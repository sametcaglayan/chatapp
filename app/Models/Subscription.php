<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['device_id', 'product_id', 'receipt_token'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
