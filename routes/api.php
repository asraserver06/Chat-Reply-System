<?php

use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes – secured by Laravel Sanctum
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->group(function () {

    // ── User Profile & Subscription ────────────────────────────────────────
    Route::get('/user',                            [UserController::class, 'profile']);
    Route::get('/user/subscription',               [UserController::class, 'subscription']);
    Route::get('/user/notifications',              [UserController::class, 'notifications']);
    Route::post('/user/notifications/read-all',    [UserController::class, 'markAllRead']);

    // ── Chats ──────────────────────────────────────────────────────────────
    Route::apiResource('chats', ChatController::class)->except('update');

    // ── Messages (nested under chats) ─────────────────────────────────────
    Route::get('/chats/{chat}/messages',           [MessageController::class, 'index']);
    Route::post('/chats/{chat}/messages',          [MessageController::class, 'store']);
    Route::delete('/messages/{message}',           [MessageController::class, 'destroy']);
});
