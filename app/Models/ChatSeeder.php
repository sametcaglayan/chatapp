<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatSeeder extends Model
{
    use HasFactory;
    protected $fillable = ['user_message', 'bot_response'];
}
