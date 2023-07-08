<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\LikeCommentController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProfileUpdateController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\SearchController;

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
	Route::middleware(['guest'])->group(function () {
		Route::post('/forgot-password', 'sendResetLink')->name('password.email');
		Route::get('/reset-password/{token}/{email}', 'redirectToResetForm')->name('password.reset');
		Route::post('/reset-password', 'updatePassword')->name('password.update');
	});
});

Route::middleware(['auth:sanctum'])->group(function () {
	Route::controller(MovieController::class)->group(function () {
		Route::get('/movies', 'index')->name('movies.index');
		Route::post('/movies', 'store')->name('movies.store');
		Route::post('/movies/{movie}', 'update')->name('movies.update');
		Route::delete('/movies/{movie}', 'destroy')->name('movies.destroy');
		Route::get('/movies/paginate', 'getPaginatedMovies')->name('movies.get_paginated_movies');
	});
	Route::controller(QuoteController::class)->group(function () {
		Route::get('/quotes', 'index')->name('quotes.index');
		Route::post('/quotes', 'store')->name('quotes.store');
		Route::delete('/quotes/{quote}', 'destroy')->name('quotes.destroy');
		Route::put('/quotes/{quote}', 'update')->name('quotes.update');
		Route::get('/quotes/paginate', 'getPaginatedQuotes')->name('quotes.get_paginated_quotes');
	});
	Route::get('/user', [AuthController::class, 'getUser'])->name('user.get');
	Route::get('/search/{string}', [SearchController::class, 'search'])->name('post.search');
	Route::controller(LikeCommentController::class)->group(function (){
		Route::post('/{quote}/add-like', 'addLike')->name('quote.add_like');
		Route::post('/{quote}/add-comment','addComment')->name('quote.add_comment');
	});
	Route::controller(NotificationController::class)->group(function(){
		Route::post('/{notification}/mark-as-read','markAsRead')->name('notification.mark_as_read');
		Route::post('/notifications/mark-all-as-read','markAllAsRead')->name('notification.mark_all_as_read');
		Route::get('/notifications', 'getNotifications')-> name('notification.get');
	});
});
Route::post('/update-profile', [ProfileUpdateController::class, 'updateProfile'])->name('user.update');
