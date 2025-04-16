<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Buy extends Model
{
    use HasFactory;
    protected $fillable = ['device_id', 'productId', 'receiptToken'];
}
