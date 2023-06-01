<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\PasswordResetController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
})->name('user.get');


Route::controller(AuthController::class)->group(function () {
    Route::get('email/verify/{id}/{hash}', 'verifyEmail')->middleware(['signed'])->name('verification.verify');
    Route::post('/login', 'login')->name('login');
    Route::post('/register', 'register')->name('register');
    Route::post('/logout', 'logout')->name('logout');
});


Route::middleware(['web'])->group(function () {
    Route::controller(GoogleAuthController::class)->group(function () {
        Route::get('/auth/redirect', 'redirectToProvider')->name('google-auth.redirect');
        Route::get('/auth/callback', 'handleCallback')->name('google-auth.callback');
    });


});

Route::controller(PasswordResetController::class)->group(function () {
    Route::post('/forgot-password', 'sendResetLink')->middleware('guest')->name('password.email');
    Route::get('/reset-password/{token}/{email}', 'redirectToResetForm')->middleware('guest')->name('password.reset');
    Route::post('/reset-password', 'updatePassword')->middleware('guest')->name('password.update');
});
