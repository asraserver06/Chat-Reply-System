<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MessageManagementController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SubscriptionManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\MessageController as UserMessageController;
use App\Http\Controllers\User\SubscriptionController as UserSubscriptionController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Landing page
Route::get('/', function () {
    return view('welcome');
});

// Include auth routes (login, register, password reset)
require __DIR__.'/auth.php';


/*
|--------------------------------------------------------------------------
| Dashboard Route - Authenticated & Verified
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    if (auth()->user()->hasRole('Admin')) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



/*
|--------------------------------------------------------------------------
| Routes Protected by Auth
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User management
    Route::middleware(['permission:manage-users'])->group(function () {
        Route::get('/users',          [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/{user}',   [UserManagementController::class, 'show'])->name('users.show');
        Route::delete('/users/{user}',[UserManagementController::class, 'destroy'])->name('users.destroy');
    });

    // Chat / Message management
    Route::get('/messages',            [MessageManagementController::class, 'index'])->name('messages.index');
    Route::get('/messages/{chat}',     [MessageManagementController::class, 'show'])->name('messages.show');

    // Subscription management
    Route::get('/subscriptions', [SubscriptionManagementController::class, 'index'])->name('subscriptions.index');
});


/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
|
| Only users with "User" role can access these routes.
|
*/
Route::middleware(['auth', 'verified', 'role:User'])->prefix('user')->name('user.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Chat threads
    Route::get('/chats',                        [UserMessageController::class, 'index'])->name('chats.index');
    Route::post('/chats',                       [UserMessageController::class, 'store'])->name('chats.store');
    Route::get('/chats/{chat}',                 [UserMessageController::class, 'show'])->name('chats.show');
    Route::delete('/chats/{chat}',              [UserMessageController::class, 'destroy'])->name('chats.destroy');
    Route::post('/chats/{chat}/send',           [UserMessageController::class, 'sendMessage'])->name('chats.send');
    Route::delete('/messages/{message}',        [UserMessageController::class, 'deleteMessage'])->name('messages.destroy');

    // Subscription
    Route::get('/subscription',         [UserSubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('/subscription',        [UserSubscriptionController::class, 'subscribe'])->name('subscription.subscribe');
    Route::post('/subscription/cancel', [UserSubscriptionController::class, 'cancel'])->name('subscription.cancel');
    Route::post('/subscription/resume', [UserSubscriptionController::class, 'resume'])->name('subscription.resume');
});
