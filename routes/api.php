<?php

use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes – secured by Laravel Sanctum
|--------------------------------------------------------------------------
*/

// ── Public: token issuance ────────────────────────────────────────────────
Route::post('/auth/login', function (Request $request): JsonResponse {
    $request->validate([
        'email'       => 'required|email',
        'password'    => 'required',
        'device_name' => 'required|string',
    ]);

    if (! Auth::attempt($request->only('email', 'password'))) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    $user  = Auth::user();
    $token = $user->createToken($request->device_name)->plainTextToken;

    return response()->json([
        'token' => $token,
        'user'  => [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'roles' => $user->getRoleNames(),
        ],
    ]);
});

// ── Protected ─────────────────────────────────────────────────────────────

Route::middleware(['auth:sanctum'])->group(function () {

    // ── Auth: logout ───────────────────────────────────────────────────────
    Route::post('/auth/logout', function (Request $request): JsonResponse {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully.']);
    });

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
