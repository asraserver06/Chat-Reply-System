<?php

use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
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
    return view('dashboard');
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

    /*
    |--------------------------------------------------------------------------
    | Email Verification Routes
    |--------------------------------------------------------------------------
    */

    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard');
    })->middleware(['auth', 'signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');
});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Only users with "Admin" role can access these routes.
| You can add more controllers as needed for admin functionality.
|
*/
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard'); // Your admin dashboard view
    })->name('admin.dashboard');

    Route::get('/admin/users', function () {
        return view('admin.users'); // Manage users page
    })->name('admin.users');

    // Example: permission-protected route
    Route::get('/admin/manage-users', function () {
        return 'You can manage users!';
    })->middleware('permission:manage users');
});


/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
|
| Only users with "User" role can access these routes.
|
*/
Route::middleware(['auth', 'role:User'])->group(function () {
    Route::get('/user/dashboard', function () {
        return view('user.dashboard'); // Your user dashboard view
    })->name('user.dashboard');

    Route::get('/user/messages', function () {
        return view('user.messages'); // User messages page
    })->name('user.messages');

    // Example: permission-protected route
    Route::get('/user/send-message', function () {
        return 'You can send messages!';
    })->middleware('permission:send messages');
});
