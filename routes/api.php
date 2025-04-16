<?php

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuyController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\LoginController;

//Admin
Route::post('/login', [LoginController::class, 'login']);
Route::get('/admin/devices', [AdminController::class, 'device']);
Route::get('/admin/chat', [AdminController::class, 'chat']);
Route::get('/admin/chat/{device_id}', [AdminController::class, 'chat']);
Route::get('/admin/chatid/{chat_id}', [AdminController::class, 'getchat']);

//Mobil
Route::post('/auth', [AuthController::class, 'authenticate']);
Route::middleware(['auth:sanctum', 'device.match'])->group(function () {
    Route::post('/subscription', [SubscriptionController::class, 'store']);
    Route::post('/chat', [ChatController::class, 'talk']);
    Route::post('/buy', [BuyController::class, 'create']);
});
